<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Book — {{ $booking->experience_title_snapshot }} | CittaLoka</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=DM+Sans:wght@300;400;500;600&family=Caveat:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --green-dark:    #1E3A2F;
            --green-sidebar: #1A2E1C;
            --terracotta:    #C4783A;
            --cream:         #F7F3ED;
            --cream-light:   #FAFAF8;
            --gray-text:     #6B7280;
            --gray-border:   #E8E4DC;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream-light);
            color: #1C1C1C;
        }

        /* ── Hero ── */
        .hero-section {
            position: relative;
            height: 100vh;
            min-height: 600px;
            overflow: hidden;
        }

        .hero-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(10,25,15,0.15) 0%,
                rgba(10,25,15,0.35) 40%,
                rgba(10,25,15,0.75) 100%
            );
        }

        .hero-content {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
        }

        .hero-eyebrow {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.65);
            margin-bottom: 0.75rem;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 400;
            line-height: 1.1;
            color: #fff;
            margin-bottom: 1rem;
        }

        .hero-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .hero-divider-line {
            height: 1px;
            width: 60px;
            background: rgba(255,255,255,0.45);
        }

        .hero-ornament {
            color: rgba(255,255,255,0.55);
            font-size: 1rem;
        }

        .hero-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255,255,255,0.8);
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }

        .hero-meta-row {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .hero-meta-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .hero-chip {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .hero-chip-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-chip-text {
            display: flex;
            flex-direction: column;
        }

        .hero-chip-label {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .hero-chip-value {
            font-size: 0.875rem;
            color: #fff;
            font-weight: 500;
        }

        .hero-quote-card {
            background: rgba(30,58,47,0.75);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            max-width: 260px;
        }

        .hero-quote-mark {
            font-size: 1.5rem;
            color: var(--terracotta);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .hero-quote-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 0.95rem;
            font-style: italic;
            color: rgba(255,255,255,0.9);
            line-height: 1.5;
            margin-bottom: 0.5rem;
        }

        .hero-quote-author {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.05em;
        }

        /* ── Share button ── */
        .btn-share {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.125rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(8px);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-share:hover {
            background: rgba(255,255,255,0.2);
        }

        /* ── Section base ── */
        .section {
            padding: 5rem 0;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-eyebrow {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--terracotta);
            margin-bottom: 0.75rem;
        }

        /* ── Cerita dari Host ── */
        .host-story-section {
            background: #fff;
        }

        .host-story-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        @media (max-width: 768px) {
            .host-story-grid {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }
        }

        .story-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 4vw, 3.25rem);
            font-weight: 400;
            color: var(--green-dark);
            line-height: 1.15;
            margin-bottom: 1rem;
        }

        .story-ornament {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.75rem;
        }

        .story-ornament-line {
            height: 1px;
            width: 40px;
            background: var(--terracotta);
            opacity: 0.5;
        }

        .story-body {
            font-size: 1rem;
            line-height: 1.85;
            color: #4A4A4A;
            margin-bottom: 2rem;
            white-space: pre-line;
        }

        .signature-block {
            margin-top: 1.5rem;
        }

        .signature-name {
            font-family: 'Caveat', cursive;
            font-size: 1.75rem;
            color: var(--green-dark);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .signature-role {
            font-size: 0.8rem;
            color: var(--gray-text);
            letter-spacing: 0.05em;
        }

        .story-photo {
            width: 100%;
            aspect-ratio: 4/5;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        }

        /* ── Gallery ── */
        .gallery-section {
            background: var(--cream);
        }

        .gallery-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 400;
            color: var(--green-dark);
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .gallery-ornament {
            display: flex;
            justify-content: center;
            margin-bottom: 3rem;
        }

        /* Scrollable Gallery */
        .gallery-scroll-wrapper {
            position: relative;
        }

        .gallery-scroll-track {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding-bottom: 0.5rem;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #C4783A transparent;
        }

        .gallery-scroll-track::-webkit-scrollbar {
            height: 6px;
        }

        .gallery-scroll-track::-webkit-scrollbar-track {
            background: transparent;
        }

        .gallery-scroll-track::-webkit-scrollbar-thumb {
            background: #D9C6B0;
            border-radius: 999px;
        }

        .gallery-scroll-track::-webkit-scrollbar-thumb:hover {
            background: #C4783A;
        }

        .gallery-photo-item {
            flex: 0 0 280px;
            aspect-ratio: 4/3;
            border-radius: 12px;
            overflow: hidden;
            cursor: zoom-in;
            scroll-snap-align: start;
        }

        .gallery-photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .gallery-photo-item:hover img {
            transform: scale(1.05);
        }

        @media (max-width: 640px) {
            .gallery-photo-item { flex: 0 0 220px; }
        }

        .gallery-scroll-hint {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            font-size: 0.75rem;
            color: #9CA3AF;
        }

        .gallery-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.95);
            border: 1px solid #E8E4DC;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            color: #1E3A2F;
            transition: all 0.2s;
        }

        .gallery-nav-btn:hover {
            background: #1E3A2F;
            color: #fff;
        }

        .gallery-nav-prev { left: -8px; }
        .gallery-nav-next { right: -8px; }

        @media (max-width: 768px) {
            .gallery-nav-btn { display: none; }
        }

        /* ── Bottom 3-col ── */
        .bottom-section {
            background: #fff;
        }

        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr 1fr;
            gap: 3rem;
            align-items: start;
        }

        @media (max-width: 900px) {
            .bottom-grid {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }
        }

        /* Highlight */
        .highlight-title {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gray-text);
            margin-bottom: 1.5rem;
        }

        .highlight-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            align-items: flex-start;
        }

        .highlight-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .highlight-text h4 {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--green-dark);
            margin-bottom: 0.2rem;
        }

        .highlight-text p {
            font-size: 0.8rem;
            color: var(--gray-text);
            line-height: 1.5;
        }

        /* Pesan Penutup */
        .closing-title {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gray-text);
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .closing-quote-mark {
            font-size: 3rem;
            color: var(--terracotta);
            opacity: 0.3;
            font-family: 'Cormorant Garamond', serif;
            line-height: 0.5;
            text-align: center;
            margin-bottom: 1rem;
        }

        .closing-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-style: italic;
            line-height: 1.75;
            color: #3A3A3A;
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .closing-sign {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            color: var(--gray-text);
            text-align: center;
        }

        /* CTA Card */
        .cta-card {
            background: var(--cream);
            border-radius: 16px;
            padding: 1.75rem 1.5rem;
            text-align: center;
        }

        .cta-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .cta-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--green-dark);
            margin-bottom: 0.4rem;
        }

        .cta-subtitle {
            font-size: 0.8rem;
            color: var(--gray-text);
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .btn-review {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.5rem;
            border-radius: 999px;
            border: 1.5px solid var(--green-dark);
            background: transparent;
            color: var(--green-dark);
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            width: 100%;
            justify-content: center;
        }

        .btn-review:hover {
            background: var(--green-dark);
            color: #fff;
        }

        /* ── Footer note ── */
        .footer-note {
            background: var(--cream-light);
            border-top: 1px solid var(--gray-border);
            padding: 1.25rem 2rem;
            text-align: center;
        }

        .footer-note p {
            font-size: 0.8rem;
            color: var(--gray-text);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up {
            opacity: 0;
            animation: fadeUp 0.7s ease forwards;
        }

        .fade-up-1 { animation-delay: 0.1s; }
        .fade-up-2 { animation-delay: 0.25s; }
        .fade-up-3 { animation-delay: 0.4s; }
        .fade-up-4 { animation-delay: 0.55s; }

        /* ── Lightbox ── */
        .lightbox {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.92);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .lightbox img {
            max-width: 90vw;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
        }

        .lightbox-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: rgba(255,255,255,0.15);
            border: none;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .lightbox-close:hover {
            background: rgba(255,255,255,0.25);
        }

        /* Ornament Bali */
        .bali-ornament {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-12c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z' fill='%23C4783A' opacity='0.6'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            vertical-align: middle;
        }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════════ --}}
{{-- Navbar --}}
@include('components.shared.navbar')

