<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Notifications\BookingCancelledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\RefundCalculator;

class BookingController extends Controller
{
    // ── My Bookings ───────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');

        $query = Booking::with(['experience.photos', 'experience.kategori', 'review'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        match ($filter) {
            'upcoming' => $query->whereIn('status', ['confirmed', 'pending_payment'])
                ->where('tanggal_experience', '>=', now()->toDateString()),
            'completed' => $query->where('status', 'completed'),
            'cancelled' => $query->whereIn('status', ['cancelled', 'expired', 'refunded']),
            default => null,
        };

        $bookings = $query->get();

        // Cari booking upcoming terdekat untuk banner
        $nextBooking = Booking::with('experience')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed'])
            ->where('tanggal_experience', '>=', now()->toDateString())
            ->orderBy('tanggal_experience', 'asc')
            ->first();

        $daysUntilNext = null;
        if ($nextBooking) {
            $daysUntilNext = (int) now()->startOfDay()
                ->diffInDays(Carbon::parse($nextBooking->tanggal_experience)->startOfDay());
        }

        return view('pages.my-bookings', compact('bookings', 'filter', 'nextBooking', 'daysUntilNext'));
    }

    // ── Detail Booking ────────────────────────────────────────────────────

    public function show(string $kode)
    {
        $booking = Booking::with(['experience.photos', 'experience.host.user', 'experience.kategori', 'complaints', 'memoryBook'])
            ->where('kode_booking', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $locale = app()->getLocale();

        return view('pages.booking-detail', compact('booking', 'locale'));
    }

    // ── Cancel Booking ────────────────────────────────────────────────────

    public function cancelConfirm(string $kode)
    {
        $booking = Booking::with('experience')
            ->where('kode_booking', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!in_array($booking->status, ['confirmed'])) {
            return redirect()
                ->route('bookings.show', $kode)
                ->with('error', 'Booking ini tidak bisa dibatalkan.');
        }

        $calculator = app(RefundCalculator::class);
        $refundPercentage = $calculator->calculatePercentage($booking);
        $refundAmount = $calculator->calculateAmount($booking, $refundPercentage);
        $policyDescription = $calculator->getPolicyDescription($booking);

        return view('pages.booking-cancel-confirm', compact(
            'booking',
            'refundPercentage',
            'refundAmount',
            'policyDescription'
        ));
    }

    /**
     * Eksekusi cancel — dipanggil setelah traveler konfirmasi di halaman cancel-confirm.
     * Route: PATCH /bookings/{kode}/cancel
     */
    public function cancel(Request $request, string $kode)
    {
        $booking = Booking::with(['experience', 'availability', 'host.user'])
            ->where('kode_booking', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!in_array($booking->status, ['confirmed'])) {
            return redirect()
                ->route('bookings.show', $kode)
                ->with('error', 'Booking ini tidak bisa dibatalkan.');
        }

        $calculator = app(RefundCalculator::class);
        $refundPercentage = $calculator->calculatePercentage($booking);
        $refundAmount = $calculator->calculateAmount($booking, $refundPercentage);

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => 'traveler',
            'cancel_reason' => $request->input('reason', 'Dibatalkan oleh traveler'),
            'refund_percentage' => $refundPercentage,
            'refund_amount' => $refundAmount,
            'refund_status' => $refundAmount > 0 ? 'pending' : 'not_applicable',
        ]);

        // Kembalikan slot yang sempat ditahan
        if ($booking->availability) {
            $booking->availability->decrement('booked_slot', $booking->jumlah_peserta);
        }

        // ── Notifikasi ke host ──
        try {
            $booking->host?->user?->notify(new BookingCancelledNotification($booking));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim notif BookingCancelled', ['error' => $e->getMessage()]);
        }

        $message = $refundAmount > 0
            ? "Booking dibatalkan. Refund sebesar Rp " . number_format($refundAmount, 0, ',', '.') . " akan diproses oleh tim kami dalam beberapa hari kerja."
            : "Booking dibatalkan. Sesuai kebijakan, tidak ada refund untuk pembatalan ini.";

        return redirect()
            ->route('bookings.show', $kode)
            ->with('success', $message);
    }
}