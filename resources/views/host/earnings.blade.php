@extends('layouts.dashboard')

@section('title', 'Earnings')
@section('page-title', 'Earnings')

@section('content')

@php use Illuminate\Support\Str; @endphp

<div style="display:flex; flex-direction:column; gap:1.5rem;">

    {{-- ── Tabs ── --}}
    <div style="display:flex; gap:0.5rem; border-bottom:1.5px solid #EFE7DC;">
        <a href="{{ route('host.earnings', ['tab' => 'overview']) }}"
            style="padding:0.75rem 1.25rem; font-size:0.875rem; font-weight:600; text-decoration:none; border-bottom:2.5px solid {{ $tab === 'overview' ? '#1E3A2F' : 'transparent' }}; color:{{ $tab === 'overview' ? '#1E3A2F' : '#9CA3AF' }}; margin-bottom:-1.5px;">
            Overview
        </a>
        <a href="{{ route('host.earnings', ['tab' => 'payouts']) }}"
            style="padding:0.75rem 1.25rem; font-size:0.875rem; font-weight:600; text-decoration:none; border-bottom:2.5px solid {{ $tab === 'payouts' ? '#1E3A2F' : 'transparent' }}; color:{{ $tab === 'payouts' ? '#1E3A2F' : '#9CA3AF' }}; margin-bottom:-1.5px;">
            Payout History
        </a>
    </div>

    @if($tab === 'overview')

        {{-- ── Summary Cards ── --}}
        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:1rem;">

            {{-- Total Pendapatan --}}
            <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; padding:1.25rem;">
                <div style="font-size:0.72rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Total Pendapatan</div>
                <div style="font-size:1.4rem; font-weight:700; color:#1E3A2F;">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; color:#9CA3AF; margin-top:0.3rem;">{{ $totalCompletedBookings }} booking selesai</div>
            </div>

            {{-- Bulan Ini vs Bulan Lalu --}}
            <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; padding:1.25rem;">
                <div style="font-size:0.72rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Bulan Ini</div>
                <div style="font-size:1.4rem; font-weight:700; color:#1E3A2F;">Rp {{ number_format($thisMonthEarnings, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; margin-top:0.3rem; color:{{ $monthOverMonthChange >= 0 ? '#2D5240' : '#C0392B' }};">
                    {{ $monthOverMonthChange >= 0 ? '↑' : '↓' }} {{ abs($monthOverMonthChange) }}% vs bulan lalu
                </div>
            </div>

            {{-- Rata-rata per Booking --}}
            <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; padding:1.25rem;">
                <div style="font-size:0.72rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Rata-rata per Booking</div>
                <div style="font-size:1.4rem; font-weight:700; color:#1E3A2F;">Rp {{ number_format($averagePerBooking, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; color:#9CA3AF; margin-top:0.3rem;">per booking selesai</div>
            </div>

            {{-- Pending Disbursement --}}
            <div style="background:#FDF6EE; border:1.5px solid #F0DFC0; border-radius:14px; padding:1.25rem;">
                <div style="font-size:0.72rem; font-weight:700; color:#C4783A; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Menunggu Pencairan</div>
                <div style="font-size:1.4rem; font-weight:700; color:#C4783A;">Rp {{ number_format($pendingDisbursement, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; color:#A87C4F; margin-top:0.3rem;">akan cair otomatis</div>
            </div>

        </div>

        {{-- ── Chart: Trend Pendapatan ── --}}
        <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; padding:1.5rem;">
            <div style="font-size:0.95rem; font-weight:700; color:#1E3A2F; margin-bottom:1rem;">Trend Pendapatan (12 Bulan Terakhir)</div>
            <div style="position:relative; height:280px;">
                <canvas id="earningsTrendChart"></canvas>
            </div>
        </div>

        {{-- ── Chart: Top Experience ── --}}
        <div style="display:grid; grid-template-columns:1.3fr 1fr; gap:1.5rem;">

            <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; padding:1.5rem;">
                <div style="font-size:0.95rem; font-weight:700; color:#1E3A2F; margin-bottom:1rem;">Top 5 Experience Paling Menghasilkan</div>
                @if($topExperiences->isEmpty())
                    <div style="text-align:center; padding:2rem; color:#9CA3AF; font-size:0.85rem;">Belum ada data.</div>
                @else
                    <div style="position:relative; height:240px;">
                        <canvas id="topExperienceChart"></canvas>
                    </div>
                @endif
            </div>

            {{-- Riwayat Terbaru --}}
            <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; overflow:hidden;">
                <div style="padding:1.25rem 1.5rem 0.75rem; font-size:0.95rem; font-weight:700; color:#1E3A2F;">Booking Selesai Terbaru</div>
                @forelse($recentCompletedBookings as $booking)
                    <div style="padding:0.85rem 1.5rem; border-top:1px solid #F7F3ED; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <div style="font-size:0.82rem; font-weight:600; color:#1E3A2F;">{{ Str::limit($booking->experience_title_snapshot, 24) }}</div>
                            <div style="font-size:0.74rem; color:#9CA3AF;">{{ $booking->completed_at?->format('d M Y') }}</div>
                        </div>
                        <div style="font-size:0.85rem; font-weight:700; color:#2D5240;">+Rp {{ number_format($booking->host_earning, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <div style="padding:2rem 1.5rem; text-align:center; color:#9CA3AF; font-size:0.85rem;">Belum ada booking selesai.</div>
                @endforelse
            </div>

        </div>

    @else

        {{-- ── Tab: Payout History ── --}}
        <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; overflow:hidden;">

            <div style="padding:1.25rem; border-bottom:1px solid #EFE7DC;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                    <div style="font-size:0.95rem; font-weight:700; color:#1E3A2F;">Riwayat Pencairan Dana</div>
                    <a href="{{ route('host.earnings.export', request()->query()) }}"
                        style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.5rem 1rem; background:#1E3A2F; color:white; border-radius:8px; font-size:0.8rem; font-weight:600; text-decoration:none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export CSV
                    </a>
                </div>

                {{-- Filter Bar --}}
                <form method="GET" action="{{ route('host.earnings') }}" style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:end;">
                    <input type="hidden" name="tab" value="payouts">

                    {{-- Search --}}
                    <div style="flex:1; min-width:200px;">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode booking, tamu, atau experience..."
                            style="width:100%; padding:0.55rem 0.85rem; border:1.5px solid #E8E0D3; border-radius:8px; font-size:0.82rem; box-sizing:border-box;">
                    </div>

                    {{-- Date Range --}}
                    <div>
                        <select name="date_range" onchange="document.getElementById('customDateFields').style.display = this.value === 'custom' ? 'flex' : 'none';"
                            style="padding:0.55rem 0.85rem; border:1.5px solid #E8E0D3; border-radius:8px; font-size:0.82rem; background:white;">
                            <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                            <option value="7d" {{ $dateRange === '7d' ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="30d" {{ $dateRange === '30d' ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="this_month" {{ $dateRange === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="this_year" {{ $dateRange === 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                            <option value="custom" {{ $dateRange === 'custom' ? 'selected' : '' }}>Custom...</option>
                        </select>
                    </div>

                    {{-- Custom Date Fields --}}
                    <div id="customDateFields" style="display:{{ $dateRange === 'custom' ? 'flex' : 'none' }}; gap:0.5rem;">
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            style="padding:0.55rem 0.65rem; border:1.5px solid #E8E0D3; border-radius:8px; font-size:0.82rem;">
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            style="padding:0.55rem 0.65rem; border:1.5px solid #E8E0D3; border-radius:8px; font-size:0.82rem;">
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <select name="status" style="padding:0.55rem 0.85rem; border:1.5px solid #E8E0D3; border-radius:8px; font-size:0.82rem; background:white;">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="processing" {{ $statusFilter === 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="success" {{ $statusFilter === 'success' ? 'selected' : '' }}>Selesai</option>
                            <option value="failed" {{ $statusFilter === 'failed' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </div>

                    <button type="submit" style="padding:0.55rem 1.25rem; background:#1E3A2F; color:white; border:none; border-radius:8px; font-size:0.82rem; font-weight:600; cursor:pointer;">
                        Terapkan
                    </button>

                    @if($dateRange !== 'all' || $statusFilter !== 'all' || $search)
                        <a href="{{ route('host.earnings', ['tab' => 'payouts']) }}"
                            style="padding:0.55rem 1rem; color:#9CA3AF; font-size:0.8rem; text-decoration:underline;">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            @if($payoutHistory->isEmpty())
                <div style="padding:3rem; text-align:center;">
                    <div style="font-size:2.5rem; margin-bottom:0.75rem;">💰</div>
                    @if($dateRange !== 'all' || $statusFilter !== 'all' || $search)
                        <div style="font-size:0.875rem; color:#9CA3AF; font-weight:600;">Tidak ada hasil yang cocok dengan filter ini</div>
                        <div style="font-size:0.8rem; color:#9CA3AF; margin-top:0.3rem;">Coba ubah atau reset filter di atas.</div>
                    @else
                        <div style="font-size:0.875rem; color:#9CA3AF; font-weight:600;">Belum ada riwayat pencairan</div>
                    @endif
                </div>
            @else
                <div style="padding:0.75rem 1.25rem; font-size:0.78rem; color:#7A7A6E; border-bottom:1px solid #F7F3ED;">
                    Menampilkan {{ $payoutHistory->total() }} hasil
                    @if($dateRange !== 'all' || $statusFilter !== 'all' || $search)
                        <span style="color:#C4783A;">(filter aktif)</span>
                    @endif
                </div>
                <div style="display:grid; grid-template-columns:1.5fr 1fr 1fr 1fr 120px; gap:0; padding:0.6rem 1.25rem; background:#F7F3ED; border-bottom:1px solid #EDE7DC;">
                    @foreach(['Experience & Tamu', 'Selesai Pada', 'Nominal', 'Status', 'Dicairkan'] as $col)
                        <div style="font-size:0.7rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.06em;">{{ $col }}</div>
                    @endforeach
                </div>

                @foreach($payoutHistory as $booking)
                    @php
                        $statusColor = match($booking->disbursement_status) {
                            'success'    => '#2D5240',
                            'processing' => '#C4783A',
                            'failed'     => '#C0392B',
                            default      => '#7A7A6E',
                        };
                        $statusBg = match($booking->disbursement_status) {
                            'success'    => '#EBF5EE',
                            'processing' => '#FDF6EE',
                            'failed'     => '#FEF2F2',
                            default      => '#F3F4F6',
                        };
                        $statusLabel = match($booking->disbursement_status) {
                            'success'    => 'Selesai',
                            'processing' => 'Diproses',
                            'failed'     => 'Gagal',
                            default      => 'Menunggu',
                        };
                    @endphp
                    <div style="display:grid; grid-template-columns:1.5fr 1fr 1fr 1fr 120px; gap:0; padding:1rem 1.25rem; border-bottom:1px solid #F7F3ED; align-items:center;">
                        <div>
                            <div style="font-size:0.85rem; font-weight:600; color:#1E3A2F;">{{ Str::limit($booking->experience_title_snapshot, 30) }}</div>
                            <div style="font-size:0.76rem; color:#9CA3AF;">{{ $booking->user->name ?? '-' }}</div>
                        </div>
                        <div style="font-size:0.8rem; color:#4A4A4A;">{{ $booking->completed_at?->format('d M Y') }}</div>
                        <div style="font-size:0.85rem; font-weight:700; color:#1E3A2F;">Rp {{ number_format($booking->host_earning, 0, ',', '.') }}</div>
                        <div>
                            <span style="font-size:0.68rem; font-weight:700; letter-spacing:0.04em; padding:0.25rem 0.65rem; border-radius:999px; background:{{ $statusBg }}; color:{{ $statusColor }};">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div style="font-size:0.76rem; color:#9CA3AF;">
                            {{ $booking->disbursed_at?->format('d M Y') ?? '-' }}
                        </div>
                    </div>
                @endforeach

                {{-- Pagination --}}
                @if($payoutHistory->hasPages())
                    <div style="padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; border-top:1px solid #EDE7DC;">
                        <div style="font-size:0.8rem; color:#7A7A6E;">
                            Showing {{ $payoutHistory->firstItem() }}–{{ $payoutHistory->lastItem() }} of {{ $payoutHistory->total() }}
                        </div>
                        <div style="display:flex; gap:0.4rem;">
                            @if($payoutHistory->onFirstPage())
                                <span style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#D1D5DB; font-size:0.8rem;">‹</span>
                            @else
                                <a href="{{ $payoutHistory->previousPageUrl() }}" style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#1E3A2F; font-size:0.8rem; text-decoration:none;">‹</a>
                            @endif
                            <span style="width:32px; height:32px; border-radius:6px; background:#1E3A2F; color:white; display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:600;">{{ $payoutHistory->currentPage() }}</span>
                            @if($payoutHistory->hasMorePages())
                                <a href="{{ $payoutHistory->nextPageUrl() }}" style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#1E3A2F; font-size:0.8rem; text-decoration:none;">›</a>
                            @else
                                <span style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#D1D5DB; font-size:0.8rem;">›</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>

    @endif

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    @if($tab === 'overview')

    // ── Chart 1: Trend Pendapatan ──
    const trendCtx = document.getElementById('earningsTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyEarnings, 'label')) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode(array_column($monthlyEarnings, 'earnings')) !!},
                    borderColor: '#1E3A2F',
                    backgroundColor: 'rgba(30, 58, 47, 0.08)',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#C4783A',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E3A2F',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F0EDE6' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                return 'Rp ' + value;
                            },
                            color: '#9CA3AF',
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9CA3AF', font: { size: 11 } }
                    }
                }
            }
        });
    }

    // ── Chart 2: Top Experience ──
    const topExpCtx = document.getElementById('topExperienceChart');
    if (topExpCtx) {
        new Chart(topExpCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topExperiences->pluck('experience_title_snapshot')->map(fn($t) => \Illuminate\Support\Str::limit($t, 20))) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($topExperiences->pluck('total_earning')) !!},
                    backgroundColor: '#C4783A',
                    borderRadius: 6,
                    maxBarThickness: 36,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E3A2F',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.x.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#F0EDE6' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return (value / 1000) + 'rb';
                                return value;
                            },
                            color: '#9CA3AF', font: { size: 11 }
                        }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#4A4A4A', font: { size: 11 } }
                    }
                }
            }
        });
    }

    @endif

});
</script>
@endpush

@endsection