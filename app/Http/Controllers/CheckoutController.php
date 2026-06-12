<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Experience;
use App\Models\ExperienceAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckoutController extends Controller
{

    // ── Step 2: Halaman Konfirmasi ────────────────────────────────────────

    public function show(string $slug, Request $request)
    {
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

    // ── Step 3: Proses Simpan Booking ─────────────────────────────────────

    public function store(Request $request, string $slug)
    {
        $request->validate([
            'date'         => 'required|date',
            'time'         => 'required',
            'guests'       => 'required|integer|min:1',
            'phone_number' => 'required|string|max:20',
            'agree_terms'  => 'accepted',
        ], [
            'agree_terms.accepted' => 'Kamu harus menyetujui Terms & Conditions.',
        ]);

        $experience = Experience::with('host')
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $availability = ExperienceAvailability::where('experience_id', $experience->id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->where('is_blocked', false)
            ->firstOrFail();

        $guests      = (int) $request->guests;
        $hargaPerOrang = (float) $experience->harga;
        $subtotal    = $hargaPerOrang * $guests;
        $platformFee = round($subtotal * 0.10);
        $total       = $subtotal + $platformFee;
        $hostEarning = $subtotal;

        $locale = app()->getLocale();

        // Buat booking
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
            'discount_amount'           => 0,
            'status'                    => 'pending_payment',
            'payment_status'            => 'unpaid',
            'notes_for_host'            => $request->input('notes_for_host'),
        ]);

        // Update booked_slot di availability
        $availability->increment('booked_slot', $guests);

        // Untuk sementara (sebelum Midtrans), langsung confirmed
        $booking->update([
            'status'         => 'confirmed',
            'payment_status' => 'paid',
        ]);

        return redirect()->route('checkout.success', $booking->kode_booking);
    }

    // ── Step 4: Halaman Success ───────────────────────────────────────────

    public function success(string $kodeBooking)
    {
        $booking = Booking::with(['experience.photos', 'experience.host.user'])
            ->where('kode_booking', $kodeBooking)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pages.checkout-success', compact('booking'));
    }
}