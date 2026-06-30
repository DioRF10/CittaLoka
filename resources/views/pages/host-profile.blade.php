@extends('layouts.app')

@section('title', $host->user->name . ' — CittaLoka Host')

@push('styles')
<style>
    /* ── Reset ── */
    *, *::before, *::after { box-sizing: border-box; }

    /* ── Hero ── */
    .host-hero {
        background: #F4F1ED;
        padding: 4rem 0;
        border-bottom: 1px solid #EBE5D9;
    }
    .host-hero-inner {
        max-width: 1080px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 3.5rem;
        align-items: center;
    }
    .hero-photo-wrap {
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 4/3;
        background: #EBE5D9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #8C9990;
        font-size: 0.9rem;
        box-shadow: 0 10px 30px rgba(30,58,47,0.06);
    }
    .hero-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .host-verified-text {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #1A5336;
        background: #D8EADF;
        padding: 0.35rem 0.8rem;
        border-radius: 999px;
        margin-bottom: 1.25rem;
    }
    .host-name {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 500;
        color: #1E3A2F;
        line-height: 1.1;
        margin-bottom: 0.5rem;
    }
    .host-subtitle {
        font-size: 1.1rem;
        color: #5C7164;
        margin-bottom: 1.5rem;
        font-style: italic;
    }
    .host-meta-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
        font-size: 0.9rem;
        color: #4A5D53;
    }
    .host-meta-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .meta-divider {
        width: 1px;
        height: 14px;
        background: #D4CCC0;
    }
    .host-cta {
        display: flex;
        flex-direction: row;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .btn-cta-primary {
        padding: 0.75rem 1.75rem;
        background: #1E3A2F;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.2s;
    }
    .btn-cta-primary:hover { background: #2D4A32; color: white; }
    .btn-cta-secondary {
        padding: 0.75rem 1.75rem;
        background: transparent;
        color: #1E3A2F;
        border: 1.5px solid #1E3A2F;
        border-radius: 999px; /* Pill shape */
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    .btn-cta-secondary:hover { 
        background: #1E3A2F; 
        color: white; 
        box-shadow: 0 4px 12px rgba(30,58,47,0.15);
    }
    .btn-cta-secondary svg {
        transition: stroke 0.3s ease;
    }
    .btn-cta-secondary:hover svg {
        stroke: white;
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
        font-size: 2.2rem;
        font-weight: 500;
        color: #1E3A2F;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .section-sub {
        font-size: 0.9rem;
        color: #5C7164;
        text-align: center;
        margin-bottom: 3.5rem;
        line-height: 1.6;
    }

    /* ── About layout ── */
    .about-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 3.5rem;
        align-items: start;
    }
    .about-video {
        width: 100%;
        aspect-ratio: 16/9;
        background: #1E3A2F;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
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
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
    }
    .play-btn {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .play-btn:hover { background: rgba(255,255,255,0.3); }
    .about-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.8rem;
        font-weight: 600;
        color: #1E3A2F;
        margin-bottom: 1.2rem;
    }
    .about-bio {
        font-size: 0.95rem;
        color: #4A5D53;
        line-height: 1.8;
        margin-bottom: 2rem;
    }

    /* ── Info sidebar ── */
    .info-card {
        background: white;
        border: 1px solid #EBE5D9;
        border-radius: 16px;
        padding: 1.75rem;
        position: sticky;
        top: 100px;
        box-shadow: 0 10px 30px rgba(30,58,47,0.03);
    }
    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid #F4F1ED;
    }
    .info-row:first-of-type { padding-top: 0; }
    .info-icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #5C7164;
    }
    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #8C9990;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.2rem;
    }
    .info-value {
        font-size: 0.9rem;
        color: #1E3A2F;
        font-weight: 500;
        line-height: 1.4;
    }
    .btn-contact {
        width: 100%;
        padding: 0.85rem;
        background: #1EC876;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
        transition: background 0.2s;
        text-decoration: none;
    }
    .btn-contact:hover { background: #1AB569; color: white; }
    
    .info-stats-divider {
        height: 1px;
        background: #EBE5D9;
        margin: 1.75rem 0;
    }
    .info-stats-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .info-stat {
        text-align: center;
        flex: 1;
        border-right: 1px solid #EBE5D9;
    }
    .info-stat:last-child { border-right: none; }
    .stat-num {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1E3A2F;
        line-height: 1.2;
    }
    .stat-label {
        font-size: 0.7rem;
        color: #8C9990;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 0.2rem;
    }

    /* ── Heritage Tree ── */
    .heritage-section {
        background: #F4F1ED;
        padding-top: 5rem;
        padding-bottom: 5rem;
        margin-top: 5rem;
        max-width: none;
    }
    .heritage-wrap {
        position: relative;
        max-width: 720px;
        margin: 0 auto;
    }
    .heritage-timeline {
        position: relative;
        padding: 2rem 0;
    }
    .heritage-timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 1px;
        background: #D4CCC0;
        transform: translateX(-50%);
    }
    .heritage-item {
        display: grid;
        grid-template-columns: 1fr 40px 1fr;
        gap: 2rem;
        align-items: center;
        margin-bottom: 4rem;
        position: relative;
    }
    .heritage-item:last-child { margin-bottom: 0; }
    .heritage-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #1E3A2F;
        justify-self: center;
        z-index: 1;
    }
    .heritage-content {
        padding: 0 1rem;
    }
    .heritage-content.right { grid-column: 3; text-align: left; }
    .heritage-content.left { grid-column: 1; text-align: right; }
    
    .heritage-photo-wrap {
        display: flex;
    }
    .heritage-photo-wrap.right { grid-column: 3; justify-content: flex-start; padding-left: 1rem; }
    .heritage-photo-wrap.left { grid-column: 1; justify-content: flex-end; padding-right: 1rem; }
    .heritage-photo {
        width: 110px;
        height: 110px;
        border-radius: 12px;
        object-fit: cover;
        background: #EBE5D9;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .heritage-year {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2D5240;
        margin-bottom: 0.3rem;
        font-family: 'DM Sans', sans-serif;
    }
    .heritage-name {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.4rem;
        font-weight: 600;
        color: #1E3A2F;
        margin-bottom: 0.5rem;
    }
    .heritage-desc {
        font-size: 0.9rem;
        color: #5C7164;
        line-height: 1.6;
    }
    .heritage-empty {
        text-align: center;
        padding: 3rem;
        color: #8C9990;
        font-size: 0.9rem;
    }

    /* ── Experiences ── */
    .exp-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.5rem;
    }
    .exp-empty {
        text-align: center;
        padding: 4rem;
        color: #8C9990;
        font-size: 0.9rem;
        background: white;
        border: 1px dashed #EBE5D9;
        border-radius: 16px;
        grid-column: 1 / -1;
    }

    @media (max-width: 768px) {
        .host-hero-inner { grid-template-columns: 1fr; gap: 2rem; }
        .hero-photo-wrap { display: none; /* Hide on mobile to keep text focus */ }
        .about-grid { grid-template-columns: 1fr; }
        .info-card { position: static; }
        .heritage-timeline::before { left: 30px; }
        .heritage-item { grid-template-columns: 1fr; padding-left: 50px; gap: 1rem; }
        .heritage-dot { position: absolute; left: 24px; top: 1.5rem; }
        .heritage-content.left, .heritage-content.right { text-align: left; grid-column: 1; padding: 0; }
        .heritage-photo-wrap { display: none !important; }
    }
