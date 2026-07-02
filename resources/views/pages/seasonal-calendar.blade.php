@extends('layouts.app')

@section('title', 'Seasonal Calendar | CittaLoka')

@section('content')

<style>
    .sc-hero-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 3rem;
        font-weight: 500;
        color: #1a2e1c;
        line-height: 1.15;
    }
    .sc-month-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #E8E4DC;
        overflow: hidden;
    }
    .sc-month-header {
        background: #1a2e1c;
        color: white;
        padding: 1rem 1.5rem;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.3rem;
        font-weight: 600;
    }
    .sc-event-item {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid #F0EDE6;
        display: flex;
        gap: 1rem;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s;
    }
    .sc-event-item:hover { background: #FAFAF8; }
    .sc-event-item:last-child { border-bottom: none; }
    .sc-date-badge {
        flex-shrink: 0;
        width: 52px;
        height: 52px;
        border-radius: 10px;
        background: #F0EDE6;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
    .sc-empty-month {
        padding: 1.5rem;
        text-align: center;
        font-size: 0.82rem;
        color: #9CA3AF;
    }
</style>

<div class="max-w-7xl mx-auto px-6 py-12">

    {{-- Hero --}}
    <div class="text-center mb-4" style="max-width:700px; margin-left:auto; margin-right:auto;">
        <p style="font-size:0.72rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:#C4783A; margin-bottom:0.75rem;">
            Living Calendar of Bali
        </p>
        <h1 class="sc-hero-title mb-3">Seasonal Calendar</h1>
        <p style="font-size:0.95rem; color:#6B7280; line-height:1.6;">
            Discover Bali's sacred holidays and the best seasons to experience its culture — from temple ceremonies to harvest traditions.
        </p>
    </div>

    {{-- Upcoming Highlight --}}
    @if($upcomingEvent)
        <a href="{{ route('seasonal-calendar.show', $upcomingEvent->id) }}"
            class="block mb-10 mt-8" style="text-decoration:none;">
            <div style="background:linear-gradient(135deg, #1a2e1c 0%, #2D4A32 60%, #C4783A 140%); border-radius:18px; padding:2rem 2.5rem; display:flex; align-items:center; justify-content:space-between; gap:1.5rem; flex-wrap:wrap;">
                <div>
                    <p style="font-size:0.7rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:rgba(255,255,255,0.7); margin-bottom:0.5rem;">
                        Coming Up
                    </p>
                    <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.85rem; font-weight:600; color:white; margin-bottom:0.4rem;">
                        {{ $upcomingEvent->getNama($locale) }}
                    </h2>
                    <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                        <p style="font-size:0.85rem; color:rgba(255,255,255,0.75); margin:0;">
                            {{ $upcomingEvent->start_date->locale('id')->isoFormat('D MMMM YYYY') }}
                            @if($upcomingEvent->isMultiDay())
                                – {{ $upcomingEvent->end_date->locale('id')->isoFormat('D MMMM YYYY') }}
                            @endif
                        </p>
                        @if($upcomingEvent->area)
                            <p style="font-size:0.8rem; color:rgba(255,255,255,0.6); margin:0; display:flex; align-items:center; gap:0.3rem;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $upcomingEvent->area }}
                            </p>
                        @endif
                        @if($upcomingEvent->is_recurring)
                            <span style="font-size:0.7rem; background:rgba(255,255,255,0.15); color:rgba(255,255,255,0.8); padding:0.15rem 0.6rem; border-radius:999px;">
                                Tahunan
                            </span>
                        @endif
                    </div>
                </div>
                <span style="color:white; font-size:0.85rem; font-weight:600; display:flex; align-items:center; gap:0.4rem; flex-shrink:0;">
                    Learn More
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </span>
            </div>
        </a>
    @endif

    {{-- Year Switcher --}}
    <div class="flex items-center justify-center gap-4 mb-8">
        <a href="{{ route('seasonal-calendar.index', ['year' => $year - 1]) }}"
            style="width:36px; height:36px; border-radius:50%; border:1.5px solid #E8E4DC; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#1a2e1c;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <span style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:600; color:#1a2e1c; min-width:80px; text-align:center;">
            {{ $year }}
        </span>
        <a href="{{ route('seasonal-calendar.index', ['year' => $year + 1]) }}"
            style="width:36px; height:36px; border-radius:50%; border:1.5px solid #E8E4DC; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#1a2e1c;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    {{-- 12 Month Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @php
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];
        @endphp

        @foreach($eventsByMonth as $monthNum => $monthEvents)
            <div class="sc-month-card">
                <div class="sc-month-header">{{ $monthNames[$monthNum] }}</div>

                @forelse($monthEvents as $event)
                    <a href="{{ route('seasonal-calendar.show', $event->id) }}" class="sc-event-item">
                        <div class="sc-date-badge">
                            <span style="font-size:1.1rem; font-weight:700; color:#1a2e1c;">{{ $event->start_date->format('d') }}</span>
                            <span style="font-size:0.6rem; color:#9CA3AF; text-transform:uppercase;">{{ $event->start_date->locale('id')->isoFormat('MMM') }}</span>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <p style="font-size:0.88rem; font-weight:600; color:#1a2e1c; margin:0 0 0.2rem; line-height:1.3;">
                                {{ $event->getNama($locale) }}
                            </p>
                            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                                @if($event->isMultiDay())
                                    <p style="font-size:0.72rem; color:#9CA3AF; margin:0;">
                                        s/d {{ $event->end_date->format('d M') }}
                                    </p>
                                @endif
                                @if($event->area)
                                    <p style="font-size:0.72rem; color:#9CA3AF; margin:0; display:flex; align-items:center; gap:0.2rem;">
                                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ $event->area }}
                                    </p>
                                @endif
                                @if($event->is_recurring)
                                    <span style="font-size:0.62rem; font-weight:600; color:#C4783A; background:#FDF6EE; padding:0.1rem 0.45rem; border-radius:999px;">Tahunan</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="sc-empty-month">No events this month</div>
                @endforelse
            </div>
        @endforeach
    </div>

</div>

@endsection