<section class="hero-section">

    {{-- Cover photo --}}
    @php
        $coverPhoto = $memoryBook->cover_photo_url
            ?? $booking->experience?->getCoverPhoto();
    @endphp

    @if ($coverPhoto)
        <img src="{{ $coverPhoto }}" alt="{{ $booking->experience_title_snapshot }}" class="hero-cover">
    @else
        <div class="hero-cover" style="background: linear-gradient(135deg, #1E3A2F 0%, #2D5240 100%);"></div>
    @endif

    <div class="hero-overlay"></div>
</div>
    </div>

    {{-- Hero content --}}
    <div class="hero-content">
        <div style="max-width:1100px; margin:0 auto; width:100%;">

            <p class="hero-eyebrow fade-up fade-up-1">Memory Book</p>

            <h1 class="hero-title fade-up fade-up-2">
                {{ $booking->experience_title_snapshot }}
            </h1>

            <div class="hero-divider fade-up fade-up-2">
                <div class="hero-divider-line"></div>
                <span class="hero-ornament">✦</span>
                <div class="hero-divider-line"></div>
            </div>

            @if ($booking->experience?->lokasi_nama || $booking->location_snapshot)
            <div class="hero-location fade-up fade-up-3">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $booking->experience?->lokasi_nama ?? $booking->location_snapshot }}
            </div>
            @endif

            {{-- Meta chips + quote --}}
            <div class="hero-meta-row fade-up fade-up-4">
                <div class="hero-meta-chips">

                    {{-- Tanggal --}}
                    <div class="hero-chip">
                        <div class="hero-chip-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div class="hero-chip-text">
                            <span class="hero-chip-label">Tanggal Experience</span>
                            <span class="hero-chip-value">
                                {{ \Carbon\Carbon::parse($booking->tanggal_experience)->locale('id')->isoFormat('D MMMM YYYY') }}
                            </span>
                        </div>
                    </div>

                    {{-- Traveler --}}
                    <div class="hero-chip">
                        <div class="hero-chip-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div class="hero-chip-text">
                            <span class="hero-chip-label">Wisatawan</span>
                            <span class="hero-chip-value">{{ $booking->user->name }}</span>
                        </div>
                    </div>

                    {{-- Host --}}
                    <div class="hero-chip">
                        <div class="hero-chip-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </div>
                        <div class="hero-chip-text">
                            <span class="hero-chip-label">Host Anda</span>
                            <span class="hero-chip-value">{{ $booking->host_name_snapshot }}</span>
                        </div>
                    </div>

                </div>

                {{-- Quote card --}}
                @if ($memoryBook->quote_highlight)
                <div class="hero-quote-card">
                    <div class="hero-quote-mark">"</div>
                    <p class="hero-quote-text">{{ $memoryBook->quote_highlight }}</p>
                    <p class="hero-quote-author">— {{ $booking->host_name_snapshot }}</p>
                </div>
                @endif

            </div>
        </div>
    </div>

