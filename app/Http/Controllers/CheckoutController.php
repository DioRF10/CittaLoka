<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Experience;
use App\Models\ExperienceAvailability;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckoutController extends Controller
{

    // ── AJAX: Terapkan Kupon ────────────────────────────────────────────

    public function applyCoupon(Request $request, string $slug)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'guests' => 'required|integer|min:1',
        ]);

        $experience = Experience::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $subtotal = (float) $experience->harga * (int) $request->guests;

        $coupon = Coupon::whereRaw('UPPER(code) = ?', [strtoupper($request->code)])->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Kode kupon tidak ditemukan.']);
        }

        $error = $coupon->validationError($subtotal);
        if ($error) {
            return response()->json(['success' => false, 'message' => $error]);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'message' => 'Kupon berhasil diterapkan!',
            'discount_amount' => $discount,
            'code' => $coupon->code,
        ]);
    }

    // ── Step 2: Halaman Konfirmasi ────────────────────────────────────────

    public function show(string $slug, Request $request)
    {
        // Host tidak boleh melakukan booking
        if (Auth::check() && Auth::user()->isHost()) {
            return redirect()->route('experiences.show', $slug)
                ->with('error', 'Sebagai host, kamu tidak bisa melakukan booking. Gunakan akun traveler untuk memesan experience.');
        }

        $experience = Experience::with(['host.user', 'kategori', 'photos'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Validasi parameter dari booking widget
        $date   = $request->input('date');
        $time   = $request->input('time');
        $guests = (int) $request->input('guests', $experience->kapasitas_min);

        // Redirect balik kalau parameter tidak lengkap
        if (!$date || !$time) {
            return redirect()->route('experiences.show', $slug)
                ->with('error', 'Pilih tanggal dan jam terlebih dahulu.');
        }

        // Cari availability yang sesuai
        $availability = ExperienceAvailability::where('experience_id', $experience->id)
            ->where('date', $date)
            ->where('time', $time)
            ->where('is_blocked', false)
            ->first();

        if (!$availability || $availability->getAvailableSlot() < $guests) {
            return redirect()->route('experiences.show', $slug)
                ->with('error', 'Slot tidak tersedia untuk tanggal dan jam yang dipilih.');
        }

        // Hitung harga
        $hargaPerOrang = (float) $experience->harga;
        $subtotal      = $hargaPerOrang * $guests;
        $platformFee   = round($subtotal * 0.10); // 10%
        $total         = $subtotal + $platformFee;
        $hostEarning   = $subtotal - 0; // host dapat subtotal penuh, platform fee di atas

        // Format tanggal & waktu
        $tanggal = Carbon::parse($date)->locale('en')->isoFormat('ddd, MMM D, YYYY');
        $jamMulai = Carbon::parse($time)->format('H:i');
        $jamSelesai = Carbon::parse($time)->addMinutes($experience->durasi_menit)->format('H:i');

        $cover = $experience->photos->where('is_cover', true)->first()
               ?? $experience->photos->first();

        $locale = app()->getLocale();

        return view('pages.checkout', compact(
            'experience',
            'availability',
            'date',
            'time',
            'guests',
            'hargaPerOrang',
            'subtotal',
            'platformFee',
            'total',
            'hostEarning',
            'tanggal',
            'jamMulai',
            'jamSelesai',
            'cover',
            'locale'
        ));
    }

    // ── Step 3: Proses Simpan Booking + Buat Invoice Xendit ───────────────

    public function store(Request $request, string $slug)
    {
        // Host tidak boleh melakukan booking
        if (Auth::check() && Auth::user()->isHost()) {
            return redirect()->route('experiences.show', $slug)
                ->with('error', 'Sebagai host, kamu tidak bisa melakukan booking.');
        }

        $request->validate([
            'date'         => 'required|date',
            'time'         => 'required',
            'guests'       => 'required|integer|min:1',
            'phone_number' => 'required|string|max:20',
            'agree_terms'  => 'accepted',
        ], [
            'agree_terms.accepted' => 'Kamu harus menyetujui Terms & Conditions.',
        ]);

        $experience = Experience::with('host.user')
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $availability = ExperienceAvailability::where('experience_id', $experience->id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->where('is_blocked', false)
            ->firstOrFail();

        $guests        = (int) $request->guests;
        $hargaPerOrang = (float) $experience->harga;
        $subtotal      = $hargaPerOrang * $guests;
        $platformFee   = round($subtotal * 0.10);
        $hostEarning   = $subtotal;

        // ── Validasi ulang kupon di server (jangan percaya nominal dari client) ──
        $coupon = null;
        $discountAmount = 0;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::whereRaw('UPPER(code) = ?', [strtoupper($request->coupon_code)])->first();

            if ($coupon && !$coupon->validationError($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
            } else {
                // Kupon jadi tidak valid lagi (misal kepakai orang lain duluan) — abaikan diam-diam,
                // jangan gagalkan booking-nya, cukup tidak dapat diskon.
                $coupon = null;
                $discountAmount = 0;
            }
        }

        $total = $subtotal + $platformFee - $discountAmount;

        $locale = app()->getLocale();

        // Buat booking dengan status pending_payment
        $booking = Booking::create([
            'kode_booking'              => Booking::generateKode(),
            'user_id'                   => Auth::id(),
            'experience_id'             => $experience->id,
            'host_id'                   => $experience->host_id,
            'availability_id'           => $availability->id,
            'experience_title_snapshot' => $experience->getJudul($locale),
            'host_name_snapshot'        => $experience->host->user->name,
            'location_snapshot'         => $experience->lokasi_nama,
            'harga_per_orang_snapshot'  => $hargaPerOrang,
            'tanggal_experience'        => $request->date,
            'jam_experience'            => $request->time,
            'jumlah_peserta'            => $guests,
            'is_private'                => false,
            'total_harga'               => $total,
            'platform_fee'              => $platformFee,
            'host_earning'              => $hostEarning,
            'coupon_id'                 => $coupon?->id,
            'discount_amount'           => $discountAmount,
            'status'                    => 'pending_payment',
            'payment_status'            => 'unpaid',
            'notes_for_host'            => $request->input('notes_for_host'),
        ]);

        if ($coupon) {
            $coupon->increment('used_count');
        }

        // Tahan slot dulu (akan dikembalikan otomatis kalau invoice expired)
        $availability->increment('booked_slot', $guests);

        // ── Buat Invoice Xendit ──
        try {
            $xendit = app(XenditService::class);

            $invoice = $xendit->createInvoice(
                externalId: $booking->kode_booking,
                amount: (int) $total,
                description: "Booking: {$booking->experience_title_snapshot}",
                customer: [
                    'given_names'   => Auth::user()->name,
                    'email'         => Auth::user()->email,
                    'mobile_number' => $request->phone_number,
                    'items'         => [
                        [
                            'name'     => $booking->experience_title_snapshot,
                            'quantity' => $guests,
                            'price'    => (int) $hargaPerOrang,
                        ],
                    ],
                ],
                successRedirectUrl: route('checkout.success', $booking->kode_booking),
                failureRedirectUrl: route('experiences.show', $slug),
            );

            $booking->update([
                'xendit_invoice_id'   => $invoice['id'],
                'xendit_invoice_url'  => $invoice['invoice_url'],
                'payment_expired_at'  => $invoice['expiry_date'] ?? now()->addHours(24),
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Checkout Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            // Kalau gagal buat invoice, rollback booking & slot
            $availability->decrement('booked_slot', $guests);
            $booking->delete();

            return redirect()
                ->route('experiences.show', $slug)
                ->with('error', 'Gagal membuat invoice pembayaran. Silakan coba lagi. (' . $e->getMessage() . ')');
        }

        // Redirect ke halaman pembayaran Xendit
        return redirect()->away($booking->xendit_invoice_url);
    }

    // ── Step 4: Halaman Success (setelah dibayar) ─────────────────────────

    public function success(string $kodeBooking)
    {
        $booking = Booking::with(['experience.photos', 'experience.host.user'])
            ->where('kode_booking', $kodeBooking)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Catatan: status sebenarnya di-update via webhook, bukan di sini.
        // Halaman ini hanya tampilan setelah redirect dari Xendit.
        // Kalau status masih pending_payment saat halaman ini dibuka,
        // tampilkan pesan "menunggu konfirmasi pembayaran".

        return view('pages.checkout-success', compact('booking'));
    }
}