</style>
@endpush

@section('content')

@php
    $locale   = app()->getLocale();
    $avatar   = $host->user->avatarUrl();
    $hostingSince = $host->created_at ? $host->created_at->year : null;
    $experiences  = $host->experiences->where('status', 'active');
@endphp

{{-- ═══════════════════════════════════════ --}}
{{-- HERO                                   --}}
{{-- ═══════════════════════════════════════ --}}
<div class="host-hero">
    <div class="host-hero-inner">
        {{-- Kiri: Text Content --}}
        <div>
            @if($host->is_verified)
                <div class="host-verified-text">
                    ✨ Verified Host
                </div>
            @endif

            <h1 class="host-name">{{ $host->user->name }}</h1>

            @if($host->village)
                <div class="host-subtitle">{{ $host->bio_title ?? 'Master Host' }} - {{ $host->village }}, Bali</div>
            @endif

            <div class="host-meta-row">
                @if($host->rating_avg > 0)
                    <div class="host-meta-item">
                        <span style="color: #F59E0B;">⭐</span>
                        <strong style="color:#1E3A2F;">{{ number_format($host->rating_avg, 1) }}</strong>
                        <span>({{ $host->total_reviews }} Reviews)</span>
                    </div>
                    <div class="meta-divider"></div>
                @endif
                @if($host->village)
                    <div class="host-meta-item">
                        <span>📍</span> {{ $host->village }}, Bali
                    </div>
                    <div class="meta-divider"></div>
                @endif
                <div class="host-meta-item">
                    <span>🌐</span> English, ID
                </div>
                @if($hostingSince)
                    <div class="meta-divider"></div>
                    <div class="host-meta-item">
                        <span>📅</span> Hosting since {{ $hostingSince }}
                    </div>
                @endif
            </div>

            <div class="host-cta">
                <a href="#experiences" class="btn-cta-primary">
                    View Experiences
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                </a>
                @auth
                    @if(auth()->user()->role !== 'host')
                        <div x-data="{ following: {{ auth()->user()->isFollowing($host->id) ? 'true' : 'false' }} }"
                             data-auth="1"
                             data-follow-url="{{ route('hosts.follow-toggle') }}"
                             data-host-id="{{ $host->id }}"
                             data-csrf="{{ csrf_token() }}"
                             style="display: inline-block;">
                            <button @click="
                                    following = !following;
                                    fetch($el.closest('[data-host-id]').dataset.followUrl, {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $el.closest('[data-host-id]').dataset.csrf, 'Accept': 'application/json' },
                                        body: JSON.stringify({ host_id: parseInt($el.closest('[data-host-id]').dataset.hostId) }),
                                    }).then(r => r.json()).then(d => { if (!d.success) following = !following; })
                                      .catch(() => { following = !following; });
                                "
                                class="btn-cta-secondary"
                                :style="following ? 'background:#1E3A2F; color:white;' : ''">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14" x-show="!following"></line>
                                    <line x1="23" y1="11" x2="17" y2="11" x-show="!following"></line>
                                    <polyline points="17 11 19 13 23 9" x-show="following" style="display:none;"></polyline>
                                </svg>
                                <span x-text="following ? 'Following' : 'Follow Host'"></span>
                            </button>
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-cta-secondary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        Follow Host
                    </a>
                @endauth
            </div>
        </div>

        {{-- Kanan: Image Placeholder --}}
        <div>
            <div class="hero-photo-wrap">
                @if($host->user->avatar && strpos($host->user->avatar, 'ui-avatars') === false)
                    <img src="{{ $host->user->avatarUrl() }}" alt="{{ $host->user->name }}" class="hero-photo">
                @else
                    <div style="text-align: center; line-height: 1.5;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 0.5rem; color:#C4A882;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Host Photo
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- PAGE BODY                              --}}
{{-- ═══════════════════════════════════════ --}}
<div class="host-page-body">
    
    {{-- About & Info --}}
    <div class="host-section" style="padding-top: 3rem;">
        <div class="about-grid">
            {{-- Kiri: Video & Bio --}}
            <div>
                <div class="about-video">
                    @if($host->video_url)
                        @php
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
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            </div>
                            <span>Video Profile Coming Soon</span>
                        </div>
                    @endif
                </div>

                <h2 class="about-title">About {{ explode(' ', $host->user->name)[0] }}</h2>
                @if($host->bio)
                    <div class="about-bio">{{ $host->bio }}</div>
                @else
                    <div class="about-bio">This host hasn't added a bio yet.</div>
                @endif
            </div>

            {{-- Kanan: Info Card --}}
            <div>
                <div class="info-card">
                    @if($host->village)
                        <div class="info-row">
                            <div class="info-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Location</div>
                                <div class="info-value">{{ $host->village }}, Bali</div>
                            </div>
                        </div>
                    @endif

                    <div class="info-row">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
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
                            
                            $durText = $minDur === $maxDur ? ($minDur / 60) . ' hours' : ($minDur / 60) . '–' . ($maxDur / 60) . ' hours';
                        @endphp
                        <div class="info-row">
                            <div class="info-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Duration</div>
                                <div class="info-value">Experiences typically {{ $durText }}</div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Group Size</div>
                                <div class="info-value">Up to {{ $maxCap }} people per session</div>
                            </div>
                        </div>
                    @endif

                    <a href="#contact" class="btn-contact">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        Contact {{ explode(' ', $host->user->name)[0] }}
                    </a>

                    <div class="info-stats-divider"></div>

                    <div class="info-stats-row">
                        <div class="info-stat">
                            <div class="stat-num">{{ $host->total_reviews ?? 0 }}</div>
                            <div class="stat-label">Guests</div>
                        </div>
                        <div class="info-stat">
                            <div class="stat-num">{{ $experiences->count() }}</div>
                            <div class="stat-label">Experiences</div>
                        </div>
                        <div class="info-stat">
                            <div class="stat-num">{{ $host->rating_avg > 0 ? number_format($host->rating_avg, 1) : '-' }}</div>
                            <div class="stat-label">Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Heritage Tree --}}
    <div class="heritage-section">
        <div class="host-section" style="padding-top: 0;">
            <h2 class="section-title">The Heritage Tree</h2>
            <p class="section-sub">A timeline of artistic evolution across generations of the {{ explode(',', $host->village ?? 'Sudarsana')[0] }} family.</p>

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
                                        <div class="heritage-year">{{ $node->learned_from_year ?? 'Gen ' . ($node->generation_number ?? $loop->iteration) }}</div>
                                        <div class="heritage-name">{{ $node->teacher_name }} [Gen {{ $node->generation_number ?? $loop->iteration }}]</div>
                                        @if($node->skill_description)
                                            <div class="heritage-desc">{{ $node->skill_description }}</div>
                                        @endif
                                    </div>
                                    {{-- Dot --}}
                                    <div class="heritage-dot"></div>
                                    {{-- Foto kanan --}}
                                    <div class="heritage-photo-wrap right">
                                        @if($node->photo_url)
                                            <img src="{{ asset('storage/' . $node->photo_url) }}" alt="{{ $node->teacher_name }}" class="heritage-photo">
                                        @else
                                            <div class="heritage-photo" style="display:flex; align-items:center; justify-content:center;">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D4CCC0" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    {{-- Foto kiri --}}
                                    <div class="heritage-photo-wrap left">
                                        @if($node->photo_url)
                                            <img src="{{ asset('storage/' . $node->photo_url) }}" alt="{{ $node->teacher_name }}" class="heritage-photo">
                                        @else
                                            <div class="heritage-photo" style="display:flex; align-items:center; justify-content:center;">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D4CCC0" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- Dot --}}
                                    <div class="heritage-dot"></div>
                                    {{-- Content kanan --}}
                                    <div class="heritage-content right">
                                        <div class="heritage-year">{{ $node->learned_from_year ?? 'Gen ' . ($node->generation_number ?? $loop->iteration) }}</div>
                                        <div class="heritage-name">{{ $node->teacher_name }} [Gen {{ $node->generation_number ?? $loop->iteration }}]</div>
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
    </div>

    {{-- Experiences --}}
    <div class="host-section" id="experiences">
        <h2 class="section-title">Learn from the master</h2>
        <p class="section-sub">Experiences led personally by {{ explode(' ', $host->user->name)[0] }}.</p>

        <div class="exp-grid">
            @forelse($experiences as $exp)
                @include('components.experience.card', ['exp' => $exp])
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