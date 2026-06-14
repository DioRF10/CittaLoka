@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@php $locale = app()->getLocale(); @endphp

{{-- Welcome --}}
<div style="margin-bottom:1.75rem;">
    <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.6rem; font-weight:500; color:#1E3A2F; margin-bottom:0.25rem;">
        Welcome back, {{ Auth::user()->name }} 👋
    </h2>
    <p style="font-size:0.85rem; color:#7A7A6E;">Here's what's happening with your experiences today.</p>
</div>

{{-- Stats Grid --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.75rem;">
    @php
        $statCards = [
            ['label' => 'Total Earnings', 'value' => 'Rp ' . number_format($totalEarnings, 0, ',', '.'), 'sub' => 'All time', 'color' => '#1E3A2F', 'icon' => '💰'],
            ['label' => 'This Month', 'value' => 'Rp ' . number_format($earningsThisMonth, 0, ',', '.'), 'sub' => now()->format('F Y'), 'color' => '#2D5240', 'icon' => '📈'],
            ['label' => 'Total Bookings', 'value' => $totalBookings, 'sub' => $confirmedBookings . ' confirmed', 'color' => '#C4783A', 'icon' => '📅'],
            ['label' => 'Active Listings', 'value' => $activeExperiences, 'sub' => $totalExperiences . ' total', 'color' => '#1E3A2F', 'icon' => '🌿'],
        ];
    @endphp
    @foreach($statCards as $card)
        <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; padding:1.25rem;">
            <div style="font-size:1.5rem; margin-bottom:0.5rem;">{{ $card['icon'] }}</div>
            <div style="font-size:0.72rem; font-weight:600; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">{{ $card['label'] }}</div>
            <div style="font-size:1.4rem; font-weight:700; color:{{ $card['color'] }}; margin-bottom:0.2rem; font-family:'DM Sans',sans-serif;">{{ $card['value'] }}</div>
            <div style="font-size:0.75rem; color:#9CA3AF;">{{ $card['sub'] }}</div>
        </div>
    @endforeach
</div>

<div style="display:grid; grid-template-columns:1fr 340px; gap:1.25rem;">

    {{-- Recent Bookings --}}
    <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; overflow:hidden;">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #EDE7DC; display:flex; align-items:center; justify-content:space-between;">
            <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.1rem; font-weight:500; color:#1E3A2F;">Recent Bookings</h3>
            <a href="{{ route('host.bookings.index') }}" style="font-size:0.8rem; color:#1E3A2F; text-decoration:underline;">See all</a>
        </div>
        @if($recentBookings->isEmpty())
            <div style="padding:2rem; text-align:center; color:#9CA3AF; font-size:0.875rem;">No bookings yet</div>
        @else
            @foreach($recentBookings as $booking)
                @php
                    $statusColor = match($booking->status) {
                        'confirmed' => '#2D5240', 'completed' => '#1E3A2F',
                        'pending_payment' => '#C4783A', default => '#C0392B',
                    };
                    $statusBg = match($booking->status) {
                        'confirmed' => '#EBF5EE', 'completed' => '#E8E4DC',
                        'pending_payment' => '#FDF6EE', default => '#FEF2F2',
                    };
                @endphp
                <div style="padding:1rem 1.5rem; border-bottom:1px solid #F7F3ED; display:flex; align-items:center; justify-content:space-between;">
                    <div>
                        <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F; margin-bottom:0.2rem;">{{ $booking->user->name }}</div>
                        <div style="font-size:0.78rem; color:#7A7A6E;">{{ Str::limit($booking->experience_title_snapshot, 35) }}</div>
                        <div style="font-size:0.72rem; color:#9CA3AF; margin-top:0.15rem;">
                            {{ \Carbon\Carbon::parse($booking->tanggal_experience)->format('d M Y') }}
                            · {{ $booking->jumlah_peserta }} guests
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <span style="font-size:0.68rem; font-weight:700; letter-spacing:0.06em; padding:0.2rem 0.6rem; border-radius:999px; background:{{ $statusBg }}; color:{{ $statusColor }}; display:block; margin-bottom:0.4rem;">
                            {{ strtoupper($booking->status) }}
                        </span>
                        <div style="font-size:0.82rem; font-weight:600; color:#1E3A2F;">
                            Rp {{ number_format($booking->host_earning, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Upcoming + Experience Stats --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        {{-- Upcoming --}}
        <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; overflow:hidden;">
            <div style="padding:1rem 1.25rem; border-bottom:1px solid #EDE7DC;">
                <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.1rem; font-weight:500; color:#1E3A2F;">Upcoming Sessions</h3>
            </div>
            @if($upcomingBookings->isEmpty())
                <div style="padding:1.5rem; text-align:center; color:#9CA3AF; font-size:0.82rem;">No upcoming sessions</div>
            @else
                @foreach($upcomingBookings as $booking)
                    <div style="padding:0.875rem 1.25rem; border-bottom:1px solid #F7F3ED;">
                        <div style="font-size:0.82rem; font-weight:500; color:#1E3A2F; margin-bottom:0.2rem;">
                            {{ Str::limit($booking->experience_title_snapshot, 30) }}
                        </div>
                        <div style="font-size:0.75rem; color:#7A7A6E;">
                            📅 {{ \Carbon\Carbon::parse($booking->tanggal_experience)->format('d M') }}
                            {{ $booking->jam_experience ? '· ' . \Carbon\Carbon::parse($booking->jam_experience)->format('H:i') : '' }}
                            · {{ $booking->jumlah_peserta }} guests
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Experience Stats --}}
        <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; padding:1.25rem;">
            <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.1rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">Experiences</h3>
            @php
                $expStats = [
                    ['label' => 'Active',   'value' => $activeExperiences,  'color' => '#2D5240',  'bg' => '#EBF5EE'],
                    ['label' => 'Draft',    'value' => $draftExperiences,   'color' => '#7A7A6E',  'bg' => '#F3F4F6'],
                    ['label' => 'Pending',  'value' => $pendingExperiences, 'color' => '#C4783A',  'bg' => '#FDF6EE'],
                ];
            @endphp
            @foreach($expStats as $stat)
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.6rem;">
                    <span style="font-size:0.82rem; color:#4A4A4A;">{{ $stat['label'] }}</span>
                    <span style="font-size:0.8rem; font-weight:600; padding:0.15rem 0.6rem; border-radius:999px; background:{{ $stat['bg'] }}; color:{{ $stat['color'] }};">
                        {{ $stat['value'] }}
                    </span>
                </div>
            @endforeach
            <a href="{{ route('host.experiences.index') }}"
                style="display:block; margin-top:0.75rem; text-align:center; padding:0.6rem; background:#F7F3ED; border-radius:8px; font-size:0.8rem; font-weight:500; color:#1E3A2F; text-decoration:none;">
                Manage Experiences →
            </a>
        </div>

    </div>
</div>

@endsection
