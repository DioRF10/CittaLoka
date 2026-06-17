<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Experience;
use App\Models\ExperienceAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HostDashboardController extends Controller
{
    private function getHost()
    {
        return Auth::user()->host;
    }

    // ── Overview ──────────────────────────────────────────────────────────

    public function index()
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        $locale = app()->getLocale();

        // Statistik experience
        $totalExperiences = Experience::where('host_id', $host->id)->count();
        $activeExperiences = Experience::where('host_id', $host->id)->where('status', 'active')->count();
        $draftExperiences = Experience::where('host_id', $host->id)->where('status', 'draft')->count();
        $pendingExperiences = Experience::where('host_id', $host->id)->where('status', 'pending_review')->count();

        // Statistik booking
        $totalBookings = Booking::where('host_id', $host->id)->count();
        $confirmedBookings = Booking::where('host_id', $host->id)->where('status', 'confirmed')->count();
        $completedBookings = Booking::where('host_id', $host->id)->where('status', 'completed')->count();

        // Pendapatan bulan ini
        $earningsThisMonth = Booking::where('host_id', $host->id)
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->sum('host_earning');

        // Total pendapatan
        $totalEarnings = Booking::where('host_id', $host->id)
            ->where('status', 'completed')
            ->sum('host_earning');

        // Booking terbaru
        $recentBookings = Booking::with(['experience', 'user'])
            ->where('host_id', $host->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming bookings
        $upcomingBookings = Booking::with(['experience', 'user'])
            ->where('host_id', $host->id)
            ->where('status', 'confirmed')
            ->where('tanggal_experience', '>=', now()->toDateString())
            ->orderBy('tanggal_experience', 'asc')
            ->take(3)
            ->get();

        return view('host.dashboard', compact(
            'host', 'locale',
            'totalExperiences', 'activeExperiences', 'draftExperiences', 'pendingExperiences',
            'totalBookings', 'confirmedBookings', 'completedBookings',
            'earningsThisMonth', 'totalEarnings',
            'recentBookings', 'upcomingBookings'
        ));
    }

    // ── My Experiences ────────────────────────────────────────────────────

    public function experiences(Request $request)
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        $filter = $request->input('filter', 'all');
        $locale = app()->getLocale();

        $query = Experience::with(['photos', 'kategori'])
            ->where('host_id', $host->id)
            ->orderBy('created_at', 'desc');

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $experiences = $query->paginate(10)->withQueryString();

        // Stats
        $stats = [
            'active'  => Experience::where('host_id', $host->id)->where('status', 'active')->count(),
            'draft'   => Experience::where('host_id', $host->id)->where('status', 'draft')->count(),
            'pending' => Experience::where('host_id', $host->id)->where('status', 'pending_review')->count(),
            'rejected'=> Experience::where('host_id', $host->id)->where('status', 'rejected')->count(),
        ];

        return view('host.experiences', compact('host', 'experiences', 'filter', 'stats', 'locale'));
    }

    public function deleteExperience(int $id)
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        $experience = Experience::where('host_id', $host->id)->findOrFail($id);

        // Cegah hapus jika ada booking aktif
        $activeBookings = Booking::where('experience_id', $experience->id)
            ->whereIn('status', ['pending_payment', 'confirmed'])
            ->count();

        if ($activeBookings > 0) {
            return back()->with('error', 'Tidak bisa menghapus experience — masih ada ' . $activeBookings . ' booking aktif.');
        }

        $experience->delete();

        return redirect()->route('host.experiences.index')
            ->with('success', 'Experience "' . $experience->judul_id . '" berhasil dihapus.');
    }

    // ── Create / Edit Experience ──────────────────────────────────────────

    public function createExperience()
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        // Pastikan KTP sudah verified
        if ($host->ktp_status !== 'verified') {
            return redirect()->route('host.experiences.index')
                ->with('error', 'KTP kamu belum terverifikasi. Tunggu konfirmasi admin sebelum membuat experience.');
        }

        $categories = \App\Models\Kategori::orderBy('nama')->get();

        return view('host.experiences.create', compact('host', 'categories'));
    }

    public function editExperience(int $id)
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        $experience = Experience::with(['photos', 'kategori'])
            ->where('host_id', $host->id)
            ->findOrFail($id);

        $categories = \App\Models\Kategori::orderBy('nama')->get();

        return view('host.experiences.create', compact('host', 'experience', 'categories'));
    }

    // ── Memory Books ──────────────────────────────────────────────────────

    public function memoryBooks()
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        $urgent = \App\Models\MemoryBook::with(['booking.user', 'booking.experience'])
            ->whereHas('booking', fn($q) => $q->where('host_id', $host->id))
            ->where('status', 'pending_host')
            ->where('updated_at', '<', now()->subHours(24))
            ->orderBy('updated_at')
            ->get();

        $all = \App\Models\MemoryBook::with(['booking.user', 'booking.experience'])
            ->whereHas('booking', fn($q) => $q->where('host_id', $host->id))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('host.memory-books', compact('host', 'urgent', 'all'));
    }

    // ── Bookings ──────────────────────────────────────────────────────────

    public function bookings(Request $request)
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        $filter = $request->input('filter', 'all');

        $query = Booking::with(['experience', 'user'])
            ->where('host_id', $host->id)
            ->orderBy('created_at', 'desc');

        match($filter) {
            'upcoming'  => $query->whereIn('status', ['confirmed'])->where('tanggal_experience', '>=', now()->toDateString()),
            'completed' => $query->where('status', 'completed'),
            'cancelled' => $query->whereIn('status', ['cancelled', 'expired']),
            default     => null,
        };

        $bookings = $query->paginate(15)->withQueryString();

        return view('host.bookings', compact('host', 'bookings', 'filter'));
    }

    public function bookingDetail(int $id)
    {
        $host = $this->getHost();
        if (!$host) return response()->json(['error' => 'Unauthorized'], 403);

        $booking = Booking::with(['experience', 'user', 'coupon'])
            ->where('host_id', $host->id)
            ->findOrFail($id);

        return response()->json([
            'kode_booking'               => $booking->kode_booking,
            'status'                     => $booking->status,
            'status_label'               => $booking->getStatusLabel(),
            'guest_name'                 => $booking->user->name,
            'guest_email'                => $booking->user->email,
            'experience_title'           => $booking->experience_title_snapshot,
            'location'                   => $booking->location_snapshot,
            'tanggal'                    => Carbon::parse($booking->tanggal_experience)->format('d M Y'),
            'jam'                        => $booking->jam_experience
                                              ? Carbon::parse($booking->jam_experience)->format('H:i') . ' WITA'
                                              : null,
            'jumlah_peserta'             => $booking->jumlah_peserta,
            'is_private'                 => $booking->is_private,
            'harga_per_orang'            => 'Rp ' . number_format($booking->harga_per_orang_snapshot, 0, ',', '.'),
            'total_harga'                => 'Rp ' . number_format($booking->total_harga, 0, ',', '.'),
            'platform_fee'               => 'Rp ' . number_format($booking->platform_fee, 0, ',', '.'),
            'host_earning'               => 'Rp ' . number_format($booking->host_earning, 0, ',', '.'),
            'discount'                   => $booking->discount_amount > 0
                                              ? 'Rp ' . number_format($booking->discount_amount, 0, ',', '.')
                                              : null,
            'coupon_code'                => $booking->coupon?->code,
            'notes_for_host'             => $booking->notes_for_host,
            'created_at'                 => Carbon::parse($booking->created_at)->format('d M Y, H:i'),
            'cancelled_at'               => $booking->cancelled_at
                                              ? Carbon::parse($booking->cancelled_at)->format('d M Y, H:i')
                                              : null,
            'cancel_reason'              => $booking->cancel_reason,
            'completed_at'               => $booking->completed_at
                                              ? Carbon::parse($booking->completed_at)->format('d M Y, H:i')
                                              : null,
        ]);
    }

    // ── Availability ──────────────────────────────────────────────────────

    public function availability(Request $request)
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        $experiences = Experience::where('host_id', $host->id)
            ->whereIn('status', ['active', 'draft'])
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedExpId = $request->input('experience_id', $experiences->first()?->id);

        $availabilities = collect();
        if ($selectedExpId) {
            $availabilities = ExperienceAvailability::where('experience_id', $selectedExpId)
                ->where('date', '>=', now()->toDateString())
                ->orderBy('date')
                ->orderBy('time')
                ->get();
        }

        return view('host.availability', compact('host', 'experiences', 'selectedExpId', 'availabilities'));
    }

    public function storeAvailability(Request $request)
    {
        $host = $this->getHost();

        $request->validate([
            'experience_id' => 'required|exists:experience,id',
            'date'          => 'required|date|after_or_equal:today',
            'times'         => 'required|array|min:1',
            'times.*.time'  => 'required',
            'times.*.max_slot' => 'required|integer|min:1',
        ]);

        // Pastikan experience milik host ini
        $exp = Experience::where('id', $request->experience_id)
            ->where('host_id', $host->id)
            ->firstOrFail();

        foreach ($request->times as $slot) {
            ExperienceAvailability::updateOrCreate(
                [
                    'experience_id' => $exp->id,
                    'date'          => $request->date,
                    'time'          => $slot['time'],
                ],
                [
                    'max_slot'   => $slot['max_slot'],
                    'is_blocked' => false,
                ]
            );
        }

        return back()->with('success', 'Availability berhasil disimpan!');
    }

    public function deleteAvailability(Request $request, int $id)
    {
        $host = $this->getHost();

        $avail = ExperienceAvailability::whereHas('experience', function($q) use ($host) {
            $q->where('host_id', $host->id);
        })->findOrFail($id);

        // Jangan hapus kalau sudah ada booking
        if ($avail->booked_slot > 0) {
            return back()->with('error', 'Tidak bisa hapus — sudah ada yang booking slot ini.');
        }

        $avail->delete();
        return back()->with('success', 'Slot berhasil dihapus.');
    }

    // ── Earnings ──────────────────────────────────────────────────────────

    public function earnings()
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        $totalEarnings = Booking::where('host_id', $host->id)
            ->where('status', 'completed')
            ->sum('host_earning');

        $thisMonthEarnings = Booking::where('host_id', $host->id)
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->sum('host_earning');

        $lastMonthEarnings = Booking::where('host_id', $host->id)
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->subMonth()->month)
            ->whereYear('completed_at', now()->subMonth()->year)
            ->sum('host_earning');

        // Earnings per bulan (6 bulan terakhir)
        $monthlyEarnings = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyEarnings[] = [
                'label'    => $month->format('M Y'),
                'earnings' => Booking::where('host_id', $host->id)
                    ->where('status', 'completed')
                    ->whereMonth('completed_at', $month->month)
                    ->whereYear('completed_at', $month->year)
                    ->sum('host_earning'),
            ];
        }

        $completedBookings = Booking::with(['experience', 'user'])
            ->where('host_id', $host->id)
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->paginate(10);

        return view('host.earnings', compact(
            'host', 'totalEarnings', 'thisMonthEarnings',
            'lastMonthEarnings', 'monthlyEarnings', 'completedBookings'
        ));
    }

    // ── Settings ──────────────────────────────────────────────────────────

    public function settings()
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        return view('host.settings', compact('host'));
    }

    public function updateSettings(Request $request)
    {
        $host = $this->getHost();

        $request->validate([
            'bio'                 => 'nullable|string|max:500',
            'village'             => 'nullable|string|max:100',
            'bank_name'           => 'nullable|string|max:100',
            'bank_account_name'   => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
        ]);

        $host->update($request->only([
            'bio', 'village', 'bank_name',
            'bank_account_name', 'bank_account_number',
        ]));

        Auth::user()->update([
            'name' => $request->input('name', Auth::user()->name),
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
