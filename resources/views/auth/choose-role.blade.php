<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join CittaLoka - Choose Role</title>
    
    <!-- Meta tags for SEO -->
    <meta name="description" content="Choose your role in CittaLoka. Register as a Host to share your culture, or a Traveler to discover authentic experiences.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-bg: #F5F2EC;
            --color-surface: #FFFFFF;
            --color-primary: #1C2B1E;
            --color-primary-light: #2C3E2F;
            --color-accent: #C4783A;
            --color-accent-hover: #AF652A;
            --color-text: #2C332E;
            --color-text-muted: #6E7570;
            --color-border: #E0DBD0;
            --color-border-focus: #1C2B1E;
            
            --font-heading: 'Playfair Display', serif;
            --font-body: 'DM Sans', sans-serif;
            
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }

        body { 
            font-family: var(--font-body); 
            background: var(--color-bg); 
            color: var(--color-text);
            min-height: 100vh; 
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .auth-wrap {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            min-height: 100vh;
        }

        /* Left Column - Hero Visual */
        .auth-photo { 
            position: relative; 
            overflow: hidden; 
            height: 100vh;
            display: flex;
            align-items: flex-end;
            padding: 64px;
        }
        .auth-photo img { 
            position: absolute;
            inset: 0;
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            z-index: 1;
            transition: transform 10s ease;
        }
        .auth-photo:hover img {
            transform: scale(1.05);
        }
        .auth-photo-overlay {
            position: absolute; 
            inset: 0;
            background: linear-gradient(
                to bottom, 
                rgba(28, 43, 30, 0.25) 0%, 
                rgba(28, 43, 30, 0.85) 100%
            );
            z-index: 2;
        }
        .auth-photo-brand {
            position: absolute; 
            top: 48px; 
            left: 48px;
            font-family: var(--font-heading);
            font-size: 26px; 
            font-weight: 700;
            color: #FFFFFF; 
            letter-spacing: -0.01em;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .auth-photo-brand-dot {
            width: 8px;
            height: 8px;
            background-color: var(--color-accent);
            border-radius: 50%;
        }
        .auth-photo-quote { 
            position: relative;
            z-index: 3;
            max-width: 520px;
        }
        .auth-photo-quote h2 {
            font-family: var(--font-heading);
            font-size: 32px; 
            color: #FFFFFF; 
            font-weight: 400; 
            margin-bottom: 16px;
            line-height: 1.25;
        }
        .auth-photo-quote p {
            font-size: 14px; 
            color: rgba(255, 255, 255, 0.8);
            font-style: italic; 
            font-weight: 300;
            line-height: 1.6;
        }

        /* Right Column - Form Container */
        .auth-form {
            display: flex; 
            align-items: center; 
            justify-content: center;
            padding: 40px 64px; 
            background: #FAF8F5;
            height: 100vh;
            overflow-y: auto;
        }
        .auth-form-inner { 
            width: 100%; 
            max-width: 400px; 
        }

        .label-tag {
            font-size: 10px; 
            font-weight: 600; 
            letter-spacing: 0.15em;
            color: var(--color-accent); 
            text-transform: uppercase; 
            margin-bottom: 6px;
            display: inline-block;
        }
        .auth-title {
            font-family: var(--font-heading);
            font-size: 32px; 
            color: var(--color-primary); 
            font-weight: 400;
            margin-bottom: 6px; 
            line-height: 1.2;
        }
        .auth-subtitle {
            font-size: 13px;
            color: var(--color-text-muted);
            margin-bottom: 24px;
        }

        /* Role Cards Premium Grid */
        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }
        .role-card {
            position: relative;
            border: 2px solid transparent;
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition-smooth);
            overflow: hidden;
            height: 200px;
            box-shadow: 0 4px 12px rgba(28, 43, 30, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .role-card img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
        }
        .role-card:hover img {
            transform: scale(1.08);
        }
        .role-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top, 
                rgba(28, 43, 30, 0.9) 0%, 
                rgba(28, 43, 30, 0.4) 50%,
                rgba(28, 43, 30, 0.1) 100%
            );
            z-index: 2;
            transition: var(--transition-smooth);
        }
        .role-card.selected .role-card-overlay {
            background: linear-gradient(
                to top, 
                rgba(196, 120, 58, 0.95) 0%, 
                rgba(196, 120, 58, 0.5) 60%,
                rgba(196, 120, 58, 0.1) 100%
            );
        }
        .role-card-content {
            position: relative;
            z-index: 3;
            padding: 16px;
            color: #FFFFFF;
        }
        .role-card-tick {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #FFFFFF;
            border: 2px solid var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-primary);
            font-size: 12px;
            font-weight: 700;
            opacity: 0;
            transform: scale(0.7);
            transition: var(--transition-smooth);
            z-index: 3;
        }
        
        .role-card.selected .role-card-tick {
            opacity: 1;
            transform: scale(1);
            background: #FFFFFF;
            border-color: #FFFFFF;
            color: var(--color-accent);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .role-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(28, 43, 30, 0.12);
        }
        .role-card.selected {
            border-color: #FFFFFF;
            box-shadow: 0 8px 24px rgba(196, 120, 58, 0.25);
        }
        .role-name {
            font-family: var(--font-heading);
            font-size: 18px;
            color: #FFFFFF;
            font-weight: 600;
            margin-bottom: 2px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        .role-desc {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.4;
            font-weight: 400;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
        }

        /* Primary Button */
        .btn-primary {
            width: 100%; 
            padding: 14px;
            background: var(--color-primary); 
            color: #FFFFFF;
            border: none; 
            border-radius: 10px;
            font-size: 14px; 
            font-weight: 600; 
            cursor: pointer;
            transition: var(--transition-smooth);
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px;
            font-family: var(--font-body);
            box-shadow: 0 4px 14px rgba(28, 43, 30, 0.15);
        }
        .btn-primary svg {
            width: 16px;
            height: 16px;
        }
        .btn-primary:hover:not(:disabled) { 
            background: var(--color-primary-light); 
            transform: translateY(-1px); 
            box-shadow: 0 6px 20px rgba(28, 43, 30, 0.25);
        }
        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }
        .btn-primary:disabled { 
            background: #D1CFC8; 
            color: #8E8B83;
            cursor: not-allowed; 
            transform: none; 
            box-shadow: none;
        }

        .signin-link {
            text-align: center; 
            margin-top: 20px;
            font-size: 13px; 
            color: var(--color-text-muted);
            border-top: 1px solid var(--color-border);
            padding-top: 20px;
        }
        .signin-link a { 
            color: var(--color-accent); 
            font-weight: 600; 
            text-decoration: none; 
            transition: var(--transition-smooth);
        }
        .signin-link a:hover { 
            color: var(--color-accent-hover);
            text-decoration: underline; 
        }

        @media (max-width: 992px) {
            .auth-wrap { 
                grid-template-columns: 1fr; 
            }
            .auth-photo { 
                display: none; 
            }
            .auth-form { 
                padding: 48px 32px; 
                height: 100vh;
            }
        }
    </style>
