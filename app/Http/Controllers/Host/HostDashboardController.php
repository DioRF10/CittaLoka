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
        if (!$host)
            return redirect()->route('home');

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

        // ── Mini Chart: Pendapatan 6 Bulan Terakhir ──
        $miniChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $miniChartData[] = [
                'label' => $month->locale('id')->isoFormat('MMM'),
                'earnings' => (int) Booking::where('host_id', $host->id)
                    ->where('status', 'completed')
                    ->whereMonth('completed_at', $month->month)
                    ->whereYear('completed_at', $month->year)
                    ->sum('host_earning'),
            ];
        }

        return view('host.dashboard', compact(
            'host',
            'locale',
            'totalExperiences',
            'activeExperiences',
            'draftExperiences',
            'pendingExperiences',
            'totalBookings',
            'confirmedBookings',
            'completedBookings',
            'earningsThisMonth',
            'totalEarnings',
            'recentBookings',
            'upcomingBookings',
            'miniChartData'
        ));
    }


    // ── My Experiences ────────────────────────────────────────────────────

    public function experiences(Request $request)
    {
        $host = $this->getHost();
        if (!$host)
            return redirect()->route('home');

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
            'active' => Experience::where('host_id', $host->id)->where('status', 'active')->count(),
            'draft' => Experience::where('host_id', $host->id)->where('status', 'draft')->count(),
            'pending' => Experience::where('host_id', $host->id)->where('status', 'pending_review')->count(),
            'rejected' => Experience::where('host_id', $host->id)->where('status', 'rejected')->count(),
        ];

        return view('host.experiences', compact('host', 'experiences', 'filter', 'stats', 'locale'));
    }

    public function deleteExperience(int $id)
    {
        $host = $this->getHost();
        if (!$host)
            return redirect()->route('home');

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
        if (!$host)
            return redirect()->route('home');

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
        if (!$host)
            return redirect()->route('home');

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
        if (!$host)
            return redirect()->route('home');

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
        if (!$host)
            return redirect()->route('home');

        $filter = $request->input('filter', 'all');

        $query = Booking::with(['experience', 'user'])
            ->where('host_id', $host->id)
            ->orderBy('created_at', 'desc');

        match ($filter) {
            'upcoming' => $query->whereIn('status', ['confirmed'])->where('tanggal_experience', '>=', now()->toDateString()),
            'completed' => $query->where('status', 'completed'),
            'cancelled' => $query->whereIn('status', ['cancelled', 'expired']),
            default => null,
        };

        $bookings = $query->paginate(15)->withQueryString();

        return view('host.bookings', compact('host', 'bookings', 'filter'));
    }

    public function bookingDetail(int $id)
    {
        $host = $this->getHost();
        if (!$host)
            return response()->json(['error' => 'Unauthorized'], 403);

        $booking = Booking::with(['experience', 'user'])
            ->where('host_id', $host->id)
            ->findOrFail($id);

        $user = $booking->user;
        $isConfirmed = in_array($booking->status, ['confirmed', 'completed']);

        // Hitung jam selesai
        $jamSelesai = null;
        if ($booking->jam_experience && $booking->experience?->durasi_menit) {
            $jamSelesai = Carbon::parse($booking->jam_experience)
                ->addMinutes($booking->experience->durasi_menit)
                ->format('H:i') . ' WITA';
        }

        return response()->json([
            'kode_booking' => $booking->kode_booking,
            'status' => $booking->status,
            'status_label' => $booking->getStatusLabel(),
            'guest_name' => $user->name,
            'guest_email' => $user->email,
            'guest_avatar' => $user->avatar
                ? asset('storage/' . $user->avatar)
                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=2D5240&color=fff&size=200',
            'guest_phone' => $isConfirmed
                ? ($user->country_code && $user->phone_number
                    ? $user->country_code . ' ' . $user->phone_number
                    : $user->phone_number)
                : null,
            'guest_is_verified' => !is_null($user->email_verified_at),
            'experience_title' => $booking->experience_title_snapshot,
            'location' => $booking->location_snapshot,
            'meeting_point' => $booking->experience?->meeting_point,
            'tanggal' => Carbon::parse($booking->tanggal_experience)->format('d M Y'),
            'jam' => $booking->jam_experience
                ? Carbon::parse($booking->jam_experience)->format('H:i') . ' WITA'
                : null,
            'jam_selesai' => $jamSelesai,
            'jumlah_peserta' => $booking->jumlah_peserta,
            'is_private' => $booking->is_private,
            'harga_per_orang' => 'Rp ' . number_format($booking->harga_per_orang_snapshot, 0, ',', '.'),
            'total_harga' => 'Rp ' . number_format($booking->total_harga, 0, ',', '.'),
            'platform_fee' => 'Rp ' . number_format($booking->platform_fee, 0, ',', '.'),
            'host_earning' => 'Rp ' . number_format($booking->host_earning, 0, ',', '.'),
            'discount' => $booking->discount_amount > 0
                ? 'Rp ' . number_format($booking->discount_amount, 0, ',', '.')
                : null,
            'coupon_code' => null,
            'notes_for_host' => $booking->notes_for_host,
            'can_file_complaint' => $booking->status === 'completed'
                && \App\Models\Complaint::canFileFor($booking)
                && !$booking->complaints()->where('filed_by_user_id', auth()->id())->exists(),
            'complaint_deadline' => optional(\App\Models\Complaint::deadlineFor($booking))->translatedFormat('d M Y, H:i'),
            'my_complaint_status' => optional(
                $booking->complaints()->where('filed_by_user_id', auth()->id())->first()
            )?->getStatusLabel(),
            'complaint_disabled_reason' => match (true) {
                $booking->status === 'confirmed' => 'Complaint bisa diajukan setelah experience selesai.',
                $booking->status === 'completed'
                    && optional(\App\Models\Complaint::deadlineFor($booking))->isPast() => 'Batas waktu pengajuan complaint sudah lewat.',
                default => null,
            },
        ]);
    }

    // ── Availability ──────────────────────────────────────────────────────

    public function availability(Request $request)
    {
        $host = $this->getHost();
        if (!$host)
            return redirect()->route('home');

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
            'date' => 'required|date|after_or_equal:today',
            'times' => 'required|array|min:1',
            'times.*.time' => 'required',
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
                    'date' => $request->date,
                    'time' => $slot['time'],
                ],
                [
                    'max_slot' => $slot['max_slot'],
                    'is_blocked' => false,
                ]
            );
        }

        return back()->with('success', 'Availability berhasil disimpan!');
    }

    public function deleteAvailability(Request $request, int $id)
    {
        $host = $this->getHost();

        $avail = ExperienceAvailability::whereHas('experience', function ($q) use ($host) {
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

    public function earnings(Request $request)
    {
        $host = $this->getHost();
        if (!$host)
            return redirect()->route('home');

        $tab = $request->input('tab', 'overview'); // 'overview' atau 'payouts'

        // ── Filter (dipakai khusus di tab Payout History) ──
        $dateRange = $request->input('date_range', 'all');   // all | 7d | 30d | this_month | this_year | custom
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $statusFilter = $request->input('status', 'all');       // all | pending | processing | success | failed
        $search = $request->input('search');

        // ── Summary Cards (selalu total keseluruhan, tidak terpengaruh filter) ──
        $totalEarnings = Booking::where('host_id', $host->id)
            ->where('status', 'completed')
            ->sum('host_earning');

        $totalCompletedBookings = Booking::where('host_id', $host->id)
            ->where('status', 'completed')
            ->count();

        $averagePerBooking = $totalCompletedBookings > 0
            ? (int) round($totalEarnings / $totalCompletedBookings)
            : 0;

        $pendingDisbursement = Booking::where('host_id', $host->id)
            ->where('status', 'completed')
            ->whereIn('disbursement_status', ['pending', 'processing'])
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

        $monthOverMonthChange = $lastMonthEarnings > 0
            ? round((($thisMonthEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100, 1)
            : ($thisMonthEarnings > 0 ? 100 : 0);

        // ── Chart 1: Trend Pendapatan Bulanan (12 bulan terakhir) ──
        $monthlyEarnings = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyEarnings[] = [
                'label' => $month->locale('id')->isoFormat('MMM YYYY'),
                'earnings' => (int) Booking::where('host_id', $host->id)
                    ->where('status', 'completed')
                    ->whereMonth('completed_at', $month->month)
                    ->whereYear('completed_at', $month->year)
                    ->sum('host_earning'),
                'bookings' => Booking::where('host_id', $host->id)
                    ->where('status', 'completed')
                    ->whereMonth('completed_at', $month->month)
                    ->whereYear('completed_at', $month->year)
                    ->count(),
            ];
        }

        // ── Chart 2: Top 5 Experience Paling Menghasilkan ──
        $topExperiences = Booking::where('host_id', $host->id)
            ->where('status', 'completed')
            ->selectRaw('experience_title_snapshot, SUM(host_earning) as total_earning, COUNT(*) as total_bookings')
            ->groupBy('experience_title_snapshot')
            ->orderByDesc('total_earning')
            ->take(5)
            ->get();

        // ── Tab Payout History (dengan filter lengkap) ──
        $payoutHistory = null;
        if ($tab === 'payouts') {
            $query = Booking::with(['experience', 'user'])
                ->where('host_id', $host->id)
                ->where('status', 'completed');

            // Filter rentang tanggal
            $query = $this->applyDateRangeFilter($query, $dateRange, $startDate, $endDate);

            // Filter status disbursement
            if ($statusFilter !== 'all') {
                $query->where('disbursement_status', $statusFilter);
            }

            // Search (kode booking, nama tamu, atau nama experience)
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_booking', 'like', "%{$search}%")
                        ->orWhere('experience_title_snapshot', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $payoutHistory = $query->orderBy('completed_at', 'desc')->paginate(10)->withQueryString();
        }

        // ── Riwayat singkat untuk tab Overview (5 terbaru saja) ──
        $recentCompletedBookings = Booking::with(['experience', 'user'])
            ->where('host_id', $host->id)
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->take(5)
            ->get();

        return view('host.earnings', compact(
            'host',
            'tab',
            'totalEarnings',
            'totalCompletedBookings',
            'averagePerBooking',
            'pendingDisbursement',
            'thisMonthEarnings',
            'lastMonthEarnings',
            'monthOverMonthChange',
            'monthlyEarnings',
            'topExperiences',
            'payoutHistory',
            'recentCompletedBookings',
            'dateRange',
            'startDate',
            'endDate',
            'statusFilter',
            'search'
        ));
    }
    public function exportPayouts(Request $request)
    {
        $host = $this->getHost();
        if (!$host)
            return redirect()->route('home');

        $dateRange = $request->input('date_range', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $statusFilter = $request->input('status', 'all');
        $search = $request->input('search');

        $query = Booking::with(['user'])
            ->where('host_id', $host->id)
            ->where('status', 'completed');

        $query = $this->applyDateRangeFilter($query, $dateRange, $startDate, $endDate);

        if ($statusFilter !== 'all') {
            $query->where('disbursement_status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                    ->orWhere('experience_title_snapshot', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->orderBy('completed_at', 'desc')->get();

        $filename = 'payout-history-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');

            // Header kolom
            fputcsv($file, [
                'Kode Booking',
                'Experience',
                'Nama Tamu',
                'Tanggal Selesai',
                'Nominal',
                'Status Disbursement',
                'Tanggal Dicairkan',
            ]);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->kode_booking,
                    $booking->experience_title_snapshot,
                    $booking->user->name ?? '-',
                    $booking->completed_at?->format('Y-m-d H:i') ?? '-',
                    $booking->host_earning,
                    $booking->disbursement_status,
                    $booking->disbursed_at?->format('Y-m-d H:i') ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    private function applyDateRangeFilter($query, string $dateRange, ?string $startDate, ?string $endDate)
    {
        return match ($dateRange) {
            '7d' => $query->where('completed_at', '>=', now()->subDays(7)),
            '30d' => $query->where('completed_at', '>=', now()->subDays(30)),
            'this_month' => $query->whereMonth('completed_at', now()->month)->whereYear('completed_at', now()->year),
            'this_year' => $query->whereYear('completed_at', now()->year),
            'custom' => $startDate && $endDate
            ? $query->whereBetween('completed_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            : $query,
            default => $query, // 'all'
        };
    }
    // ── Settings ──────────────────────────────────────────────────────────

    public function settings()
    {
        $host = $this->getHost();
        if (!$host)
            return redirect()->route('home');

        return view('host.settings', compact('host'));
    }

    public function updateSettings(Request $request)
    {
        $host = $this->getHost();

        $request->validate([
            'bio' => 'nullable|string|max:500',
            'village' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
        ]);

        $host->update($request->only([
            'bio',
            'village',
            'bank_name',
            'bank_account_name',
            'bank_account_number',
        ]));

        Auth::user()->update([
            'name' => $request->input('name', Auth::user()->name),
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // ── Re-submit KTP ─────────────────────────────────────────────────────

    public function resubmitKtp(Request $request)
    {
        $host = $this->getHost();
        if (!$host)
            return redirect()->route('home');

        // Hanya boleh jika status KTP rejected
        if ($host->ktp_status !== 'rejected') {
            return back()->with('error', 'KTP Anda tidak perlu diajukan ulang saat ini.');
        }

        $request->validate([
            'ktp_photo' => ['required', 'image', 'max:5120'],
            'ktp_selfie' => ['required', 'image', 'max:5120'],
        ], [
            'ktp_photo.required' => 'Foto KTP wajib diupload.',
            'ktp_selfie.required' => 'Foto selfie dengan KTP wajib diupload.',
            'ktp_photo.image' => 'File harus berupa gambar.',
            'ktp_selfie.image' => 'File harus berupa gambar.',
            'ktp_photo.max' => 'Ukuran foto KTP maksimal 5MB.',
            'ktp_selfie.max' => 'Ukuran foto selfie maksimal 5MB.',
        ]);

        // Hapus file lama
        if ($host->ktp_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($host->ktp_path);
        }
        if ($host->ktp_selfie_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($host->ktp_selfie_path);
        }

        // Simpan file baru
        $host->ktp_path = $request->file('ktp_photo')->store('ktp', 'public');
        $host->ktp_selfie_path = $request->file('ktp_selfie')->store('ktp', 'public');
        $host->ktp_status = 'pending'; // reset ke pending untuk review ulang
        $host->ktp_rejection_note = null;
        $host->save();

        return back()->with('success', 'KTP berhasil diajukan ulang! Admin akan segera meninjau dokumen Anda.');
    }

    // ── Re-submit Bank ────────────────────────────────────────────────────

    public function resubmitBank(Request $request)
    {
        $host = $this->getHost();
        if (!$host)
            return redirect()->route('home');

        // Hanya boleh jika status rekening not_verified (ditolak)
        if ($host->bank_review_status !== 'not_verified') {
            return back()->with('error', 'Rekening Anda tidak perlu diajukan ulang saat ini.');
        }

        $request->validate([
            'bank_name' => ['required', 'string', 'max:100'],
            'bank_account_name' => ['required', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'max:50'],
        ], [
            'bank_name.required' => 'Nama bank wajib diisi.',
            'bank_account_name.required' => 'Nama pemilik rekening wajib diisi.',
            'bank_account_number.required' => 'Nomor rekening wajib diisi.',
        ]);

        $host->bank_name = $request->input('bank_name');
        $host->bank_account_name = $request->input('bank_account_name');
        $host->bank_account_number = $request->input('bank_account_number');
        $host->bank_review_status = 'needs_review'; // reset ke antrian review
        $host->bank_review_note = 'Diajukan ulang oleh host. Menunggu verifikasi manual admin.';
        $host->bank_reviewed_by = null;
        $host->bank_reviewed_at = null;
        $host->save();

        return back()->with('success', 'Data rekening berhasil diajukan ulang! Admin akan segera meninjau.');
    }
}