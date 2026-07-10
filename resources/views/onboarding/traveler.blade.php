<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome to CittaLoka</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,400&family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --green-dark: #1A2E1C;
            --green-mid: #1E3A2F;
            --terracotta: #C4783A;
            --cream: #F7F3ED;
            --cream-light: #FAFAF8;
            --gray-text: #6B7280;
            --gray-border: #E8E4DC;
            --success-bg: #EBF5EE;
            --success-text: #2D5240;
            --success-border: #B8DFC8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream-light);
            color: #1C1C1C;
            margin: 0;
        }

        [x-cloak] {
            display: none !important;
        }

        /* ── Shell Layout ── */
        .onboarding-shell {
            display: grid;
            grid-template-columns: 420px 1fr;
            min-height: 100vh;
        }

        @media (max-width: 960px) {
            .onboarding-shell {
                grid-template-columns: 1fr;
            }
        }

        /* ── Left Panel — Full Photo ── */
        .left-panel {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        @media (max-width: 960px) {
            .left-panel {
                display: none;
            }
        }

        /* Background photo that switches per step */
        .left-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.6s ease;
            z-index: 1;
        }

        /* Dark overlay gradient */
        .left-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(15, 27, 17, 0.22) 0%,
                rgba(15, 27, 17, 0.88) 100%
            );
            z-index: 2;
        }

        /* Logo top-left */
        .left-logo {
            position: absolute;
            top: 2.5rem;
            left: 2.5rem;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .left-logo-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--terracotta);
            flex-shrink: 0;
        }

        .left-logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.01em;
            line-height: 1;
        }

        /* Bottom text content */
        .left-content {
            position: relative;
            z-index: 3;
            padding: 2.5rem;
        }

        .left-eyebrow {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--terracotta);
            margin-bottom: 0.6rem;
            display: block;
        }

        .left-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.4rem;
            font-weight: 500;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 0.85rem;
        }

        .left-desc {
            font-size: 0.88rem;
            color: rgba(255, 255, 255, 0.78);
            line-height: 1.65;
            font-style: italic;
        }

        /* Info box (glass-style) */
        .left-info-box {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            padding: 0.9rem 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .left-info-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(196, 120, 58, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
        }

        .left-info-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.2rem;
        }

        .left-info-text {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.5;
        }

        /* Benefit list */
        .left-benefit-list {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .left-benefit-item {
            display: flex;
            gap: 0.7rem;
            align-items: flex-start;
        }

        .left-benefit-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(196, 120, 58, 0.25);
            border: 1.5px solid rgba(196, 120, 58, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 0.1rem;
            font-size: 0.7rem;
            color: var(--terracotta);
            font-weight: 700;
        }

        .left-benefit-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.1rem;
        }

        .left-benefit-desc {
            font-size: 0.78rem;
            color: rgba(255,255,255,0.65);
            line-height: 1.4;
        }

        /* ── Right Panel ── */
        .right-panel {
            padding: 2rem 3rem 4rem;
            max-width: 980px;
        }

        @media (max-width: 900px) {
            .right-panel {
                padding: 1.5rem 1.25rem 3rem;
            }
        }



        /* ── Form Card ── */
        .form-card {
            background: #fff;
            border: 1px solid var(--gray-border);
            border-radius: 18px;
            padding: 2.25rem 2.5rem;
        }

        @media (max-width: 640px) {
            .form-card {
                padding: 1.5rem 1.25rem;
            }
        }

        .form-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--green-dark);
            margin: 0 0 0.3rem;
        }

        .form-subtitle {
            font-size: 0.85rem;
            color: var(--gray-text);
            line-height: 1.5;
            margin: 0 0 1.75rem;
        }

        .field-group {
            margin-bottom: 1.5rem;
        }

        .field-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .field-hint {
            font-size: 0.75rem;
            color: var(--gray-text);
            margin-top: 0.35rem;
        }

        /* ── Radio Cards (Bahasa) ── */
        .radio-card-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        @media (max-width: 640px) {
            .radio-card-grid {
                grid-template-columns: 1fr;
            }
        }

        .radio-card {
            border: 1.5px solid var(--gray-border);
            border-radius: 12px;
            padding: 1.1rem;
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
        }

        .radio-card:hover {
            border-color: #C4BEB1;
        }

        .radio-card.selected {
            border-color: var(--green-dark);
            background: var(--cream-light);
        }

        .radio-card-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid var(--gray-border);
            margin-bottom: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .radio-card.selected .radio-card-dot {
            border-color: var(--green-dark);
        }

        .radio-card-dot-inner {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--green-dark);
        }

        .radio-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 0.3rem;
        }

        .radio-card-desc {
            font-size: 0.78rem;
            color: var(--gray-text);
            line-height: 1.45;
        }

        .badge-recommended {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--terracotta);
            background: #FDF6EE;
            padding: 0.15rem 0.5rem;
            border-radius: 999px;
            margin-top: 0.5rem;
        }

        /* ── Avatar Upload ── */
        .avatar-upload-row {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .avatar-preview {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            border: 1.5px solid var(--gray-border);
            cursor: pointer;
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-upload-photo {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--green-dark);
            background: #fff;
            border: 1.5px solid var(--gray-border);
            padding: 0.55rem 1.1rem;
            border-radius: 9px;
            cursor: pointer;
        }

        /* ── Tip Box ── */
        .tip-box {
            background: var(--cream);
            border-radius: 12px;
            padding: 1rem 1.1rem;
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .tip-icon {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            background: var(--cream-light);
            flex-shrink: 0;
            position: relative;
        }

        .tip-icon::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green-dark);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .tip-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 0.2rem;
        }

        .tip-text {
            font-size: 0.79rem;
            color: var(--gray-text);
            line-height: 1.5;
        }

        /* ── Welcome benefit list ── */
        .welcome-benefit-list {
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .welcome-benefit-item {
            display: flex;
            gap: 0.85rem;
            align-items: flex-start;
        }

        .welcome-benefit-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, #F7F5F0 0%, #F3F6F1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            flex-shrink: 0;
        }

        .welcome-benefit-icon::before {
            content: '';
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--green-dark);
            opacity: 0.22;
        }

        .welcome-benefit-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 0.15rem;
        }

        .welcome-benefit-desc {
            font-size: 0.78rem;
            color: var(--gray-text);
            line-height: 1.45;
        }

        /* ── Footer Actions ── */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .btn-back {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.7rem 1.4rem;
            border-radius: 10px;
            border: 1.5px solid var(--gray-border);
            background: #fff;
            color: #374151;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-next {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            border-radius: 10px;
            border: none;
            background: var(--green-dark);
            color: #fff;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-next:hover {
            background: var(--green-mid);
        }

        .btn-next:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .avatar-placeholder {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(30, 58, 47, 0.12), rgba(196, 120, 58, 0.18));
            box-shadow: inset 0 0 0 1px rgba(30, 58, 47, 0.08);
        }

        .avatar-preview:hover {
            border-color: var(--green-mid);
        }

        .completion-icon {
            width: 88px;
            height: 88px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            border: 2px dashed rgba(30, 58, 47, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .completion-icon::before {
            content: '✓';
            font-size: 2rem;
            color: var(--green-dark);
            font-weight: 700;
        }

        .spinner {
            display: inline-block;
            width: 13px;
            height: 13px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── Completion ── */
        .completion-wrap {
            text-align: center;
            padding: 1rem 0;
        }

        .completion-icon {
            width: 88px;
            height: 88px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            border: 2px dashed rgba(30, 58, 47, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .completion-icon::before {
            content: '✓';
            font-size: 2rem;
            color: var(--green-dark);
            font-weight: 700;
        }

        .completion-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            color: var(--green-dark);
            margin-bottom: 0.6rem;
        }

        .completion-sub {
            font-size: 0.9rem;
            color: var(--gray-text);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        /* ── Floating WhatsApp Help ── */
        .floating-help {
            position: fixed;
            bottom: 1.75rem;
            right: 1.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            border: 1.5px solid var(--gray-border);
            border-radius: 999px;
            padding: 0.65rem 1.1rem 0.65rem 0.65rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: var(--green-dark);
            font-size: 0.82rem;
            font-weight: 600;
            z-index: 40;
            transition: transform 0.15s;
        }

        .floating-help:hover {
            transform: translateY(-2px);
        }

        .floating-help-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #25D366;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        @media (max-width: 640px) {
            .floating-help span:last-child {
                display: none;
            }

            .floating-help {
                padding: 0.65rem;
            }
        }
    </style>
</head>

<body>

    <div class="onboarding-shell" x-data="onboardingTraveler()" x-cloak>

        {{-- ═══════════════════════════════════════════════
        LEFT PANEL — Full Photo with per-step content
        ═══════════════════════════════════════════════ --}}
        <div class="left-panel">

            {{-- Background photo — changes per step --}}
            <img x-show="step === 1" class="left-bg" src="{{ asset('images/onboarding-step1.png') }}" alt="Bali rice terrace">
            <img x-show="step === 2" class="left-bg" src="{{ asset('images/onboarding-step2.png') }}" alt="Local conversation">
            <img x-show="step === 3" class="left-bg" src="{{ asset('images/onboarding-step3.png') }}" alt="Traveler portrait">
            <img x-show="step === 4" class="left-bg" src="{{ asset('images/onboarding-step4.png') }}" alt="Soul journey">
            <img x-show="step === 5" class="left-bg" src="{{ asset('images/auth/travelling.png') }}" alt="Bali journey">

            {{-- Dark overlay --}}
            <div class="left-overlay"></div>

            {{-- Logo top-left --}}
            <div class="left-logo">
                <div class="left-logo-dot"></div>
                <span class="left-logo-text">CittaLoka</span>
            </div>

            {{-- Step 1 content --}}
            <template x-if="step === 1">
                <div class="left-content">
                    <span class="left-eyebrow">Living Culture Platform</span>
                    <h1 class="left-title">Jelajahi Bali<br>dari dalam</h1>
                    <p class="left-desc">Temukan pengalaman budaya otentik bersama komunitas lokal Bali — terhubung dengan orang nyata, tradisi bermakna, dan kenangan yang bertahan seumur hidup.</p>

                    <div class="left-benefit-list" style="margin-top:1.25rem;">
                        <div class="left-benefit-item">
                            <div class="left-benefit-dot">✦</div>
                            <div>
                                <div class="left-benefit-title">Pengalaman Otentik</div>
                                <div class="left-benefit-desc">Jelajahi Bali jauh di luar tempat wisata biasa.</div>
                            </div>
                        </div>
                        <div class="left-benefit-item">
                            <div class="left-benefit-dot">✦</div>
                            <div>
                                <div class="left-benefit-title">Koneksi Lokal</div>
                                <div class="left-benefit-desc">Bertemu orang yang menjalani budaya itu setiap hari.</div>
                            </div>
                        </div>
                        <div class="left-benefit-item">
                            <div class="left-benefit-dot">✦</div>
                            <div>
                                <div class="left-benefit-title">Kenangan Bermakna</div>
                                <div class="left-benefit-desc">Ciptakan cerita yang layak untuk dikenang.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Step 2 content --}}
            <template x-if="step === 2">
                <div class="left-content">
                    <span class="left-eyebrow">Langkah 2 dari 4</span>
                    <h1 class="left-title">Bahasa<br>pilihanmu</h1>
                    <p class="left-desc">Kami akan menampilkan experience dan konten dalam bahasa yang paling nyaman buat kamu.</p>
                    <div class="left-info-box" style="margin-top:1.25rem;">
                        <div class="left-info-icon">🌐</div>
                        <div>
                            <div class="left-info-title">Bisa diganti kapan saja</div>
                            <div class="left-info-text">Kamu selalu bisa mengubah preferensi bahasa dari halaman pengaturan akun.</div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Step 3 content --}}
            <template x-if="step === 3">
                <div class="left-content">
                    <span class="left-eyebrow">Langkah 3 dari 4</span>
                    <h1 class="left-title">Tunjukkan<br>dirimu</h1>
                    <p class="left-desc">Foto profil membantu host mengenalimu saat kalian bertemu langsung untuk sebuah experience.</p>
                    <div class="left-info-box" style="margin-top:1.25rem;">
                        <div class="left-info-icon">📸</div>
                        <div>
                            <div class="left-info-title">Opsional, tapi disarankan</div>
                            <div class="left-info-text">Kamu bisa melewati langkah ini dan menambahkan foto kapan saja dari profil nanti.</div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Step 4 content — Soul Match --}}
            <template x-if="step === 4">
                <div class="left-content">
                    <span class="left-eyebrow">Langkah 4 dari 4</span>
                    <h1 class="left-title">Temukan Jiwa<br>Perjalananmu</h1>
                    <p class="left-desc">Setiap traveler berbeda. Ada yang mencari petualangan, ketenangan, atau percakapan bermakna.</p>
                    <div class="left-info-box" style="margin-top:1.25rem;">
                        <div class="left-info-icon">✨</div>
                        <div>
                            <div class="left-info-title">2 menit saja</div>
                            <div class="left-info-text">Soul Match merekomendasikan host Bali yang paling cocok dengan caramu menjelajah.</div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Step 5 Completion --}}
            <template x-if="step === 5">
                <div class="left-content">
                    <span class="left-eyebrow">Selesai 🎉</span>
                    <h1 class="left-title">Perjalananmu<br>dimulai di sini</h1>
                    <p class="left-desc">Selamat datang di komunitas CittaLoka. Bali yang otentik menantimu.</p>
                </div>
            </template>

        </div>

        {{-- ═══════════════════════════════════════════════
        RIGHT PANEL — form per step
        ═══════════════════════════════════════════════ --}}
        <div class="right-panel">



            {{-- ═══════════════════ STEP 1: WELCOME ═══════════════════ --}}
            <div x-show="step === 1" class="form-card">
                <h2 class="form-title">Selamat datang di CittaLoka! 🌿</h2>
                <p class="form-subtitle">Sebelum mulai menjelajah, mari atur beberapa preferensi kecil supaya
                    pengalamanmu lebih personal.</p>

                <div class="tip-box">
                    <span class="tip-icon"></span>
                    <div>
                        <div class="tip-title">Kami percaya perjalanan terbaik dimulai dari koneksi</div>
                        <div class="tip-text">Dari kelas memasak tradisional, ritual spiritual, hingga kerajinan
                            tangan — setiap experience di CittaLoka dipandu langsung oleh host lokal.</div>
                    </div>
                </div>

                <div class="form-actions">
                    <div></div>
                    <button class="btn-next" type="button" @click="step = 2">
                        Mulai
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════ STEP 2: BAHASA ═══════════════════ --}}
            <div x-show="step === 2" class="form-card">
                <h2 class="form-title">Pilih Bahasamu</h2>
                <p class="form-subtitle">Kami akan menampilkan experience dan konten dalam bahasa pilihanmu.</p>

                <div class="field-group">
                    <div class="radio-card-grid">
                        <div class="radio-card" :class="{ selected: locale === 'id' }" @click="locale = 'id'">
                            <div class="radio-card-dot">
                                <div class="radio-card-dot-inner" x-show="locale === 'id'"></div>
                            </div>
                            <div class="radio-card-title">Bahasa Indonesia</div>
                            <div class="radio-card-desc">Tampilkan semua konten dalam Bahasa Indonesia.</div>
                            <span class="badge-recommended">Disarankan</span>
                        </div>
                        <div class="radio-card" :class="{ selected: locale === 'en' }" @click="locale = 'en'">
                            <div class="radio-card-dot">
                                <div class="radio-card-dot-inner" x-show="locale === 'en'"></div>
                            </div>
                            <div class="radio-card-title">English</div>
                            <div class="radio-card-desc">Show all content in English.</div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn-back" type="button" @click="step = 1">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Kembali
                    </button>
                    <button class="btn-next" type="button" :disabled="!locale || saving" @click="saveStep(2)">
                        <span x-show="saving" class="spinner"></span>
                        <span x-show="!saving">Lanjutkan</span>
                        <svg x-show="!saving" width="15" height="15" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════ STEP 3: FOTO PROFIL ═══════════════════ --}}
            <div x-show="step === 3" class="form-card">
                <h2 class="form-title">Tambahkan Foto Profil</h2>
                <p class="form-subtitle">Opsional — membantu host mengenalimu. Kamu selalu bisa menambahkannya
                    nanti dari profil.</p>

                <div class="field-group">
                    <div class="avatar-upload-row">
                        <div class="avatar-preview" @click="$refs.avatarInput.click()">
                            <img x-show="preview" :src="preview" alt="Preview">
                            <span x-show="!preview" class="avatar-placeholder"></span>
                        </div>
                        <div>
                            <button type="button" class="btn-upload-photo" @click="$refs.avatarInput.click()">
                                Pilih Foto
                            </button>
                            <p class="field-hint" style="margin-top:0.4rem;">JPG/PNG, maks 2MB</p>
                            <input type="file" x-ref="avatarInput" accept="image/jpeg,image/png" style="display:none;"
                                @change="handleAvatar($event)">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn-back" type="button" @click="step = 2">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Kembali
                    </button>
                    <button class="btn-next" type="button" :disabled="saving" @click="saveStep(3)">
                        <span x-show="saving" class="spinner"></span>
                        <span x-show="!saving" x-text="preview ? 'Simpan & Lanjutkan' : 'Lewati'"></span>
                        <svg x-show="!saving" width="15" height="15" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════ STEP 4: SOUL MATCH TEASE ═══════════════════ --}}
            <div x-show="step === 4" class="form-card">
                <h2 class="form-title">Temukan Soul Type-mu</h2>
                <p class="form-subtitle">Ambil 2 menit buat kenalan sama dirimu sendiri — kami rekomendasikan host
                    Bali yang paling nyambung sama caramu menjelajah.</p>

                <div class="tip-box">
                    <span class="tip-icon"></span>
                    <div>
                        <div class="tip-title">Ada yang cari petualangan, ada yang cari ketenangan</div>
                        <div class="tip-text">Soul Match membaca preferensimu lewat 18 pertanyaan singkat, lalu
                            mencocokkanmu dengan host yang paling relevan.</div>
                    </div>
                </div>

                <div class="form-actions" style="flex-direction:column; gap:0.75rem; align-items:stretch;">
                    <button class="btn-next" type="button" style="justify-content:center; width:100%;"
                        :disabled="saving" @click="startSoulMatch()">
                        <span x-show="saving" class="spinner"></span>
                        <span x-show="!saving">Mulai Soul Match</span>
                        <svg x-show="!saving" width="15" height="15" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                    <button type="button" style="background:none; border:none; color:var(--gray-text);
                        font-size:0.85rem; text-decoration:underline; cursor:pointer; padding:0.4rem;"
                        :disabled="saving" @click="step = 5">
                        Lewati dulu
                    </button>
                </div>
            </div>

            {{-- ═══════════════════ STEP 5: COMPLETION ═══════════════════ --}}
            <div x-show="step === 5" class="form-card completion-wrap">
                <div class="completion-icon"></div>
                <h2 class="completion-title">Perjalananmu dimulai di sini</h2>
                <p class="completion-sub">Selamat datang di CittaLoka. Bali yang otentik menantimu.</p>

                <button class="btn-next" type="button" style="margin:0 auto;" :disabled="saving" @click="complete()">
                    <span x-show="saving" class="spinner"></span>
                    <span x-show="!saving">Jelajahi Experience</span>
                    <svg x-show="!saving" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Floating WhatsApp Help --}}
    <a href="https://wa.me/6281234567890" target="_blank" class="floating-help">
        <span class="floating-help-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="#fff">
                <path
                    d="M17.6 6.32A8.86 8.86 0 0 0 12 4a8.94 8.94 0 0 0-7.92 13.08L3 21l3.92-1.04A8.94 8.94 0 0 0 12 21a8.94 8.94 0 0 0 8.94-9 8.86 8.86 0 0 0-3.34-5.68zM12 19.4a7.4 7.4 0 0 1-3.78-1.04l-.27-.16-2.32.62.62-2.26-.18-.28A7.43 7.43 0 1 1 19.4 12 7.43 7.43 0 0 1 12 19.4z" />
            </svg>
        </span>
        <span>Butuh bantuan?</span>
    </a>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function onboardingTraveler() {
            return {
                step: 1,
                stepLabels: ['Welcome', 'Bahasa', 'Foto Profil', 'Soul Match'],
                saving: false,
                locale: '{{ auth()->user()->locale ?? "" }}',
                preview: null,
                file: null,

                handleAvatar(e) {
                    const f = e.target.files[0];
                    if (!f) return;
                    if (f.size > 2 * 1024 * 1024) { alert('Ukuran foto maksimal 2MB.'); return; }
                    this.file = f;
                    const reader = new FileReader();
                    reader.onload = (ev) => { this.preview = ev.target.result; };
                    reader.readAsDataURL(f);
                },

                async saveStep(stepNum) {
                    this.saving = true;
                    try {
                        const fd = new FormData();
                        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        fd.append('step', stepNum);

                        if (stepNum === 2) fd.append('locale', this.locale);
                        if (stepNum === 3 && this.file) fd.append('avatar', this.file);

                        const res = await fetch('{{ route("onboarding.traveler.save") }}', {
                            method: 'POST', body: fd,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        if (data.success) this.step++;
                        else alert('Terjadi kesalahan. Coba lagi.');
                    } catch (e) {
                        alert('Network error. Periksa koneksimu.');
                    } finally {
                        this.saving = false;
                    }
                },

                async startSoulMatch() {
                    this.saving = true;
                    try {
                        const fd = new FormData();
                        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        fd.append('step', 'complete');

                        const res = await fetch('{{ route("onboarding.traveler.save") }}', {
                            method: 'POST', body: fd,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        if (data.success) window.location.href = '{{ route("soul-match.intro") }}';
                    } catch (e) {
                        alert('Network error.');
                    } finally {
                        this.saving = false;
                    }
                },

                async complete() {
                    this.saving = true;
                    try {
                        const fd = new FormData();
                        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        fd.append('step', 'complete');

                        const res = await fetch('{{ route("onboarding.traveler.save") }}', {
                            method: 'POST', body: fd,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        if (data.success) window.location.href = data.redirect;
                    } catch (e) {
                        alert('Network error.');
                    } finally {
                        this.saving = false;
                    }
                },
            }
        }
    </script>
</body>

</html>