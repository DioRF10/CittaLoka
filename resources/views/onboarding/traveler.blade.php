<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome to CittaLoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cream:        #F7F3ED;
            --cream-dark:   #EDE7DC;
            --forest:       #1E3A2F;
            --forest-mid:   #2D5240;
            --forest-light: #4A7C5F;
            --sand:         #C4A882;
            --sand-light:   #E8D5B7;
            --text:         #2C2C2C;
            --muted:        #7A7A6E;
            --white:        #FFFFFF;
            --fd: 'Cormorant Garamond', Georgia, serif;
            --fb: 'DM Sans', sans-serif;
        }

        html, body {
            min-height: 100vh;
            background: var(--cream);
            font-family: var(--fb);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        .wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* Card */
        .card {
            background: var(--white);
            border-radius: 24px;
            box-shadow: 0 4px 32px rgba(30,58,47,0.10);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
        }

        /* Dots */
        .dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            padding: 1.5rem 2rem 0;
        }
        .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--cream-dark);
            transition: all 0.3s ease;
        }
        .dot.active { width: 24px; border-radius: 4px; background: var(--forest); }
        .dot.done   { background: var(--forest-light); }

        /* Body */
        .body { padding: 1.75rem 2rem 1.5rem; }

        .title {
            font-family: var(--fd);
            font-size: 1.9rem;
            font-weight: 500;
            color: var(--forest);
            line-height: 1.2;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            font-size: 0.875rem;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        /* Nav */
        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem 1.75rem;
        }

        .btn-back {
            font-size: 0.875rem;
            color: var(--muted);
            background: none;
            border: none;
            cursor: pointer;
            font-family: var(--fb);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .btn-back:hover { color: var(--text); }

        .btn-next {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.75rem 1.5rem;
            background: var(--forest);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-family: var(--fb);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(30,58,47,0.25);
        }
        .btn-next:hover    { background: var(--forest-mid); transform: translateY(-1px); }
        .btn-next:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        /* Step 1 — Hero */
        .hero {
            height: 180px;
            background: linear-gradient(135deg, #1E3A2F 0%, #4A7C5F 50%, #C4A882 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
        }
        .feat-icon {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--cream);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* Step 2 — Language */
        .lang-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .lang-card {
            border: 2px solid var(--cream-dark);
            border-radius: 12px;
            padding: 1.25rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .lang-card:hover    { border-color: var(--forest-light); background: #F0F5F2; }
        .lang-card.selected { border-color: var(--forest); background: #EBF1ED; }

        .check {
            position: absolute; top: 8px; right: 8px;
            width: 18px; height: 18px;
            border-radius: 50%;
            background: var(--forest);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transform: scale(0.5);
            transition: all 0.2s;
        }
        .lang-card.selected .check { opacity: 1; transform: scale(1); }

        .flag  { font-size: 2.25rem; display: block; margin-bottom: 0.4rem; }
        .lname { font-family: var(--fd); font-size: 1rem; font-weight: 500; color: var(--forest); display: block; }
        .ldesc { font-size: 0.72rem; color: var(--muted); }

        /* Step 3 — Avatar */
        .avatar-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .avatar-circle {
            width: 96px; height: 96px;
            border-radius: 50%;
            border: 2px dashed var(--sand);
            background: var(--cream);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.2s;
        }
        .avatar-circle:hover { border-color: var(--forest-light); }
        .avatar-circle img   { width: 100%; height: 100%; object-fit: cover; }
        .avatar-hint {
            font-size: 0.75rem;
            color: var(--muted);
            margin-top: 0.5rem;
        }

        /* Step 4 — Completion */
        .completion {
            padding: 2.5rem 2rem;
            text-align: center;
        }
        .completion-icon {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--forest), var(--forest-light));
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.25rem;
            box-shadow: 0 8px 24px rgba(30,58,47,0.25);
            animation: popIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        @keyframes popIn {
            from { transform: scale(0); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
        .btn-full {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            width: 100%; padding: 0.875rem;
            background: var(--forest); color: var(--white);
            border: none; border-radius: 8px;
            font-family: var(--fb); font-size: 0.9rem; font-weight: 500;
            cursor: pointer; transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(30,58,47,0.25);
        }
        .btn-full:hover    { background: var(--forest-mid); transform: translateY(-1px); }
        .btn-full:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        /* Animations */
        [x-cloak] { display: none !important; }
        .step-in { animation: slideIn 0.3s ease forwards; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(16px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Spinner */
        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card" x-data="onboardingTraveler()" x-init="init()">

        {{-- Dots --}}
        <div class="dots" x-show="step <= 3">
            <template x-for="i in 3" :key="i">
                <div class="dot" :class="{ active: step === i, done: step > i }"></div>
            </template>
        </div>

        {{-- ── Step 1: Welcome ─────────────────────────────────────── --}}
        <div x-show="step === 1" x-cloak class="step-in">
            <div class="hero">🌿</div>
            <div class="body">
                <h1 class="title">Welcome to<br>CittaLoka</h1>
                <p class="subtitle">Your gateway to authentic Balinese culture and genuine local connections.</p>

                <div class="feature">
                    <div class="feat-icon">🏛️</div>
                    <span>Discover <strong>authentic</strong> Balinese culture beyond tourist traps</span>
                </div>
                <div class="feature">
                    <div class="feat-icon">🤝</div>
                    <span>Meet <strong>local hosts</strong> who share their real lives and traditions</span>
                </div>
                <div class="feature">
                    <div class="feat-icon">✨</div>
                    <span>Create <strong>meaningful memories</strong> that last a lifetime</span>
                </div>
            </div>
            <div class="nav" style="justify-content: flex-end;">
                <button class="btn-next" @click="step = 2">Get Started →</button>
            </div>
        </div>

        {{-- ── Step 2: Language ────────────────────────────────────── --}}
        <div x-show="step === 2" x-cloak class="step-in">
            <div class="body">
                <h1 class="title">Choose Your Language</h1>
                <p class="subtitle">We'll show experiences and content in your preferred language.</p>

                <div class="lang-grid">
                    <div class="lang-card" :class="{ selected: locale === 'id' }" @click="locale = 'id'">
                        <div class="check">
                            <svg width="10" height="8" viewBox="0 0 10 8" fill="none">
                                <path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="flag">🇮🇩</span>
                        <span class="lname">Bahasa Indonesia</span>
                        <span class="ldesc">Standard Indonesian</span>
                    </div>
                    <div class="lang-card" :class="{ selected: locale === 'en' }" @click="locale = 'en'">
                        <div class="check">
                            <svg width="10" height="8" viewBox="0 0 10 8" fill="none">
                                <path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="flag">🇬🇧</span>
                        <span class="lname">English</span>
                        <span class="ldesc">Global Standard</span>
                    </div>
                </div>
            </div>
            <div class="nav">
                <button class="btn-back" @click="step = 1">← Back</button>
                <button class="btn-next" :disabled="!locale || saving" @click="saveStep(2)">
                    <span x-show="saving" class="spinner"></span>
                    <span x-show="!saving">Continue →</span>
                </button>
            </div>
        </div>

        {{-- ── Step 3: Foto Profil ─────────────────────────────────── --}}
        <div x-show="step === 3" x-cloak class="step-in">
            <div class="body">
                <h1 class="title">Add a Profile Photo</h1>
                <p class="subtitle">Optional — helps hosts recognise you. You can always add one later from your profile.</p>

                <div class="avatar-wrap">
                    <div class="avatar-circle" @click="$refs.avatarInput.click()">
                        <template x-if="preview">
                            <img :src="preview" alt="Preview">
                        </template>
                        <template x-if="!preview">
                            <span style="font-size: 2rem; color: var(--sand);">📷</span>
                        </template>
                    </div>
                    <span class="avatar-hint">Tap to upload (max 2MB)</span>
                    <input type="file" x-ref="avatarInput" accept="image/*" style="display:none" @change="handleAvatar($event)">
                </div>
            </div>
            <div class="nav">
                <button class="btn-back" @click="step = 2">← Back</button>
                <button class="btn-next" :disabled="saving" @click="saveStep(3)">
                    <span x-show="saving" class="spinner"></span>
                    <span x-show="!saving" x-text="preview ? 'Save & Continue →' : 'Skip →'"></span>
                </button>
            </div>
        </div>

        {{-- ── Step 4: Completion ──────────────────────────────────── --}}
        <div x-show="step === 4" x-cloak>
            <div class="completion">
                <div class="completion-icon">🌿</div>
                <h1 class="title">Your journey begins here</h1>
                <p class="subtitle" style="margin-bottom: 1.5rem;">
                    Welcome to CittaLoka. Authentic Bali awaits you.
                </p>
                <button class="btn-full" :disabled="saving" @click="complete()">
                    <span x-show="saving" class="spinner"></span>
                    <span x-show="!saving">Explore Experiences →</span>
                </button>
            </div>
        </div>

    </div>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function onboardingTraveler() {
    return {
        step:    1,
        saving:  false,
        locale:  '{{ auth()->user()->locale ?? "" }}',
        preview: null,
        file:    null,

        init() {},

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

                const res  = await fetch('{{ route("onboarding.traveler.save") }}', {
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

        async complete() {
            this.saving = true;
            try {
                const fd = new FormData();
                fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                fd.append('step', 'complete');

                const res  = await fetch('{{ route("onboarding.traveler.save") }}', {
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