</head>
<body>

<div class="auth-wrap">

    {{-- Kolom Kiri: Foto --}}
    <div class="auth-photo">
        <img src="https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=1200&q=80" alt="Bali Traditional Culture Scene">
        <div class="auth-photo-overlay"></div>
        <div class="auth-photo-quote">
            <h2>Immerse yourself in the living rhythm of the world's most vibrant cultures.</h2>
            <p>Connect with local hosts, learn centuries-old traditions, and create memories that live forever in your heart.</p>
        </div>
    </div>

    {{-- Kolom Kanan: Form --}}
    <div class="auth-form">
        <div class="auth-form-inner" x-data="{ role: '' }">

            <p class="label-tag">Join CittaLoka</p>
            <h1 class="auth-title">Choose Your Role</h1>
            <p class="auth-subtitle">Tell us how you would like to experience CittaLoka.</p>

            <div class="role-grid">
                {{-- Host Card --}}
                <div
                    class="role-card"
                    :class="{ 'selected': role === 'host' }"
                    @click="role = 'host'"
                >
                    <img src="/images/auth/card-host.png" alt="Host background image showing Balinese hospitality">
                    <div class="role-card-overlay"></div>
                    <div class="role-card-tick">✓</div>
                    <div class="role-card-content">
                        <div class="role-name">Host</div>
                        <div class="role-desc">Share your culture, skills, and home with the world</div>
                    </div>
                </div>

                {{-- Traveler Card --}}
                <div
                    class="role-card"
                    :class="{ 'selected': role === 'user' }"
                    @click="role = 'user'"
                >
                    <img src="/images/auth/card-traveler.png" alt="Traveler background image exploring Bali fields">
                    <div class="role-card-overlay"></div>
                    <div class="role-card-tick">✓</div>
                    <div class="role-card-content">
                        <div class="role-name">Traveler</div>
                        <div class="role-desc">Discover authentic activities and connect with locals</div>
                    </div>
                </div>
            </div>

            {{-- Form POST to set the role in session --}}
            <form method="POST" action="{{ route('register.set-role') }}">
                @csrf
                <input type="hidden" name="role" :value="role">
                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="!role"
                >
                    Continue as <span x-text="role === 'host' ? 'Host' : (role === 'user' ? 'Traveler' : '...')"></span>
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>

            <p class="signin-link">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
            </p>

        </div>
    </div>

</div>

<!-- Alpine.js to manage interactive elements -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>
