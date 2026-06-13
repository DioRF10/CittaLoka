<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    // ── My Bookings ───────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');

        $query = Booking::with(['experience.photos', 'experience.kategori'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        match($filter) {
            'upcoming'  => $query->whereIn('status', ['confirmed', 'pending_payment'])
                                 ->where('tanggal_experience', '>=', now()->toDateString()),
            'completed' => $query->where('status', 'completed'),
            'cancelled' => $query->whereIn('status', ['cancelled', 'expired', 'refunded']),
            default     => null,
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
        $booking = Booking::with(['experience.photos', 'experience.host.user', 'experience.kategori'])
            ->where('kode_booking', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $locale = app()->getLocale();

        return view('pages.booking-detail', compact('booking', 'locale'));
    }

    // ── Cancel Booking ────────────────────────────────────────────────────

    public function cancel(Request $request, string $kode)
    {
        $booking = Booking::where('kode_booking', $kode)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'pending_payment'])
            ->firstOrFail();

        // Cek 24 jam sebelum experience
        $experienceDateTime = Carbon::parse($booking->tanggal_experience);
        if (now()->diffInHours($experienceDateTime, false) < 24) {
            return back()->with('error', 'Tidak bisa cancel kurang dari 24 jam sebelum experience.');
        }

        // Update status booking
        $booking->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
            'cancel_reason'=> $request->input('reason', 'Cancelled by user'),
        ]);

        // Kembalikan slot
        if ($booking->availability) {
            $booking->availability->decrement('booked_slot', $booking->jumlah_peserta);
        }

        return redirect()->route('bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}