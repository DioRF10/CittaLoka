@extends('layouts.app')

@section('title', $event->getNama($locale) . ' | CittaLoka')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-12">

    {{-- Back --}}
    <a href="{{ route('seasonal-calendar.index') }}"
        style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.85rem; color:#6B7280; text-decoration:none; margin-bottom:1.5rem;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Calendar
    </a>

    {{-- Header --}}
    <div style="margin-bottom:2rem;">
        <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap; margin-bottom:1rem;">
            @if($event->is_recurring)
                <span style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; padding:0.2rem 0.65rem; border-radius:999px; background:#FDF6EE; color:#C4783A;">
                    Tahunan
                </span>
            @endif
            @if($event->area)
                <span style="font-size:0.78rem; color:#6B7280; display:flex; align-items:center; gap:0.3rem;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ $event->area }}
                </span>
            @endif
        </div>

        <h1 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:2.75rem; font-weight:500; color:#1a2e1c; margin:0 0 0.75rem; line-height:1.15;">
            {{ $event->getNama($locale) }}
        </h1>

        <p style="font-size:0.95rem; color:#6B7280; display:flex; align-items:center; gap:0.5rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            {{ $event->start_date->locale('id')->isoFormat('D MMMM YYYY') }}
            @if($event->isMultiDay())
                – {{ $event->end_date->locale('id')->isoFormat('D MMMM YYYY') }}
            @endif
        </p>
    </div>

    {{-- Cover --}}
    @if($event->thumbnail_url)
        <img src="{{ $event->thumbnail_url }}" alt="{{ $event->getNama($locale) }}"
            style="width:100%; height:340px; object-fit:cover; border-radius:18px; margin-bottom:2.5rem;">
    @endif

    {{-- Deskripsi --}}
    @if($event->getDeskripsi($locale))
        <div style="font-size:0.98rem; color:#374151; line-height:1.8; margin-bottom:3rem; max-width:720px;">
            {!! nl2br(e($event->getDeskripsi($locale))) !!}
        </div>
    @endif

    @if($event->experiences->count() > 0)
        <div style="margin-bottom:3rem;">
            <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:2rem; font-weight:600; color:#1a2e1c; margin-bottom:1.5rem;">
                Experiences for this Season
            </h2>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:1.5rem;">
                @foreach($event->experiences as $exp)
                    <x-experience-card :exp="$exp" />
                @endforeach
            </div>
        </div>
    @else
        {{-- CTA Explore --}}
        <div style="background:linear-gradient(135deg, #F7F3ED, #F0EDE6); border-radius:16px; padding:2rem; text-align:center; margin-bottom:2rem;">
            <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:600; color:#1a2e1c; margin-bottom:0.5rem;">
                Experience Bali During This Season
            </h3>
            <p style="font-size:0.875rem; color:#6B7280; margin-bottom:1.25rem; line-height:1.6;">
                Find authentic cultural experiences to book during this special time in Bali.
            </p>
            <a href="{{ route('experiences.index') }}"
                style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.8rem 1.75rem; background:#1a2e1c; color:white; border-radius:10px; font-size:0.875rem; font-weight:600; text-decoration:none;">
                Explore Experiences
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    @endif

</div>

@endsection