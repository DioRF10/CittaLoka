@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Host Overview')

@push('styles')
<style>
    .host-dashboard {
        display: flex;
        flex-direction: column;
        gap: 1.4rem;
        color: #24352C;
    }

    .dashboard-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(30, 58, 47, 0.12);
        border-radius: 18px;
        background:
            linear-gradient(135deg, rgba(30, 58, 47, 0.96), rgba(42, 80, 63, 0.9)),
            url('https://images.unsplash.com/photo-1537953773345-d172ccf13cf1?auto=format&fit=crop&w=1500&q=80');
        background-size: cover;
        background-position: center;
        padding: 1.5rem;
        color: #fff;
        min-height: 220px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 1.5rem;
        align-items: end;
        box-shadow: 0 24px 60px rgba(30, 58, 47, 0.16);
    }

    .dashboard-hero::after {
        content: "";
        position: absolute;
        inset: auto 0 0 0;
        height: 45%;
        background: linear-gradient(to top, rgba(15, 26, 20, 0.45), transparent);
        pointer-events: none;
    }

    .hero-copy,
    .hero-actions {
        position: relative;
        z-index: 1;
    }

    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.35rem 0.65rem;
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .hero-title {
        max-width: 680px;
        margin-top: 1rem;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2rem, 4vw, 3.6rem);
        line-height: 0.95;
        font-weight: 600;
        letter-spacing: 0;
    }

    .hero-subtitle {
        max-width: 560px;
        margin-top: 0.85rem;
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.95rem;
        line-height: 1.65;
    }

    .hero-actions {
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
        align-items: stretch;
        min-width: 190px;
    }

    .dashboard-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 42px;
        border-radius: 10px;
        padding: 0.7rem 1rem;
        border: 1px solid transparent;
        font-size: 0.84rem;
        font-weight: 700;
        text-decoration: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }

    .dashboard-button:hover {
        transform: translateY(-1px);
    }

    .dashboard-button.primary {
        background: #fff;
        color: #1E3A2F;
        box-shadow: 0 12px 24px rgba(10, 18, 14, 0.2);
    }

    .dashboard-button.secondary {
        border-color: rgba(255, 255, 255, 0.25);
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }

    .metric-card {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid #E8E0D3;
        border-radius: 14px;
        padding: 1.1rem;
        box-shadow: 0 14px 34px rgba(40, 50, 42, 0.06);
    }

    .metric-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .metric-label {
        color: #7A7A6E;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .metric-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        color: var(--metric-color);
        background: var(--metric-bg);
    }

    .metric-value {
        color: #182A21;
        font-size: 1.35rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .metric-sub {
        margin-top: 0.35rem;
        color: #8A877D;
        font-size: 0.78rem;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 1.15rem;
        align-items: start;
    }

    .dashboard-panel {
        overflow: hidden;
        border: 1px solid #E8E0D3;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 14px 34px rgba(40, 50, 42, 0.06);
    }

    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid #EFE7DC;
    }

    .panel-title {
        font-size: 0.96rem;
        font-weight: 800;
        color: #1E3A2F;
    }

    .panel-link {
        color: #946032;
        font-size: 0.78rem;
        font-weight: 800;
        text-decoration: none;
    }

    .booking-list {
        display: flex;
        flex-direction: column;
    }

    .booking-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #F2ECE3;
    }

    .booking-row:last-child {
        border-bottom: 0;
    }

    .booking-person {
        display: flex;
        gap: 0.8rem;
        min-width: 0;
    }

    .booking-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #F3E8D8;
        color: #1E3A2F;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        font-weight: 800;
    }

    .booking-name {
        color: #1E3A2F;
        font-size: 0.9rem;
        font-weight: 800;
        margin-bottom: 0.2rem;
    }

    .booking-title {
        color: #5D625A;
        font-size: 0.8rem;
        line-height: 1.45;
    }

    .booking-meta {
        margin-top: 0.35rem;
        color: #9A968C;
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        font-size: 0.74rem;
    }

    .booking-side {
        text-align: right;
        white-space: nowrap;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.28rem 0.62rem;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .booking-price {
        margin-top: 0.5rem;
        color: #1E3A2F;
        font-size: 0.86rem;
        font-weight: 800;
    }

    .empty-state {
        padding: 2.3rem 1.25rem;
        text-align: center;
        color: #8A877D;
        font-size: 0.9rem;
    }

    .side-stack {
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
    }

    .session-list {
        padding: 0.35rem 0;
    }

    .session-item {
        display: grid;
        grid-template-columns: 52px minmax(0, 1fr);
        gap: 0.85rem;
        padding: 0.9rem 1.15rem;
        border-bottom: 1px solid #F2ECE3;
    }

    .session-item:last-child {
        border-bottom: 0;
    }

    .date-chip {
        border-radius: 12px;
        background: #F7F1E8;
        border: 1px solid #EFE4D4;
        min-height: 52px;
        display: grid;
        place-items: center;
        text-align: center;
    }

    .date-chip strong {
        display: block;
        color: #1E3A2F;
        font-size: 1rem;
        line-height: 1;
    }

    .date-chip span {
        display: block;
        margin-top: 0.25rem;
        color: #9A6A3C;
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .session-title {
        color: #1E3A2F;
        font-size: 0.85rem;
        font-weight: 800;
        line-height: 1.35;
    }

    .session-meta {
        margin-top: 0.35rem;
        color: #817D73;
        font-size: 0.75rem;
        line-height: 1.5;
    }

    .experience-summary {
        padding: 1.1rem 1.15rem 1.2rem;
    }

    .progress-track {
        height: 10px;
        overflow: hidden;
        border-radius: 999px;
        background: #EFE7DC;
        display: flex;
    }

    .progress-segment.active {
        background: #1E3A2F;
    }

    .progress-segment.pending {
        background: #C4783A;
    }

    .progress-segment.draft {
        background: #B8B1A6;
    }

    .experience-stat-list {
        display: grid;
        gap: 0.65rem;
        margin-top: 1rem;
    }

    .experience-stat {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        color: #4A4A4A;
        font-size: 0.82rem;
    }

    .experience-count {
        min-width: 34px;
        border-radius: 999px;
        padding: 0.2rem 0.55rem;
        text-align: center;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .manage-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        margin-top: 1rem;
        min-height: 40px;
        border-radius: 10px;
        background: #1E3A2F;
        color: #fff;
        font-size: 0.82rem;
        font-weight: 800;
        text-decoration: none;
    }

    @media (max-width: 1180px) {
        .metrics-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .dashboard-hero {
            grid-template-columns: 1fr;
            align-items: start;
        }

        .hero-actions {
            min-width: 0;
            flex-direction: row;
            flex-wrap: wrap;
        }

        .dashboard-button {
            flex: 1 1 160px;
        }

        .metrics-grid {
            grid-template-columns: 1fr;
        }

        .booking-row {
            grid-template-columns: 1fr;
        }

        .booking-side {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')

@php
    $hostName = Auth::user()->name;
    $firstName = Str::of($hostName)->explode(' ')->first();
    $listingProgress = $totalExperiences > 0 ? round(($activeExperiences / $totalExperiences) * 100) : 0;

    $statCards = [
        [
            'label' => 'Total Earnings',
            'value' => 'Rp ' . number_format($totalEarnings, 0, ',', '.'),
            'sub' => 'Completed bookings',
            'color' => '#1E3A2F',
            'bg' => '#EAF1EA',
            'icon' => '<path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
        ],
        [
            'label' => 'This Month',
            'value' => 'Rp ' . number_format($earningsThisMonth, 0, ',', '.'),
            'sub' => now()->format('F Y'),
            'color' => '#946032',
            'bg' => '#F8ECDE',
            'icon' => '<path d="M3 3v18h18"/><path d="m7 14 4-4 3 3 5-6"/>',
        ],
        [
            'label' => 'Bookings',
            'value' => $totalBookings,
            'sub' => $confirmedBookings . ' confirmed, ' . $completedBookings . ' completed',
            'color' => '#254B63',
            'bg' => '#E8F0F4',
            'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>',
        ],
        [
            'label' => 'Active Listings',
            'value' => $activeExperiences,
            'sub' => $totalExperiences . ' total experiences',
            'color' => '#6C6B2B',
            'bg' => '#F2F0D9',
            'icon' => '<path d="M11 20A7 7 0 0 1 4 13c0-5 7-10 8-10s8 5 8 10a7 7 0 0 1-7 7"/><path d="M12 20V9"/>',
        ],
    ];

    $expStats = [
        ['label' => 'Active', 'value' => $activeExperiences, 'class' => 'active', 'color' => '#1E3A2F', 'bg' => '#EAF1EA'],
        ['label' => 'Pending review', 'value' => $pendingExperiences, 'class' => 'pending', 'color' => '#946032', 'bg' => '#F8ECDE'],
        ['label' => 'Draft', 'value' => $draftExperiences, 'class' => 'draft', 'color' => '#6F6B62', 'bg' => '#F2F0EC'],
    ];
@endphp

<div class="host-dashboard">
    <section class="dashboard-hero">
        <div class="hero-copy">
            <div class="hero-kicker">
                <span>{{ now()->format('l, d M') }}</span>
                <span>Host workspace</span>
            </div>
            <h1 class="hero-title">Good to see you, {{ $firstName }}.</h1>
            <p class="hero-subtitle">
                Track guest activity, keep your cultural sessions ready, and spot what needs attention before the next booking comes in.
            </p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('host.experiences.index') }}" class="dashboard-button primary">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                Manage Listings
            </a>
            <a href="{{ route('host.availability.index') }}" class="dashboard-button secondary">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Set Availability
            </a>
        </div>
    </section>

    <section class="metrics-grid">
        @foreach($statCards as $card)
            <article class="metric-card">
                <div class="metric-top">
                    <div class="metric-label">{{ $card['label'] }}</div>
                    <div class="metric-icon" style="--metric-color: {{ $card['color'] }}; --metric-bg: {{ $card['bg'] }};">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $card['icon'] !!}</svg>
                    </div>
                </div>
                <div class="metric-value">{{ $card['value'] }}</div>
                <div class="metric-sub">{{ $card['sub'] }}</div>
            </article>
        @endforeach
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Recent Bookings</div>
                </div>
                <a href="{{ route('host.bookings.index') }}" class="panel-link">View all</a>
            </div>

            @if($recentBookings->isEmpty())
                <div class="empty-state">No bookings yet. Your newest guest activity will appear here.</div>
            @else
                <div class="booking-list">
                    @foreach($recentBookings as $booking)
                        @php
                            $statusColor = match($booking->status) {
                                'confirmed' => '#1E3A2F',
                                'completed' => '#254B63',
                                'pending_payment' => '#946032',
                                default => '#A4443F',
                            };
                            $statusBg = match($booking->status) {
                                'confirmed' => '#EAF1EA',
                                'completed' => '#E8F0F4',
                                'pending_payment' => '#F8ECDE',
                                default => '#F7E7E5',
                            };
                            $guestInitial = Str::upper(Str::substr($booking->user?->name ?? 'G', 0, 1));
                        @endphp
                        <article class="booking-row">
                            <div class="booking-person">
                                <div class="booking-avatar">{{ $guestInitial }}</div>
                                <div style="min-width:0;">
                                    <div class="booking-name">{{ $booking->user?->name ?? 'Guest' }}</div>
                                    <div class="booking-title">{{ Str::limit($booking->experience_title_snapshot, 58) }}</div>
                                    <div class="booking-meta">
                                        <span>{{ \Carbon\Carbon::parse($booking->tanggal_experience)->format('d M Y') }}</span>
                                        <span>{{ $booking->jumlah_peserta }} guests</span>
                                        @if($booking->jam_experience)
                                            <span>{{ \Carbon\Carbon::parse($booking->jam_experience)->format('H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="booking-side">
                                <span class="status-pill" style="background: {{ $statusBg }}; color: {{ $statusColor }};">
                                    {{ str_replace('_', ' ', $booking->status) }}
                                </span>
                                <div class="booking-price">Rp {{ number_format($booking->host_earning, 0, ',', '.') }}</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        <aside class="side-stack">
            <div class="dashboard-panel">
                <div class="panel-header">
                    <div class="panel-title">Upcoming Sessions</div>
                    <a href="{{ route('host.bookings.index', ['filter' => 'upcoming']) }}" class="panel-link">Schedule</a>
                </div>
                @if($upcomingBookings->isEmpty())
                    <div class="empty-state">No confirmed sessions in the calendar.</div>
                @else
                    <div class="session-list">
                        @foreach($upcomingBookings as $booking)
                            <article class="session-item">
                                <div class="date-chip">
                                    <div>
                                        <strong>{{ \Carbon\Carbon::parse($booking->tanggal_experience)->format('d') }}</strong>
                                        <span>{{ \Carbon\Carbon::parse($booking->tanggal_experience)->format('M') }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="session-title">{{ Str::limit($booking->experience_title_snapshot, 42) }}</div>
                                    <div class="session-meta">
                                        {{ $booking->jam_experience ? \Carbon\Carbon::parse($booking->jam_experience)->format('H:i') . ' · ' : '' }}
                                        {{ $booking->jumlah_peserta }} guests
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="dashboard-panel">
                <div class="panel-header">
                    <div class="panel-title">Listing Health</div>
                    <span class="panel-link">{{ $listingProgress }}% active</span>
                </div>
                <div class="experience-summary">
                    <div class="progress-track" aria-label="Experience status overview">
                        @foreach($expStats as $stat)
                            @php $width = $totalExperiences > 0 ? max(6, ($stat['value'] / $totalExperiences) * 100) : 0; @endphp
                            @if($stat['value'] > 0)
                                <span class="progress-segment {{ $stat['class'] }}" style="width: {{ $width }}%;"></span>
                            @endif
                        @endforeach
                    </div>

                    <div class="experience-stat-list">
                        @foreach($expStats as $stat)
                            <div class="experience-stat">
                                <span>{{ $stat['label'] }}</span>
                                <span class="experience-count" style="background: {{ $stat['bg'] }}; color: {{ $stat['color'] }};">{{ $stat['value'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('host.experiences.index') }}" class="manage-link">
                        Manage Experiences
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </aside>
    </section>
</div>

@endsection
