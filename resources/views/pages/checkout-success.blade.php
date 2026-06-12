@extends('layouts.app')

@section('title', 'Booking Confirmed — CittaLoka')

@section('content')

@php
    $locale = app()->getLocale();
    $experience = $booking->experience;
    $cover = $experience->photos->where('is_cover', true)->first() ?? $experience->photos->first();
    $tanggal = \Carbon\Carbon::parse($booking->tanggal_experience)->locale('en')->isoFormat('ddd, MMM D, YYYY');
    $jam = \Carbon\Carbon::parse($booking->jam_experience)->format('H:i');
@endphp

<div style="background:#F7F3ED; min-height:100vh; padding-bottom:4rem;">

    {{-- Progress Steps --}}
    <div style="max-width:680px; margin:0 auto; padding:2.5rem 2rem 0;">
        <div style="display:flex; align-items:center; justify-content:center; gap:0; margin-bottom:2.5rem;">
            @php
                $steps = [
                    ['label' => 'Select Date', 'num' => 1],
                    ['label' => 'Confirm',     'num' => 2],
                    ['label' => 'Pay',         'num' => 3],
                    ['label' => 'Done',        'num' => 4],
                ];
            @endphp
            @foreach($steps as $step)
                <div style="display:flex; align-items:center;">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:0.35rem;">
                        <div style="width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:600;
                            {{ $step['num'] === 4 ? 'background:#1E3A2F; color:white;' : 'background:#E8E4DC; color:#7A7A6E;' }}">
                            {{ $step['num'] === 4 ? '✓' : $step['num'] }}
                        </div>
                        <span style="font-size:0.7rem; font-weight:{{ $step['num'] === 4 ? '600' : '400' }}; color:{{ $step['num'] === 4 ? '#1E3A2F' : '#9CA3AF' }}; white-space:nowrap;">
                            {{ $step['label'] }}
                        </span>
                    </div>
                    @if(!$loop->last)
                        <div style="width:80px; height:1px; background:#E2DDD5; margin:0 0.5rem; margin-bottom:1.2rem;"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Success Content --}}
    <div style="max-width:560px; margin:0 auto; padding:0 2rem; text-align:center;">

        {{-- Checkmark --}}
        <div style="width:80px; height:80px; background:#EBF5EE; border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#2D5240" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>

        <h1 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:2.5rem; font-weight:500; color:#1E3A2F; margin-bottom:0.75rem;">
            You're all set!
        </h1>
        <p style="font-size:0.9rem; color:#7A7A6E; line-height:1.6; margin-bottom:2rem;">
            Booking confirmed. Check your email for full details — we've sent everything to
            <strong style="color:#1E3A2F;">{{ auth()->user()->email }}</strong>
        </p>

        {{-- Booking Card --}}
        <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; overflow:hidden; text-align:left; margin-bottom:2rem;">

            {{-- Header --}}
            <div style="padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #EDE7DC;">
                <div>
                    <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.2rem;">Booking Number</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#1E3A2F; font-family:'DM Sans',sans-serif;">{{ $booking->kode_booking }}</div>
                </div>
                <span style="background:#EBF5EE; color:#2D5240; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; padding:0.3rem 0.75rem; border-radius:999px; border:1px solid #B8DFC8;">
                    CONFIRMED
                </span>
            </div>

            {{-- Experience Info --}}
            <div style="padding:1.25rem; display:flex; gap:1rem; align-items:center;">
                <div style="width:64px; height:64px; border-radius:10px; overflow:hidden; flex-shrink:0;">
                    @if($cover)
                        <img src="{{ $cover->url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <div style="width:100%; height:100%; background:linear-gradient(135deg,#2D5240,#C4A882);"></div>
                    @endif
                </div>
                <div>
                    <div style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.05rem; font-weight:500; color:#1E3A2F; margin-bottom:0.3rem;">
                        {{ $booking->experience_title_snapshot }}
                    </div>
                    <div style="font-size:0.8rem; color:#7A7A6E; display:flex; flex-direction:column; gap:0.2rem;">
                        <span>👤 {{ $booking->host_name_snapshot }}</span>
                        <span>📅 {{ $tanggal }} · {{ $jam }} WITA</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- What's Next --}}
        <div style="margin-bottom:2rem;">
            <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.12em; margin-bottom:1.25rem;">What's Next</div>
            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem;">
                @php
                    $nexts = [
                        ['icon' => '✉️', 'label' => 'Check your inbox for details'],
                        ['icon' => '💬', 'label' => 'Host will contact you'],
                        ['icon' => '🎉', 'label' => 'Show up & enjoy!'],
                    ];
                @endphp
                @foreach($nexts as $next)
                    <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; padding:1rem; text-align:center;">
                        <div style="font-size:1.5rem; margin-bottom:0.5rem;">{{ $next['icon'] }}</div>
                        <div style="font-size:0.78rem; color:#4A4A4A; line-height:1.4;">{{ $next['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="display:flex; flex-direction:column; gap:0.75rem;">
            <a href="{{ route('home') }}"
                style="display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.95rem; background:#1E3A2F; color:white; border-radius:10px; font-size:0.9rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all 0.2s;"
                onmouseover="this.style.background='#2D4A32'"
                onmouseout="this.style.background='#1E3A2F'">
                View My Booking
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('experiences.index') }}"
                style="display:flex; align-items:center; justify-content:center; padding:0.95rem; background:white; color:#1E3A2F; border:1.5px solid #E2DDD5; border-radius:10px; font-size:0.9rem; font-weight:500; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all 0.2s;"
                onmouseover="this.style.borderColor='#1E3A2F'"
                onmouseout="this.style.borderColor='#E2DDD5'">
                Explore More Experiences
            </a>
        </div>

    </div>
</div>

@endsection