</section>


{{-- ═══════════════════════════════════════════════════
     CERITA DARI HOST
═══════════════════════════════════════════════════ --}}
<section class="section host-story-section">
    <div class="container">
        <div class="host-story-grid">

            {{-- Teks kiri --}}
            <div>
                <p class="section-eyebrow">Cerita dari Host Anda</p>

                @if ($memoryBook->judul)
                    <h2 class="story-title">{{ $memoryBook->judul }}</h2>
                @else
                    <h2 class="story-title">Terima kasih, {{ explode(' ', $booking->user->name)[0] }}!</h2>
                @endif

                <div class="story-ornament">
                    <div class="story-ornament-line"></div>
                    <span style="color: var(--terracotta); font-size:0.85rem;">✦</span>
                </div>

                @if ($memoryBook->getDisplayMessage())
                    <p class="story-body">{{ $memoryBook->getDisplayMessage() }}</p>
                @endif

                {{-- Tanda tangan --}}
                <div class="signature-block">
                    <p class="signature-name">{{ $booking->host_name_snapshot }}</p>
                    <p class="signature-role">Host Anda</p>
                </div>
            </div>

            {{-- Foto kanan — ambil foto pertama dari memory book, fallback ke foto experience --}}
            @php
                $storyPhoto = $memoryBook->cover_photo_url
                    ?? $booking->experience?->getCoverPhoto();
            @endphp

            @if ($storyPhoto)
            <div>
                <img src="{{ $storyPhoto }}" alt="Foto bersama" class="story-photo">
            </div>
            @endif

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════
     GALLERY — MOMEN-MOMEN BERHARGA
═══════════════════════════════════════════════════ --}}
@if ($memoryBook->photos->count() > 0)
<section class="section gallery-section" x-data="galleryScroll()">
    <div class="container">

        <p class="section-eyebrow" style="text-align:center;">Moments to Remember</p>
        <h2 class="gallery-title">Momen-Momen Berharga</h2>

        <div class="gallery-ornament">
            <svg width="40" height="12" viewBox="0 0 40 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="6" cy="6" r="3" fill="#C4783A" opacity="0.4"/>
                <circle cx="20" cy="6" r="5" fill="#C4783A" opacity="0.6"/>
                <circle cx="34" cy="6" r="3" fill="#C4783A" opacity="0.4"/>
            </svg>
        </div>

        {{-- Scrollable gallery --}}
        <div class="gallery-scroll-wrapper">

            {{-- Nav buttons (desktop only) --}}
            @if ($memoryBook->photos->count() > 4)
            <button class="gallery-nav-btn gallery-nav-prev" x-on:click="scrollGallery(-1)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="gallery-nav-btn gallery-nav-next" x-on:click="scrollGallery(1)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
            @endif

            <div class="gallery-scroll-track" x-ref="scrollTrack">
                @foreach ($memoryBook->photos as $photo)
                <div class="gallery-photo-item"
                     x-on:click="openLightbox('{{ $photo->url }}')">
                    <img src="{{ $photo->url }}"
                         alt="Memory foto {{ $loop->iteration }}"
                         loading="lazy">
                </div>
                @endforeach
            </div>
        </div>

        @if ($memoryBook->photos->count() > 4)
        <div class="gallery-scroll-hint">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            Geser untuk melihat {{ $memoryBook->photos->count() }} foto lainnya
        </div>
        @endif

    </div>

    {{-- Lightbox --}}
    <div class="lightbox"
         x-show="lightboxOpen"
         x-on:click.self="lightboxOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display:none;">
        <button class="lightbox-close" x-on:click="lightboxOpen = false">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
        <img :src="lightboxSrc" alt="Preview foto">
    </div>

</section>
@endif


{{-- ═══════════════════════════════════════════════════
     BOTTOM: HIGHLIGHT + PESAN PENUTUP + CTA
═══════════════════════════════════════════════════ --}}
<section class="section bottom-section">
    <div class="container">
        <div class="bottom-grid">

            {{-- Kolom 1: Highlight hari itu --}}
            @if ($memoryBook->highlight_items && count($memoryBook->highlight_items) > 0)
            <div>
                <p class="highlight-title">Highlight Hari Itu</p>

                @foreach ($memoryBook->highlight_items as $item)
                <div class="highlight-item">
                    <div class="highlight-icon">
                        {{ $item['icon'] ?? '✦' }}
                    </div>
                    <div class="highlight-text">
                        <h4>{{ $item['judul'] ?? '' }}</h4>
                        <p>{{ $item['deskripsi'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            {{-- Fallback: kosongkan atau sembunyikan kolom ini --}}
            <div></div>
            @endif

            {{-- Kolom 2: Pesan Penutup --}}
            @if ($memoryBook->pesan_penutup)
            <div>
                <p class="closing-title">Pesan Penutup</p>
                <div class="closing-quote-mark">"</div>
                <p class="closing-text">{{ $memoryBook->pesan_penutup }}</p>
                <p class="closing-sign">— {{ $booking->host_name_snapshot }}</p>
            </div>
            @else
            <div></div>
            @endif

            {{-- Kolom 3: CTA --}}
            <div>
                <div class="cta-card">
                    <div class="cta-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C4783A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <p class="cta-title">Memory Book ini telah dikirim ke email kamu.</p>
                    <p class="cta-subtitle">Simpan kenangan ini dan bagikan kebahagiaanmu!</p>
                </div>

                {{-- Tombol tulis review --}}
                @if ($booking->status === 'completed')
                <div style="margin-top:1rem;">
                    <p style="font-size:0.75rem; color:var(--gray-text); text-align:center; margin-bottom:0.75rem;">Bagaimana pengalamanmu?</p>
                    <p style="font-size:0.8rem; color:var(--gray-text); text-align:center; margin-bottom:0.75rem;">Yuk tulis review untuk {{ $booking->host_name_snapshot }}</p>
                    <a href="/bookings/{{ $booking->kode_booking }}#review" class="btn-review">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        Tulis Review
                    </a>
                </div>
                @endif
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════
     FOOTER NOTE
═══════════════════════════════════════════════════ --}}
<div class="footer-note">
    <p>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C4783A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
        Dibuat dengan penuh cinta oleh <strong style="color:var(--green-dark); margin: 0 0.25rem;">{{ $booking->host_name_snapshot }}</strong> untuk <strong style="color:var(--green-dark); margin: 0 0.25rem;">{{ $booking->user->name }}</strong>
    </p>
</div>


{{-- Footer --}}
@include('components.shared.footer')

{{-- Alpine.js CDN --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    function galleryScroll() {
        return {
            lightboxOpen: false,
            lightboxSrc: '',

            scrollGallery(direction) {
                const track = this.$refs.scrollTrack;
                if (!track) return;
                const scrollAmount = 280 + 16; // item width + gap
                track.scrollBy({ left: direction * scrollAmount * 2, behavior: 'smooth' });
            },

            openLightbox(src) {
                this.lightboxSrc = src;
                this.lightboxOpen = true;
            }
        }
    }
</script>

</body>
</html>