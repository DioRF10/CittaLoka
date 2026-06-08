<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Host Setup — CittaLoka</title>
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
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 1rem 4rem;
        }

        /* Card */
        .card {
            background: var(--white);
            border-radius: 24px;
            box-shadow: 0 4px 32px rgba(30,58,47,0.10);
            width: 100%;
            max-width: 520px;
            margin-top: 1rem;
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
            font-size: 1.85rem;
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
            border: 1.5px solid var(--cream-dark);
            border-radius: 8px;
            padding: 0.65rem 1.25rem;
            cursor: pointer;
            font-family: var(--fb);
            transition: all 0.2s;
        }
        .btn-back:hover { border-color: var(--forest-light); color: var(--text); }

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

        /* Form */
        .form-group { margin-bottom: 1rem; }
        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.35rem;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.65rem 0.875rem;
            border: 1.5px solid var(--cream-dark);
            border-radius: 8px;
            font-family: var(--fb);
            font-size: 0.875rem;
            color: var(--text);
            background: var(--white);
            outline: none;
            transition: border-color 0.2s;
            appearance: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--forest);
        }
        .form-textarea { resize: vertical; min-height: 90px; line-height: 1.6; }
        .select-wrap { position: relative; }
        .select-wrap::after {
            content: '▾';
            position: absolute; right: 0.875rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); pointer-events: none; font-size: 0.75rem;
        }
        .char-count { font-size: 0.7rem; color: var(--muted); text-align: right; margin-top: 0.2rem; }

        /* Step 1 — Welcome */
        .welcome-icon {
            width: 64px; height: 64px;
            border-radius: 14px;
            background: var(--cream);
            border: 1.5px solid var(--sand-light);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.25rem;
        }
        .checklist { margin-bottom: 1.25rem; }
        .check-item {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            margin-bottom: 0.7rem;
            font-size: 0.875rem;
        }
        .check-circle {
            width: 20px; height: 20px;
            border-radius: 50%;
            background: var(--forest);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-top: 1px;
        }
        .time-note {
            display: flex; align-items: center; gap: 0.5rem;
            background: var(--cream);
            border: 1.5px solid var(--cream-dark);
            border-radius: 8px;
            padding: 0.65rem 1rem;
            font-size: 0.8rem;
            color: var(--muted);
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
        .check-badge {
            position: absolute; top: 8px; right: 8px;
            width: 18px; height: 18px;
            border-radius: 50%;
            background: var(--forest);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transform: scale(0.5);
            transition: all 0.2s;
        }
        .lang-card.selected .check-badge { opacity: 1; transform: scale(1); }
        .flag  { font-size: 2.25rem; display: block; margin-bottom: 0.4rem; }
        .lname { font-family: var(--fd); font-size: 1rem; font-weight: 500; color: var(--forest); display: block; }
        .ldesc { font-size: 0.72rem; color: var(--muted); }

        /* Step 3 — Profil */
        .avatar-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.25rem;
        }
        .avatar-circle {
            width: 88px; height: 88px;
            border-radius: 50%;
            border: 2px dashed var(--sand);
            background: var(--cream);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.2s;
        }
        .avatar-circle:hover { border-color: var(--forest-light); }
        .avatar-circle img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-hint { font-size: 0.75rem; color: var(--muted); margin-top: 0.5rem; }

        /* Step 4 — KTP Upload */
        .ktp-area {
            border: 2px dashed var(--cream-dark);
            border-radius: 12px;
            padding: 2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--cream);
            margin-bottom: 1rem;
        }
        .ktp-area:hover   { border-color: var(--forest-light); background: #F0F5F2; }
        .ktp-area.has-img { border-color: var(--forest); background: #EBF1ED; }
        .ktp-preview { max-width: 100%; max-height: 160px; border-radius: 8px; object-fit: cover; }

        .security-note {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            background: #EBF5EE;
            border: 1.5px solid #B8DFC8;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.78rem;
            color: var(--text);
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        /* Accordion */
        .accordion {
            border: 1.5px solid var(--cream-dark);
            border-radius: 8px;
            overflow: hidden;
        }
        .acc-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1rem;
            cursor: pointer;
            font-size: 0.875rem; font-weight: 500;
            user-select: none;
        }
        .acc-body {
            padding: 0.75rem 1rem 1rem;
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.7;
            border-top: 1.5px solid var(--cream-dark);
        }
        .acc-body ol { padding-left: 1.2rem; }
        .acc-body li { margin-bottom: 0.3rem; }

        /* Step 5 — Bank */
        .earning-box {
            background: var(--cream);
            border: 1.5px solid var(--cream-dark);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.25rem;
        }
        .earning-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 0.6rem;
        }
        .earning-label { font-size: 0.7rem; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .earning-pct   { font-size: 0.875rem; font-weight: 600; color: var(--forest); font-family: var(--fb); }
        .bar-track { height: 8px; border-radius: 999px; background: var(--sand-light); overflow: hidden; }
        .bar-fill  { height: 100%; width: 90%; border-radius: 999px; background: var(--forest); }
        .bar-note  { font-size: 0.72rem; color: var(--muted); margin-top: 0.5rem; }

        .confirm-row {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.5;
            margin-top: 0.75rem;
            cursor: pointer;
        }
        .confirm-row input { width: 16px; height: 16px; accent-color: var(--forest); flex-shrink: 0; margin-top: 2px; }

        .quote {
            text-align: center;
            font-family: var(--fd);
            font-style: italic;
            font-size: 0.875rem;
            color: var(--muted);
            padding: 1.25rem 2rem 1.75rem;
            border-top: 1.5px solid var(--cream-dark);
        }

        /* Completion */
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
        .btn-full:hover    { background: var(--forest-mid); }
        .btn-full:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Animations */
        [x-cloak] { display: none !important; }
        .step-in { animation: slideIn 0.3s ease forwards; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(16px); }
            to   { opacity: 1; transform: translateX(0); }
        }
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
    <div class="card" x-data="onboardingHost()" x-init="init()">

        {{-- Dots --}}
        <div class="dots" x-show="step <= 5">
            <template x-for="i in 5" :key="i">
                <div class="dot" :class="{ active: step === i, done: step > i }"></div>
            </template>
        </div>

        {{-- ── Step 1: Welcome ─────────────────────────────────────── --}}
        <div x-show="step === 1" x-cloak class="step-in">
            <div class="body">
                <div style="display:flex; justify-content:center;">
                    <div class="welcome-icon">🏡</div>
                </div>
                <h1 class="title" style="text-align:center;">
                    Welcome, <span x-text="userName"></span>!
                </h1>
                <p class="subtitle" style="text-align:center; margin-bottom:1.25rem;">
                    Share Your Culture, Create Meaningful Connections.
                </p>

                <div class="checklist">
                    <div class="check-item">
                        <div class="check-circle">
                            <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <span>Personalized hosting dashboard &amp; calendar management</span>
                    </div>
                    <div class="check-item">
                        <div class="check-circle">
                            <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <span>Global visibility to premium intentional travelers</span>
                    </div>
                    <div class="check-item">
                        <div class="check-circle">
                            <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <span>Secure payments — <strong>90% goes directly to you</strong>, only 10% platform commission</span>
                    </div>
                    <div class="check-item">
                        <div class="check-circle">
                            <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <span>Payment received after experience is completed</span>
                    </div>
                </div>

                <div class="time-note">
                    <span>⏱</span>
                    <span>This setup takes approximately <strong>10 minutes</strong></span>
                </div>
            </div>
            <div class="nav" style="justify-content:flex-end;">
                <button class="btn-next" @click="step = 2">Let's Get Started →</button>
            </div>
        </div>

        {{-- ── Step 2: Language ────────────────────────────────────── --}}
        <div x-show="step === 2" x-cloak class="step-in">
            <div class="body">
                <h1 class="title">Choose Your Language</h1>
                <p class="subtitle">We'll show your dashboard and guest messages in your preferred language.</p>

                <div class="lang-grid">
                    <div class="lang-card" :class="{ selected: locale === 'id' }" @click="locale = 'id'">
                        <div class="check-badge">
                            <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <span class="flag">🇮🇩</span>
                        <span class="lname">Bahasa Indonesia</span>
                        <span class="ldesc">Standard Indonesian</span>
                    </div>
                    <div class="lang-card" :class="{ selected: locale === 'en' }" @click="locale = 'en'">
                        <div class="check-badge">
                            <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
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

        {{-- ── Step 3: Profil ──────────────────────────────────────── --}}
        <div x-show="step === 3" x-cloak class="step-in">
            <div class="body">
                <h1 class="title">Build Your Profile</h1>
                <p class="subtitle">Sharing your story helps guests connect with your culture before they arrive.</p>

                <div class="avatar-wrap">
                    <div class="avatar-circle" @click="$refs.avatarInput.click()">
                        <template x-if="avatarPreview">
                            <img :src="avatarPreview" alt="Preview">
                        </template>
                        <template x-if="!avatarPreview">
                            <span style="font-size:2rem; color:var(--sand);">📷</span>
                        </template>
                    </div>
                    <span class="avatar-hint">Upload profile photo (clear face, smile)</span>
                    <input type="file" x-ref="avatarInput" accept="image/*" style="display:none" @change="handleAvatar($event)">
                </div>

                <div class="form-group">
                    <label class="form-label">Village <span style="color:var(--sand); text-transform:none;">(optional)</span></label>
                    <input type="text" class="form-input" placeholder="e.g. Desa Penglipuran" x-model="village">
                </div>

                <div class="form-group">
                    <label class="form-label">About Me <span style="color:var(--sand); text-transform:none;">(min. 50 karakter)</span></label>
                    <textarea class="form-textarea" placeholder="Share a bit about your family heritage and your connection to the land..." x-model="bio" maxlength="1000"></textarea>
                    <div class="char-count" x-text="`${bio.length} / 1000`"></div>
                </div>
            </div>
            <div class="nav">
                <button class="btn-back" @click="step = 2">← Back</button>
                <button class="btn-next" :disabled="saving" @click="saveStep(3)">
                    <span x-show="saving" class="spinner"></span>
                    <span x-show="!saving">Save & Continue →</span>
                </button>
            </div>
        </div>

        {{-- ── Step 4: KTP ─────────────────────────────────────────── --}}
        <div x-show="step === 4" x-cloak class="step-in">
            <div class="body">
                <h1 class="title">Verify Your Identity</h1>
                <p class="subtitle">To ensure the safety of our community, please provide a clear photo of your KTP.</p>

                <div
                    class="ktp-area"
                    :class="{ 'has-img': ktpPreview }"
                    @click="$refs.ktpInput.click()"
                    @dragover.prevent
                    @drop.prevent="handleKtpDrop($event)"
                >
                    <template x-if="ktpPreview">
                        <img :src="ktpPreview" class="ktp-preview" alt="KTP Preview">
                    </template>
                    <template x-if="!ktpPreview">
                        <div>
                            <div style="font-size:2rem; margin-bottom:0.5rem;">📄</div>
                            <div style="font-size:0.875rem; font-weight:500; color:var(--text); margin-bottom:0.25rem;">Drag your KTP here</div>
                            <div style="font-size:0.78rem; color:var(--muted);">or <span style="color:var(--forest); text-decoration:underline;">browse files</span></div>
                        </div>
                    </template>
                    <input type="file" x-ref="ktpInput" accept="image/*" style="display:none" @change="handleKtp($event)">
                </div>

                <div style="font-size:0.72rem; color:var(--muted); margin-bottom:1rem; display:flex; gap:1rem;">
                    <span>✅ Clear &amp; sharp</span>
                    <span>✅ Not blurry</span>
                    <span>✅ Not cropped</span>
                </div>

                <div class="security-note">
                    <span>🔒</span>
                    <span>Your KTP is encrypted and stored securely. Used only for identity verification and never shared with third parties.</span>
                </div>

                <div class="accordion">
                    <div class="acc-header" @click="showNext = !showNext">
                        <span>What happens next?</span>
                        <span x-text="showNext ? '▲' : '▾'" style="font-size:0.75rem; color:var(--muted);"></span>
                    </div>
                    <div class="acc-body" x-show="showNext" x-cloak>
                        <ol>
                            <li>KTP status: <strong>UNVERIFIED → PENDING → VERIFIED</strong></li>
                            <li>Admin will review within <strong>24 hours</strong></li>
                            <li>If verified: email notification, you can publish experiences</li>
                            <li>If rejected: email with notes, you can re-upload</li>
                            <li>You can access your dashboard while waiting</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="nav">
                <button class="btn-back" @click="step = 3">← Back</button>
                <button class="btn-next" :disabled="!ktpFile || saving" @click="saveStep(4)">
                    <span x-show="saving" class="spinner"></span>
                    <span x-show="!saving">Continue →</span>
                </button>
            </div>
        </div>

        {{-- ── Step 5: Bank ─────────────────────────────────────────── --}}
        <div x-show="step === 5" x-cloak class="step-in">
            <div class="body">
                <h1 class="title">Where Should We Send Your Earnings?</h1>
                <p class="subtitle">Provide your payment details to ensure seamless transfers for every successful experience you host.</p>

                <div class="earning-box">
                    <div class="earning-header">
                        <span class="earning-label">Earning Structure</span>
                        <span class="earning-pct">90% Host Payout</span>
                    </div>
                    <div class="bar-track"><div class="bar-fill"></div></div>
                    <div class="bar-note">ℹ️ You receive 90% of the total booking value. The remaining 10% covers platform services and processing fees.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Bank Name</label>
                    <div class="select-wrap">
                        <select class="form-select" x-model="bankName">
                            <option value="">Select your bank</option>
                            <option>BCA</option>
                            <option>BRI</option>
                            <option>BNI</option>
                            <option>Mandiri</option>
                            <option>BSI</option>
                            <option>CIMB Niaga</option>
                            <option>Danamon</option>
                            <option>Permata</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Account Holder Name</label>
                    <input type="text" class="form-input" placeholder="As it appears on your bank statement" x-model="bankAccountName">
                </div>

                <div class="form-group">
                    <label class="form-label">Account Number</label>
                    <input type="text" class="form-input" placeholder="•••• •••• •••• ••••" x-model="bankAccountNumber" inputmode="numeric">
                </div>

                <label class="confirm-row">
                    <input type="checkbox" x-model="confirmBank">
                    <span>I confirm that the provided bank details are accurate and belong to me. I understand that incorrect details may result in delayed payouts.</span>
                </label>
            </div>

            <div class="nav">
                <button class="btn-back" @click="step = 4">← Back</button>
                <button class="btn-next" :disabled="!canSubmit || saving" @click="saveStep(5)">
                    <span x-show="saving" class="spinner"></span>
                    <span x-show="!saving">Complete Setup →</span>
                </button>
            </div>

            <div class="quote">
                "We handle the logistics of payments so you can focus on providing a world-class experience to your guests."
            </div>
        </div>

        {{-- ── Step 6: Completion ───────────────────────────────────── --}}
        <div x-show="step === 6" x-cloak>
            <div class="completion">
                <div class="completion-icon">🏡</div>
                <h1 class="title">You're all set!</h1>
                <p class="subtitle" style="margin-bottom:0.75rem;">
                    Welcome to the CittaLoka host community. Your KTP is being reviewed — we'll notify you within 24 hours.
                </p>
                <p style="font-size:0.78rem; color:var(--muted); margin-bottom:1.5rem;">
                    You can access your dashboard while waiting for verification.
                </p>
                <button class="btn-full" @click="window.location.href='{{ route('dashboard.index') }}'">
                    Go to Dashboard →
                </button>
            </div>
        </div>

    </div>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function onboardingHost() {
    return {
        step:          1,
        saving:        false,
        showNext:      false,
        userName:      '{{ auth()->user()->name }}',
        locale:        '{{ auth()->user()->locale ?? "" }}',
        avatarPreview: null,
        avatarFile:    null,
        village:       '',
        bio:           '',
        ktpPreview:    null,
        ktpFile:       null,
        bankName:           '',
        bankAccountName:    '',
        bankAccountNumber:  '',
        confirmBank:        false,

        get canSubmit() {
            return this.bankName && this.bankAccountName && this.bankAccountNumber && this.confirmBank;
        },

        init() {},

        handleAvatar(e) {
            const f = e.target.files[0];
            if (!f) return;
            if (f.size > 2 * 1024 * 1024) { alert('Ukuran foto maksimal 2MB.'); return; }
            this.avatarFile = f;
            const reader = new FileReader();
            reader.onload = (ev) => { this.avatarPreview = ev.target.result; };
            reader.readAsDataURL(f);
        },

        handleKtp(e) {
            const f = e.target.files[0];
            if (!f) return;
            if (f.size > 5 * 1024 * 1024) { alert('Ukuran foto KTP maksimal 5MB.'); return; }
            this.ktpFile = f;
            const reader = new FileReader();
            reader.onload = (ev) => { this.ktpPreview = ev.target.result; };
            reader.readAsDataURL(f);
        },

        handleKtpDrop(e) {
            const f = e.dataTransfer.files[0];
            if (!f || !f.type.startsWith('image/')) return;
            this.ktpFile = f;
            const reader = new FileReader();
            reader.onload = (ev) => { this.ktpPreview = ev.target.result; };
            reader.readAsDataURL(f);
        },

        async saveStep(stepNum) {
            this.saving = true;
            try {
                const fd = new FormData();
                fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                fd.append('step', stepNum);

                if (stepNum === 2) {
                    fd.append('locale', this.locale);
                }
                if (stepNum === 3) {
                    if (this.avatarFile) fd.append('avatar', this.avatarFile);
                    fd.append('bio',     this.bio);
                    fd.append('village', this.village);
                }
                if (stepNum === 4) {
                    fd.append('ktp_photo', this.ktpFile);
                }
                if (stepNum === 5) {
                    fd.append('bank_name',           this.bankName);
                    fd.append('bank_account_name',   this.bankAccountName);
                    fd.append('bank_account_number', this.bankAccountNumber);
                    fd.append('confirm_bank', '1');
                }

                const res  = await fetch('{{ route("onboarding.host.save") }}', {
                    method: 'POST', body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                if (data.success) {
                    if (data.redirect) {
                        // Step 5 selesai → redirect ke dashboard
                        this.step = 6; // tampilkan completion dulu
                    } else {
                        this.step++;
                    }
                } else {
                    alert('Terjadi kesalahan. Coba lagi.');
                }
            } catch (e) {
                alert('Network error. Periksa koneksimu.');
            } finally {
                this.saving = false;
            }
        },
    }
}
</script>
</body>
</html>