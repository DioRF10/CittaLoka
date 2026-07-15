<?php
$file = 'resources/views/onboarding/host.blade.php';
$content = file_get_contents($file);

// Replace CSS
$css_start = strpos($content, '/* ── Shell Layout ── */');
$css_end = strpos($content, '/* ── Right Panel ── */');

$new_css = <<<CSS
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
            background: linear-gradient(to bottom,
                    rgba(15, 27, 17, 0.22) 0%,
                    rgba(15, 27, 17, 0.88) 100%);
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
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.4;
        }

CSS;

$content = substr_replace($content, $new_css, $css_start, $css_end - $css_start);

// Replace HTML left panel
$html_start = strpos($content, '<div class="left-panel">');
// Find the right panel start correctly
$html_end = strpos($content, '<div class="right-panel">');
// Go back to the comment
$html_end = strrpos(substr($content, 0, $html_end), '{{-- ═══════════════════════════════════════════════');


$new_html = <<<'HTML'
<div class="left-panel">

            {{-- Background photo — changes per step --}}
            <img x-show="step === 1" class="left-bg" src="{{ asset('images/onboarding-step1.png') }}" alt="Host welcome">
            <img x-show="step === 2" class="left-bg" src="{{ asset('images/onboarding-step2.png') }}" alt="Location">
            <img x-show="step === 3" class="left-bg" src="{{ asset('images/onboarding-step3.png') }}" alt="Host profile">
            <img x-show="step === 4" class="left-bg" src="{{ asset('images/onboarding-step4.png') }}" alt="Verification">
            <img x-show="step === 5" class="left-bg" src="{{ asset('images/auth/travelling.png') }}" alt="Bank info">
            <img x-show="step === 6" class="left-bg" src="{{ asset('images/onboarding-step1.png') }}" alt="Completion">

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
                    <span class="left-eyebrow">Why Join</span>
                    <h1 class="left-title">Why become<br>a Host on CittaLoka?</h1>
                    <p class="left-desc">CittaLoka helps you share knowledge and culture, while earning fair income from every experience you share.</p>

                    <div class="left-benefit-list" style="margin-top:1.25rem;">
                        <div class="left-benefit-item">
                            <div class="left-benefit-dot">✦</div>
                            <div>
                                <div class="left-benefit-title">90% of every booking</div>
                                <div class="left-benefit-desc">We only take 10% commission for platform operations.</div>
                            </div>
                        </div>
                        <div class="left-benefit-item">
                            <div class="left-benefit-dot">✦</div>
                            <div>
                                <div class="left-benefit-title">Payout otomatis</div>
                                <div class="left-benefit-desc">Dana dari booking dicairkan otomatis ke rekening Anda.</div>
                            </div>
                        </div>
                        <div class="left-benefit-item">
                            <div class="left-benefit-dot">✦</div>
                            <div>
                                <div class="left-benefit-title">Kurasi & dukungan tim</div>
                                <div class="left-benefit-desc">Kami memastikan setiap host berkualitas dan siap dibantu.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Step 2 content --}}
            <template x-if="step === 2">
                <div class="left-content">
                    <span class="left-eyebrow">Langkah 2 dari 5</span>
                    <h1 class="left-title">Bahasa & lokasi pengalaman Anda</h1>
                    <p class="left-desc">Informasi ini membantu kami menerjemahkan konten Anda dan menampilkan pengalaman Anda kepada wisatawan yang tepat.</p>

                    <div class="left-info-box">
                        <div class="left-info-icon" style="color:white; display:flex; align-items:center; justify-content:center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        </div>
                        <div>
                            <div class="left-info-title">Kenapa informasi ini penting?</div>
                            <div class="left-info-text">Language and location help us translate your story accurately and show your experience to relevant travelers.</div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Step 3 content --}}
            <template x-if="step === 3">
                <div class="left-content">
                    <span class="left-eyebrow">Langkah 3 dari 5</span>
                    <h1 class="left-title">Ceritakan tentang diri Anda</h1>
                    <p class="left-desc">A complete profile helps travelers get to know you and feel inspired by the experiences you share.</p>

                    <div class="left-info-box">
                        <div class="left-info-icon" style="color:white; display:flex; align-items:center; justify-content:center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        </div>
                        <div>
                            <div class="left-info-title">Tips</div>
                            <div class="left-info-text">Keaslian cerita Anda adalah kekuatan utama. Bagikan dengan jujur dan autentik tentang diri dan keahlian Anda.</div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Step 4 content --}}
            <template x-if="step === 4">
                <div class="left-content">
                    <span class="left-eyebrow">Langkah 4 dari 5</span>
                    <h1 class="left-title">Verifikasi identitas untuk keamanan bersama</h1>
                    <p class="left-desc">Kami melakukan verifikasi identitas untuk memastikan keamanan komunitas CittaLoka dan membangun kepercayaan dengan wisatawan.</p>

                    <div class="left-info-box">
                        <div class="left-info-icon" style="color:white; display:flex; align-items:center; justify-content:center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <div>
                            <div class="left-info-title">Data Anda aman</div>
                            <div class="left-info-text">Informasi identitas akan dienkripsi dan hanya digunakan untuk keperluan verifikasi. Kami tidak akan membagikannya ke pihak lain.</div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Step 5 content --}}
            <template x-if="step === 5">
                <div class="left-content">
                    <span class="left-eyebrow">Langkah 5 dari 5</span>
                    <h1 class="left-title">Informasi rekening untuk pencairan dana</h1>
                    <p class="left-desc">Rekening ini digunakan untuk menerima pembayaran dari setiap booking yang Anda terima. Pastikan datanya benar.</p>

                    <div class="left-info-box">
                        <div class="left-info-icon" style="color:white; display:flex; align-items:center; justify-content:center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        </div>
                        <div>
                            <div class="left-info-title">Bagaimana cara kerjanya?</div>
                            <div class="left-info-text">Kami otomatis memverifikasi nama pemilik rekening Anda begitu disubmit. Jika ada ketidaksesuaian, tim kami akan meninjau secara singkat — Anda tidak perlu menunggu untuk lanjut.</div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Completion --}}
            <template x-if="step === 6">
                <div class="left-content">
                    <span class="left-eyebrow">Selesai</span>
                    <h1 class="left-title">Welcome to the CittaLoka community</h1>
                    <p class="left-desc">Anda sudah menjadi bagian dari host yang membagikan budaya Bali kepada dunia.</p>
                </div>
            </template>

        </div>

        
HTML;

$content = substr_replace($content, $new_html, $html_start, $html_end - $html_start);

file_put_contents($file, $content);
echo "Host onboarding updated.\n";
?>
