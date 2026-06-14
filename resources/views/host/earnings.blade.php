@extends('layouts.dashboard')

@section('title', 'Earnings')
@section('page-title', 'Earnings')

@section('content')

{{-- Stat Cards --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.75rem;">
    @php
        $earningCards = [
            ['label' => 'Total Earnings', 'value' => 'Rp ' . number_format($totalEarnings, 0, ',', '.'), 'sub' => 'All time', 'icon' => '💰'],
            ['label' => 'This Month', 'value' => 'Rp ' . number_format($thisMonthEarnings, 0, ',', '.'), 'sub' => now()->format('F Y'), 'icon' => '📈'],
            ['label' => 'Last Month', 'value' => 'Rp ' . number_format($lastMonthEarnings, 0, ',', '.'), 'sub' => now()->subMonth()->format('F Y'), 'icon' => '📊'],
        ];
    @endphp
    @foreach($earningCards as $card)
        <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; padding:1.5rem;">
            <div style="font-size:1.5rem; margin-bottom:0.5rem;">{{ $card['icon'] }}</div>
            <div style="font-size:0.7rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.4rem;">{{ $card['label'] }}</div>
            <div style="font-size:1.5rem; font-weight:700; color:#1E3A2F; font-family:'DM Sans',sans-serif; margin-bottom:0.2rem;">{{ $card['value'] }}</div>
            <div style="font-size:0.75rem; color:#9CA3AF;">{{ $card['sub'] }}</div>
        </div>
    @endforeach
</div>

{{-- Monthly Chart (simple bar) --}}
<div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; padding:1.5rem; margin-bottom:1.25rem;">
    <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.1rem; font-weight:500; color:#1E3A2F; margin-bottom:1.25rem;">
        Earnings — Last 6 Months
    </h3>
    @php
        $maxEarning = max(array_column($monthlyEarnings, 'earnings')) ?: 1;
    @endphp
    <div style="display:flex; align-items:flex-end; gap:0.75rem; height:120px;">
        @foreach($monthlyEarnings as $month)
            @php $height = max(4, ($month['earnings'] / $maxEarning) * 100); @endphp
            <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:0.4rem;">
                <div style="font-size:0.68rem; color:#7A7A6E;">
                    Rp {{ number_format($month['earnings'] / 1000, 0) }}k
                </div>
                <div style="width:100%; background:#1E3A2F; border-radius:4px 4px 0 0; height:{{ $height }}px; min-height:4px; transition:height 0.3s;"></div>
                <div style="font-size:0.65rem; color:#9CA3AF; white-space:nowrap;">{{ substr($month['label'], 0, 6) }}</div>
            </div>
        @endforeach
    </div>
</div>

{{-- Transaction History --}}
<div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; overflow:hidden;">
    <div style="padding:1rem 1.5rem; border-bottom:1px solid #EDE7DC;">
        <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.1rem; font-weight:500; color:#1E3A2F;">Transaction History</h3>
    </div>

    {{-- Header --}}
    <div style="display:grid; grid-template-columns:1fr 1fr 120px 100px; padding:0.75rem 1.5rem; background:#F7F3ED; border-bottom:1px solid #EDE7DC;">
        @foreach(['Guest','Experience','Date','Earnings'] as $col)
            <div style="font-size:0.7rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.06em;">{{ $col }}</div>
        @endforeach
    </div>

    @if($completedBookings->isEmpty())
        <div style="padding:2rem; text-align:center; color:#9CA3AF; font-size:0.875rem;">No completed bookings yet</div>
    @else
        @foreach($completedBookings as $booking)
            <div style="display:grid; grid-template-columns:1fr 1fr 120px 100px; padding:1rem 1.5rem; border-bottom:1px solid #F7F3ED; align-items:center;"
                onmouseover="this.style.background='#FAFAF8'"
                onmouseout="this.style.background='white'">
                <div style="font-size:0.875rem; color:#1E3A2F;">{{ $booking->user->name }}</div>
                <div style="font-size:0.82rem; color:#4A4A4A;">{{ Str::limit($booking->experience_title_snapshot, 30) }}</div>
                <div style="font-size:0.82rem; color:#7A7A6E;">{{ \Carbon\Carbon::parse($booking->tanggal_experience)->format('d M Y') }}</div>
                <div style="font-size:0.875rem; font-weight:600; color:#2D5240;">Rp {{ number_format($booking->host_earning, 0, ',', '.') }}</div>
            </div>
        @endforeach

        @if($completedBookings->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid #EDE7DC;">
                {{ $completedBookings->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
