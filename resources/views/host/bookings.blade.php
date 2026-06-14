@extends('layouts.dashboard')

@section('title', 'Bookings')
@section('page-title', 'Manage Bookings')

@section('content')

{{-- Filter Tabs --}}
<div style="display:flex; gap:0.4rem; margin-bottom:1.25rem;">
    @foreach([['all','All Bookings'],['upcoming','Upcoming'],['completed','Completed'],['cancelled','Cancelled']] as [$val,$label])
        <a href="{{ route('host.bookings.index', ['filter' => $val]) }}"
            style="padding:0.45rem 1rem; border-radius:8px; font-size:0.82rem; font-weight:500; text-decoration:none; transition:all 0.15s;
                {{ $filter === $val ? 'background:#1E3A2F; color:white;' : 'background:white; color:#4A4A4A; border:1.5px solid #EDE7DC;' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- Table --}}
<div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; overflow:hidden;">

    {{-- Header --}}
    <div style="display:grid; grid-template-columns:1fr 1fr 120px 80px 100px 120px; padding:0.75rem 1.5rem; background:#F7F3ED; border-bottom:1px solid #EDE7DC;">
        @foreach(['Guest','Experience','Date','Guests','Status','Total'] as $col)
            <div style="font-size:0.7rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.06em;">{{ $col }}</div>
        @endforeach
    </div>

    @if($bookings->isEmpty())
        <div style="padding:3rem; text-align:center; color:#9CA3AF; font-size:0.875rem;">
            <div style="font-size:2rem; margin-bottom:0.75rem;">📅</div>
            No bookings found
        </div>
    @else
        @foreach($bookings as $booking)
            @php
                $statusColor = match($booking->status) {
                    'confirmed'       => '#2D5240',
                    'completed'       => '#1E3A2F',
                    'pending_payment' => '#C4783A',
                    default           => '#C0392B',
                };
                $statusBg = match($booking->status) {
                    'confirmed'       => '#EBF5EE',
                    'completed'       => '#E8E4DC',
                    'pending_payment' => '#FDF6EE',
                    default           => '#FEF2F2',
                };
                $tanggal = \Carbon\Carbon::parse($booking->tanggal_experience)->format('d M Y');
                $jam = $booking->jam_experience
                    ? \Carbon\Carbon::parse($booking->jam_experience)->format('H:i')
                    : null;
            @endphp
            <div style="display:grid; grid-template-columns:1fr 1fr 120px 80px 100px 120px; padding:1rem 1.5rem; border-bottom:1px solid #F7F3ED; align-items:center;"
                onmouseover="this.style.background='#FAFAF8'"
                onmouseout="this.style.background='white'">

                {{-- Guest --}}
                <div>
                    <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">{{ $booking->user->name }}</div>
                    <div style="font-size:0.75rem; color:#9CA3AF;">{{ $booking->kode_booking }}</div>
                </div>

                {{-- Experience --}}
                <div style="padding-right:1rem;">
                    <div style="font-size:0.82rem; color:#1E3A2F;">{{ Str::limit($booking->experience_title_snapshot, 30) }}</div>
                </div>

                {{-- Date --}}
                <div>
                    <div style="font-size:0.82rem; color:#1E3A2F;">{{ $tanggal }}</div>
                    @if($jam)
                        <div style="font-size:0.72rem; color:#9CA3AF;">{{ $jam }} WITA</div>
                    @endif
                </div>

                {{-- Guests --}}
                <div style="font-size:0.875rem; color:#1E3A2F;">{{ $booking->jumlah_peserta }}</div>

                {{-- Status --}}
                <div>
                    <span style="font-size:0.68rem; font-weight:700; letter-spacing:0.06em; padding:0.2rem 0.6rem; border-radius:999px; background:{{ $statusBg }}; color:{{ $statusColor }};">
                        {{ strtoupper($booking->status) }}
                    </span>
                </div>

                {{-- Total --}}
                <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F;">
                    Rp {{ number_format($booking->host_earning, 0, ',', '.') }}
                </div>

            </div>
        @endforeach

        {{-- Pagination --}}
        @if($bookings->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid #EDE7DC; display:flex; justify-content:space-between; align-items:center;">
                <div style="font-size:0.8rem; color:#7A7A6E;">
                    Showing {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} of {{ $bookings->total() }}
                </div>
                {{ $bookings->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
