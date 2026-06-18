@extends('layouts.app')

@section('title', $host->user->name . ' — CittaLoka Host')

@push('styles')
<style>
    /* ── Reset ── */
    *, *::before, *::after { box-sizing: border-box; }

    /* ── Hero ── */
    .host-hero {
        background: #F7F3ED;
        padding: 3rem 0 0;
        border-bottom: 1px solid #EDE7DC;
    }
    .host-hero-inner {
        max-width: 1080px;
        margin: 0 auto;
        padding: 0 2rem 0;
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 3rem;
        align-items: start;
    }
    .host-hero-left {
        padding-bottom: 3rem;
        padding-top: 0.5rem;
    }
    .host-hero-right {
        position: relative;
        align-self: stretch;
        display: flex;
        align-items: flex-end;
    }
    .host-hero-photo {
        width: 100%;
        aspect-ratio: 3/4;
        object-fit: cover;
        border-radius: 14px 14px 0 0;
        display: block;
        background: #E8E0D3;
    }
    .host-hero-photo-placeholder {
        width: 100%;
        aspect-ratio: 3/4;
        border-radius: 14px 14px 0 0;
        background: linear-gradient(160deg, #E8E4DC, #D4CCBC);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #EDE7DC;
        border-bottom: none;
    }
    .host-avatar-wrap {
        position: relative;
        flex-shrink: 0;
        margin-bottom: 0;
    }
    .host-avatar {
        width: 0;
        height: 0;
        display: none;
    }
    .host-verified-badge {
        display: none;
    }
    .host-hero-info { padding-bottom: 0; }
    .host-verified-text {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #2D5240;
        background: #EBF5EE;
        border: 1px solid #B8DFC8;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        margin-bottom: 1rem;
    }
    .host-name {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2.2rem, 4vw, 3.25rem);
        font-weight: 500;
        color: #1E3A2F;
        line-height: 1.05;
        margin-bottom: 0.5rem;
    }
    .host-subtitle {
        font-size: 0.9rem;
        color: #7A7A6E;
        margin-bottom: 1.25rem;
        font-style: italic;
    }
    .host-meta-row {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .host-meta-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.82rem;
        color: #4A4A4A;
    }
    .host-meta-item svg { opacity: 0.5; }
    .host-cta {
        display: flex;
        flex-direction: row;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .btn-cta-primary {
        padding: 0.65rem 1.5rem;
        background: #1E3A2F;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        transition: background 0.15s;
    }
    .btn-cta-primary:hover { background: #2D4A32; }
    .btn-cta-secondary {
        padding: 0.65rem 1.5rem;
        background: white;
        color: #1E3A2F;
        border: 1.5px solid #D4CCC0;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        transition: all 0.15s;
    }
    .btn-cta-secondary:hover { background: #F7F3ED; border-color: #1E3A2F; }

    /* ── Stats bar ── */
    .host-stats-bar {
        background: white;
        border-bottom: 1px solid #EDE7DC;
        border-top: 1px solid #EDE7DC;
    }
    .host-stats-inner {
        max-width: 1080px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        gap: 0;
    }
    .host-stat {
        padding: 1.1rem 2rem 1.1rem 0;
        margin-right: 2rem;
        border-right: 1px solid #EDE7DC;
        text-align: center;
    }
    .host-stat:last-child { border-right: none; }
    .host-stat-num {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1E3A2F;
        font-family: 'DM Sans', sans-serif;
        line-height: 1;
    }
    .host-stat-label {
        font-size: 0.7rem;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-top: 0.2rem;
        font-weight: 600;
    }

    /* ── Page body ── */
    .host-page-body {
        background: #FAFAF8;
        min-height: 100vh;
        padding-bottom: 5rem;
    }
    .host-section {
        max-width: 1080px;
        margin: 0 auto;
        padding: 4rem 2rem 0;
    }
    .section-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 2rem;
        font-weight: 500;
        color: #1E3A2F;
        margin-bottom: 0.4rem;
        text-align: center;
    }
    .section-sub {
        font-size: 0.875rem;
        color: #7A7A6E;
        text-align: center;
        margin-bottom: 3rem;
        line-height: 1.6;
    }

    /* ── About layout ── */
    .about-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 3rem;
        align-items: start;
    }
    .about-video {
        width: 100%;
        aspect-ratio: 16/9;
        background: #1E3A2F;
        border-radius: 14px;
        overflow: hidden;
        position: relative;
        margin-bottom: 2rem;
    }
    .about-video iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    .about-video-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1E3A2F, #2D5240);
        flex-direction: column;
        gap: 1rem;
        color: rgba(255,255,255,0.5);
        font-size: 0.85rem;
    }
    .play-btn {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: 2px solid rgba(255,255,255,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .play-btn:hover { background: rgba(255,255,255,0.25); }
    .about-bio {
        font-size: 0.9rem;
        color: #3A3A3A;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }
    .soul-type-chips {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .soul-type-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #7A7A6E;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .soul-chip {
        padding: 0.3rem 0.875rem;
        background: #F0EBE3;
        color: #1E3A2F;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 500;
    }

    /* ── Info sidebar ── */
    .info-card {
        background: white;
        border: 1.5px solid #EDE7DC;
        border-radius: 14px;
        padding: 1.5rem;
        position: sticky;
        top: 80px;
    }
    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F3EFE8;
    }
    .info-row:last-of-type { border-bottom: none; }
    .info-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #F0EBE3;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #C4783A;
    }
    .info-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 0.15rem;
    }
    .info-value {
        font-size: 0.875rem;
        color: #1E3A2F;
        font-weight: 500;
    }

    /* ── Heritage Tree ── */
    .heritage-wrap {
        position: relative;
        max-width: 680px;
        margin: 0 auto;
    }
    .heritage-timeline {
        position: relative;
        padding: 0;
    }
    .heritage-timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #EDE7DC, #C4783A, #EDE7DC);
        transform: translateX(-50%);
    }
    .heritage-item {
        display: grid;
        grid-template-columns: 1fr 40px 1fr;
        gap: 1.5rem;
        align-items: center;
        margin-bottom: 3rem;
        position: relative;
    }
    .heritage-item:last-child { margin-bottom: 0; }
    .heritage-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #C4783A;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #C4783A;
        justify-self: center;
        z-index: 1;
    }
    .heritage-content {
        background: white;
        border: 1.5px solid #EDE7DC;
        border-radius: 14px;
        padding: 1.25rem;
        box-shadow: 0 4px 16px rgba(30,58,47,0.06);
    }
    .heritage-content.right { grid-column: 3; }
    .heritage-content.left { grid-column: 1; text-align: right; }
    .heritage-photo-wrap {
        grid-column: 1;
        display: flex;
        justify-content: flex-end;
    }
    .heritage-photo-wrap.right {
        grid-column: 3;
        justify-content: flex-start;
    }
    .heritage-photo {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #EDE7DC;
        background: #F0EBE3;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .heritage-year {
        font-size: 0.72rem;
        font-weight: 700;
        color: #C4783A;
        letter-spacing: 0.1em;
        margin-bottom: 0.3rem;
    }
    .heritage-name {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.05rem;
        font-weight: 500;
        color: #1E3A2F;
        margin-bottom: 0.3rem;
    }
    .heritage-desc {
        font-size: 0.8rem;
        color: #7A7A6E;
        line-height: 1.6;
    }
    .heritage-gen-badge {
        display: inline-block;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.15rem 0.5rem;
        background: #F0EBE3;
        color: #C4783A;
        border-radius: 999px;
        letter-spacing: 0.06em;
        margin-bottom: 0.4rem;
        text-transform: uppercase;
    }
    .heritage-empty {
        text-align: center;
        padding: 3rem;
        color: #9CA3AF;
        font-size: 0.875rem;
        background: white;
        border: 1.5px dashed #EDE7DC;
        border-radius: 14px;
    }

    /* ── Experiences ── */
    .exp-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
    }
    .exp-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        border: 1.5px solid #EDE7DC;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
        display: block;
    }
    .exp-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(30,58,47,0.1);
    }
    .exp-card-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        background: linear-gradient(135deg, #2D5240, #C4A882);
        display: block;
    }
    .exp-card-body { padding: 1rem; }
    .exp-card-cat {
        font-size: 0.68rem;
        font-weight: 700;
        color: #C4783A;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.3rem;
    }
    .exp-card-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1rem;
        font-weight: 500;
        color: #1E3A2F;
        margin-bottom: 0.4rem;
        line-height: 1.3;
    }
    .exp-card-meta {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.75rem;
        color: #7A7A6E;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
    }
    .exp-card-price {
        font-size: 0.875rem;
        font-weight: 700;
        color: #1E3A2F;
        font-family: 'DM Sans', sans-serif;
    }
    .exp-card-price span {
        font-size: 0.75rem;
        font-weight: 400;
        color: #9CA3AF;
    }
    .exp-empty {
        text-align: center;
        padding: 3rem;
        color: #9CA3AF;
        font-size: 0.875rem;
        background: white;
        border: 1.5px dashed #EDE7DC;
        border-radius: 14px;
        grid-column: 1 / -1;
    }

    @media (max-width: 768px) {
        .host-hero-inner { grid-template-columns: auto 1fr; }
        .host-cta { display: none; }
        .about-grid { grid-template-columns: 1fr; }
        .info-card { position: static; }
        .heritage-timeline::before { left: 20px; }
        .heritage-item { grid-template-columns: 1fr; padding-left: 3rem; }
        .heritage-dot { position: absolute; left: 13px; top: 1.5rem; }
        .heritage-content.left { text-align: left; grid-column: 1; }
        .heritage-content.right { grid-column: 1; }
        .heritage-photo-wrap, .heritage-photo-wrap.right { display: none; }
        .exp-grid { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')

@php
    $locale   = app()->getLocale();
    $avatar   = $host->user->avatar
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($host->user->name) . '&background=1E3A2F&color=fff&size=200';
    $hostingSince = $host->created_at ? $host->created_at->year : null;
    $experiences  = $host->experiences->where('status', 'active');
@endphp

{{-- ═══════════════════════════════════════ --}}
{{-- HERO                                   --}}
{{-- ═══════════════════════════════════════ --}}
<div class="host-hero">
    <div class="host-hero-inner">

        {{-- Kiri: Info --}}
        <div class="host-hero-left">

            {{-- Verified badge --}}
            @if($host->is_verified)
                <div class="host-verified-text">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Verified Host
                </div>
            @endif

            {{-- Nama --}}
            <h1 class="host-name">{{ $host->user->name }}</h1>

            {{-- Subtitle: keahlian + lokasi --}}
            @if($host->village)
                <div class="host-subtitle">{{ $host->village }}</div>
            @endif

            {{-- Meta row --}}
            <div class="host-meta-row">
                @if($host->rating_avg > 0)
                    <div class="host-meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="#C4783A" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <strong style="color:#1E3A2F;">{{ number_format($host->rating_avg, 1) }}</strong>
                        <span style="color:#7A7A6E;">({{ $host->total_reviews }} Reviews)</span>
                    </div>
                @endif
                @if($hostingSince)
                    <div class="host-meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Hosting since {{ $hostingSince }}
                    </div>
                @endif
                <div class="host-meta-item">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    ID · EN
                </div>
            </div>

            {{-- CTA --}}
            <div class="host-cta">
                <a href="#experiences" class="btn-cta-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    View Experiences
                </a>
                @auth
                    @if(auth()->user()->role !== 'host')
                        <button class="btn-cta-secondary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            Follow Host
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-cta-secondary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        Follow Host
                    </a>
                @endauth
            </div>
        </div>

        {{-- Kanan: Foto besar --}}
        <div class="host-hero-right">
            @if($host->user->avatar)
                <img src="{{ $host->user->avatar }}"
                    alt="{{ $host->user->name }}"
                    class="host-hero-photo">
            @else
                <div class="host-hero-photo-placeholder">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#C4A882" stroke-width="1" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
            @endif
        </div>

    </div>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- STATS BAR                              --}}
{{-- ═══════════════════════════════════════ --}}
<div class="host-stats-bar">
    <div class="host-stats-inner">
        <div class="host-stat">
            <div class="host-stat-num">{{ $host->total_reviews ?? 0 }}</div>
            <div class="host-stat-label">Guests</div>
        </div>
        <div class="host-stat">
            <div class="host-stat-num">{{ $experiences->count() }}</div>
            <div class="host-stat-label">Experiences</div>
        </div>
        @if($host->rating_avg > 0)
            <div class="host-stat">
                <div class="host-stat-num">{{ number_format($host->rating_avg, 1) }}</div>
                <div class="host-stat-label">Rating</div>
            </div>
        @endif
        @if($hostingSince)
            <div class="host-stat">
                <div class="host-stat-num">{{ now()->year - $hostingSince }}+</div>
                <div class="host-stat-label">Years Hosting</div>
            </div>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- ABOUT                                  --}}
{{-- ═══════════════════════════════════════ --}}
<div class="host-page-body">
    <div class="host-section">
        <div class="about-grid">

            {{-- Left: Video + Bio --}}
            <div>
                {{-- Video --}}
                <div class="about-video">
                    @if($host->video_url)
                        @php
                            // Convert YouTube URL ke embed
                            $videoUrl = $host->video_url;
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/', $videoUrl, $matches)) {
                                $videoUrl = 'https://www.youtube.com/embed/' . $matches[1];
                            } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches)) {
                                $videoUrl = 'https://player.vimeo.com/video/' . $matches[1];
                            }
                        @endphp
                        <iframe src="{{ $videoUrl }}" allowfullscreen></iframe>
                    @else
                        <div class="about-video-placeholder">
                            <div class="play-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            </div>
                            <span>No video yet</span>
                        </div>
                    @endif
                </div>

                {{-- Bio --}}
                @if($host->bio)
                    <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">
                        About {{ explode(' ', $host->user->name)[0] }}
                    </h2>
                    <div class="about-bio">{{ $host->bio }}</div>
                @endif

                {{-- Soul Type chips (placeholder) --}}
                <div class="soul-type-chips">
                    <span class="soul-type-label">Best match for</span>
                    <span class="soul-chip">The Creator</span>
                    <span class="soul-chip">The Seeker</span>
                </div>
            </div>

            {{-- Right: Info Card --}}
            <div>
                <div class="info-card">
                    @if($host->village)
                        <div class="info-row">
                            <div class="info-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Location</div>
                                <div class="info-value">{{ $host->village }}, Bali</div>
                            </div>
                        </div>
                    @endif

                    <div class="info-row">
                        <div class="info-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        </div>
                        <div>
                            <div class="info-label">Languages</div>
                            <div class="info-value">Bahasa Indonesia, English</div>
                        </div>
                    </div>

                    @if($experiences->count() > 0)
                        @php
                            $minDur = $experiences->min('durasi_menit');
                            $maxDur = $experiences->max('durasi_menit');
                            $minCap = $experiences->min('kapasitas_min');
                            $maxCap = $experiences->max('kapasitas_max');
                        @endphp
                        <div class="info-row">
                            <div class="info-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Duration</div>
                                <div class="info-value">
                                    @if($minDur === $maxDur)
                                        {{ $minDur }} minutes
                                    @else
                                        {{ $minDur }}–{{ $maxDur }} minutes
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Group Size</div>
                                <div class="info-value">Up to {{ $maxCap }} people per session</div>
                            </div>
                        </div>
                    @endif

                    {{-- View all experiences CTA --}}
                    <a href="#experiences" class="btn-cta-primary" style="width:100%; justify-content:center; margin-top:1.25rem; text-decoration:none;">
                        View All Experiences
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- HERITAGE TREE                          --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="host-section">
        <h2 class="section-title">The Heritage Tree</h2>
        <p class="section-sub">
            A timeline of artistic evolution across generations
            @if($host->village) of the {{ explode(',', $host->village)[0] }} family @endif.
        </p>

        <div class="heritage-wrap">
            @if($heritageTree->isEmpty())
                <div class="heritage-empty">
                    <div style="font-size:1.5rem; margin-bottom:0.5rem;">🌳</div>
                    Heritage story coming soon.
                </div>
            @else
                <div class="heritage-timeline">
                    @foreach($heritageTree as $i => $node)
                        @php $isLeft = $i % 2 === 0; @endphp
                        <div class="heritage-item">

                            @if($isLeft)
                                {{-- Content kiri --}}
                                <div class="heritage-content left">
                                    <div class="heritage-gen-badge">Gen {{ $node->generation_number ?? $loop->iteration }}</div>
                                    @if($node->learned_from_year)
                                        <div class="heritage-year">{{ $node->learned_from_year }}</div>
                                    @endif
                                    <div class="heritage-name">{{ $node->teacher_name }}</div>
                                    @if($node->skill_description)
                                        <div class="heritage-desc">{{ $node->skill_description }}</div>
                                    @endif
                                </div>
                                {{-- Dot --}}
                                <div class="heritage-dot"></div>
                                {{-- Foto placeholder kanan --}}
                                <div class="heritage-photo-wrap right">
                                    <div class="heritage-photo">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C4A882" stroke-width="1.5" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                </div>
                            @else
                                {{-- Foto placeholder kiri --}}
                                <div class="heritage-photo-wrap">
                                    <div class="heritage-photo">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C4A882" stroke-width="1.5" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                </div>
                                {{-- Dot --}}
                                <div class="heritage-dot"></div>
                                {{-- Content kanan --}}
                                <div class="heritage-content right">
                                    <div class="heritage-gen-badge">Gen {{ $node->generation_number ?? $loop->iteration }}</div>
                                    @if($node->learned_from_year)
                                        <div class="heritage-year">{{ $node->learned_from_year }}</div>
                                    @endif
                                    <div class="heritage-name">{{ $node->teacher_name }}</div>
                                    @if($node->skill_description)
                                        <div class="heritage-desc">{{ $node->skill_description }}</div>
                                    @endif
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- EXPERIENCES                            --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="host-section" id="experiences">
        <h2 class="section-title">Learn from the master</h2>
        <p class="section-sub">Experiences from 2–8 hours led personally by {{ explode(' ', $host->user->name)[0] }}.</p>

        <div class="exp-grid">
            @forelse($experiences as $exp)
                @php
                    $cover = $exp->photos->where('is_cover', true)->first() ?? $exp->photos->first();
                    $judul = is_array($exp->judul)
                        ? ($exp->judul[$locale] ?? $exp->judul['id'] ?? $exp->judul['en'] ?? '')
                        : $exp->judul;
                    $katNama = '';
                    if ($exp->kategori) {
                        $katNama = is_array($exp->kategori->nama)
                            ? ($exp->kategori->nama[$locale] ?? $exp->kategori->nama['id'] ?? '')
                            : $exp->kategori->nama;
                    }
                @endphp
                <a href="{{ route('experiences.show', $exp->slug) }}" class="exp-card">
                    @if($cover)
                        <img src="{{ $cover->url }}" alt="{{ $judul }}" class="exp-card-img">
                    @else
                        <div class="exp-card-img" style="display:flex; align-items:center; justify-content:center;">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                    @endif
                    <div class="exp-card-body">
                        @if($katNama)
                            <div class="exp-card-cat">{{ $katNama }}</div>
                        @endif
                        <div class="exp-card-title">{{ $judul }}</div>
                        <div class="exp-card-meta">
                            <span>📍 {{ $exp->kabupaten }}</span>
                            <span>⏱ {{ $exp->durasi_menit }} min</span>
                            @if($exp->rating_avg > 0)
                                <span>★ {{ number_format($exp->rating_avg, 1) }}</span>
                            @endif
                        </div>
                        <div class="exp-card-price">
                            Rp {{ number_format($exp->harga, 0, ',', '.') }}
                            <span>/ person</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="exp-empty">
                    <div style="font-size:1.5rem; margin-bottom:0.5rem;">🌿</div>
                    No active experiences yet.
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection