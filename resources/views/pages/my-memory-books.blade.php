<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Memory Books | CittaLoka</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --green-dark: #1E3A2F;
            --terracotta: #C4783A;
            --cream: #F7F3ED;
            --cream-light: #FAFAF8;
            --gray-text: #6B7280;
            --gray-border: #E8E4DC;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream-light);
            color: #1C1C1C;
        }

        .page-header {
            padding: 3rem 2rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-eyebrow {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--terracotta);
            margin-bottom: 0.5rem;
        }

        .page-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 4vw, 2.75rem);
            font-weight: 500;
            color: var(--green-dark);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
            color: var(--gray-text);
        }

        .grid-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 4rem;
        }

        .memory-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        @media (max-width: 1024px) {
            .memory-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .memory-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── Card ── */
        .memory-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--gray-border);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .memory-card.is-sent:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.1);
        }

        .memory-card.is-pending {
            cursor: default;
        }

        .card-image-wrap {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
            background: linear-gradient(135deg, #1E3A2F 0%, #2D5240 100%);
        }

        .card-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-image-wrap.is-pending img {
            filter: grayscale(0.4) brightness(0.85);
        }

        .card-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            backdrop-filter: blur(6px);
        }

        .badge-sent {
            background: rgba(30, 58, 47, 0.85);
            color: #fff;
        }

        .badge-pending {
            background: rgba(255, 255, 255, 0.9);
            color: var(--terracotta);
        }

        .card-overlay-icon {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(10, 20, 12, 0.25);
        }

        .card-overlay-icon svg {
            opacity: 0.85;
        }

        .card-body {
            padding: 1.1rem 1.25rem 1.25rem;
        }

        .card-exp-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.15rem;
            font-weight: 500;
            color: var(--green-dark);
            line-height: 1.3;
            margin-bottom: 0.4rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-meta {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            color: var(--gray-text);
            margin-bottom: 0.3rem;
        }

        .card-host {
            font-size: 0.78rem;
            color: var(--gray-text);
        }

        .card-status-text {
            margin-top: 0.6rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-sent-text {
            color: var(--green-dark);
        }

        .status-pending-text {
            color: var(--terracotta);
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            color: var(--green-dark);
            margin-bottom: 0.5rem;
        }

        .empty-subtitle {
            font-size: 0.875rem;
            color: var(--gray-text);
            margin-bottom: 1.5rem;
        }

        .btn-explore {
            display: inline-flex;
            padding: 0.65rem 1.5rem;
            background: var(--green-dark);
            color: #fff;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-explore:hover {
            background: #2D5240;
        }
    </style>
</head>

<body>

    @include('components.shared.navbar')

    <div class="page-header">
        <p class="page-eyebrow">Kenangan Anda</p>
        <h1 class="page-title">My Memory Books</h1>
        <p class="page-subtitle">A collection of stories and memories from every experience you've had in Bali.</p>
    </div>

    <div class="grid-container">

        @if ($bookings->isEmpty())

            <div class="empty-state">
                <div class="empty-icon">📖</div>
                <div class="empty-title">Belum ada Memory Book</div>
                <p class="empty-subtitle">Memory Book akan muncul di sini setelah experience-mu selesai dan host mengirimkan
                    kenangannya.</p>
                <a href="/experiences" class="btn-explore">Jelajahi Experience</a>
            </div>

        @else

                <div class="memory-grid">
                    @foreach ($bookings as $booking)
                        @php
                            $mb = $booking->memoryBook;
                            $isSent = $mb && $mb->status === 'sent';

                            $coverPhoto = $isSent && $mb->cover_photo_url
                                ? $mb->cover_photo_url
                                : $booking->experience?->getCoverPhoto();
                        @endphp

                        @if ($isSent)
                            <a href="{{ route('memory-book.show', $booking->kode_booking) }}" class="memory-card is-sent">
                        @else
                                <div class="memory-card is-pending">
                            @endif

                                <div class="card-image-wrap {{ $isSent ? '' : 'is-pending' }}">
                                    @if ($coverPhoto)
                                        <img src="{{ $coverPhoto }}" alt="{{ $booking->experience_title_snapshot }}">
                                    @endif

                                    @if ($isSent)
                                        <span class="card-badge badge-sent">Sudah Dikirim</span>
                                    @else
                                        <span class="card-badge badge-pending">Menunggu Host</span>
                                        <div class="card-overlay-icon">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <div class="card-exp-title">{{ $booking->experience_title_snapshot }}</div>
                                    <div class="card-meta">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                            <line x1="16" y1="2" x2="16" y2="6" />
                                            <line x1="8" y1="2" x2="8" y2="6" />
                                            <line x1="3" y1="10" x2="21" y2="10" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($booking->tanggal_experience)->locale('id')->isoFormat('D MMM YYYY') }}
                                    </div>
                                    <div class="card-host">Host: {{ $booking->host_name_snapshot }}</div>

                                    @if ($isSent)
                                        <div class="card-status-text status-sent-text">Klik untuk melihat kenangan ✦</div>
                                    @else
                                        <div class="card-status-text status-pending-text">Host sedang menyiapkan kenanganmu</div>
                                    @endif
                                </div>

                                @if ($isSent)
                                    </a>
                                @else
                            </div>
                        @endif

                    @endforeach
            </div>

        @endif

    </div>

    @include('components.shared.footer')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>

</html>