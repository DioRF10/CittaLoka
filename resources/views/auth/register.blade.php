<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - CittaLoka</title>
    
    <!-- Meta tags for SEO -->
    <meta name="description" content="Join CittaLoka and discover authentic cultural experiences. Connect with local hosts and explore the living rhythm of the world's most vibrant cultures.">
    
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
            --color-error: #DC2626;
            --color-error-bg: #FEF2F2;
            --color-error-border: #FECACA;
            
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
            padding: 64px 80px; 
            background: #FAF8F5;
            overflow-y: auto;
            height: 100vh;
        }
        .auth-form-inner { 
            width: 100%; 
            max-width: 460px; 
        }

        .label-tag {
            font-size: 11px; 
            font-weight: 600; 
            letter-spacing: 0.15em;
            color: var(--color-accent); 
            text-transform: uppercase; 
            margin-bottom: 8px;
            display: inline-block;
        }
        .auth-title {
            font-family: var(--font-heading);
            font-size: 38px; 
            color: var(--color-primary); 
            font-weight: 400;
            margin-bottom: 8px; 
            line-height: 1.2;
        }
        .auth-subtitle {
            font-size: 14px;
            color: var(--color-text-muted);
            margin-bottom: 32px;
        }

        /* Google Button - Premium Glassmorphism style */
        .btn-google {
            width: 100%; 
            padding: 14px 16px;
            background: #FFFFFF; 
            border: 1px solid var(--color-border);
            border-radius: 12px; 
            font-size: 14px; 
            font-weight: 500;
            color: var(--color-primary); 
            cursor: pointer;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 12px;
            font-family: var(--font-body);
            transition: var(--transition-smooth); 
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(28, 43, 30, 0.02);
        }
        .btn-google:hover { 
            border-color: var(--color-primary); 
            background: #FAF9F6;
            box-shadow: 0 4px 12px rgba(28, 43, 30, 0.05);
            transform: translateY(-1px);
        }
        .btn-google svg { 
            width: 18px; 
            height: 18px; 
        }

        /* Divider */
        .divider {
            display: flex; 
            align-items: center; 
            gap: 16px;
            margin: 24px 0; 
            color: #A3A8A4; 
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.1em; 
            text-transform: uppercase;
        }
        .divider::before, .divider::after {
            content: ''; 
            flex: 1; 
            height: 1px; 
            background: var(--color-border);
        }

        /* Form Fields */
        .field { 
            margin-bottom: 20px; 
            position: relative;
        }
        .field label {
            display: block; 
            font-size: 11px; 
            font-weight: 600;
            letter-spacing: 0.1em; 
            color: var(--color-text-muted);
            text-transform: uppercase; 
            margin-bottom: 8px;
        }
        
        .input-icon-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            color: #8D9490;
            pointer-events: none;
            transition: var(--transition-smooth);
            width: 18px;
            height: 18px;
        }
        
        .field input {
            width: 100%; 
            padding: 14px 16px 14px 48px;
            border: 1px solid var(--color-border); 
            border-radius: 12px;
            font-size: 14px; 
            color: var(--color-primary);
            background: #FFFFFF; 
            font-family: var(--font-body);
            transition: var(--transition-smooth); 
            outline: none;
            box-shadow: 0 2px 4px rgba(28, 43, 30, 0.01);
        }
        .field input:focus { 
            border-color: var(--color-border-focus);
            box-shadow: 0 0 0 4px rgba(28, 43, 30, 0.06);
            background: #FFFFFF;
        }
        .field input:focus ~ .input-icon {
            color: var(--color-primary);
        }
        .field input::placeholder { 
            color: #BDBAB0; 
        }
        .field input.error { 
            border-color: var(--color-error); 
            background: #FFFDFD;
        }
        .field input.error:focus {
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.08);
        }
        .field .error-msg {
            font-size: 12px; 
            color: var(--color-error); 
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Password wrapper */
        .password-wrap { 
            position: relative; 
            width: 100%;
        }
        .password-wrap input { 
            padding-right: 48px; 
        }
        .password-toggle {
            position: absolute; 
            right: 16px; 
            top: 50%; 
            transform: translateY(-50%);
            background: none; 
            border: none; 
            cursor: pointer;
            color: #8D9490; 
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
        }
        .password-toggle:hover {
            color: var(--color-primary);
        }
        .password-toggle svg {
            width: 20px;
            height: 20px;
        }

        /* Custom Checkbox - Premium styling */
        .checkbox-wrap {
            display: flex; 
            align-items: flex-start; 
            gap: 12px;
            margin: 24px 0;
        }
        .custom-checkbox-container {
            display: block;
            position: relative;
            padding-left: 24px;
            cursor: pointer;
            font-size: 13px;
            color: var(--color-text-muted);
            user-select: none;
            line-height: 1.5;
        }
        .custom-checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        .checkmark {
            position: absolute;
            top: 2px;
            left: 0;
            height: 16px;
            width: 16px;
            background-color: #FFFFFF;
            border: 1px solid var(--color-border);
            border-radius: 4px;
            transition: var(--transition-smooth);
        }
        .custom-checkbox-container:hover input ~ .checkmark {
            border-color: var(--color-primary);
        }
        .custom-checkbox-container input:checked ~ .checkmark {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        .custom-checkbox-container input:checked ~ .checkmark:after {
            display: block;
        }
        .custom-checkbox-container .checkmark:after {
            left: 5px;
            top: 2px;
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        .custom-checkbox-container a { 
            color: var(--color-accent); 
            text-decoration: none; 
            font-weight: 500;
        }
        .custom-checkbox-container a:hover { 
            text-decoration: underline; 
            color: var(--color-accent-hover);
        }

        /* Primary Button */
        .btn-primary {
            width: 100%; 
            padding: 16px;
            background: var(--color-primary); 
            color: #FFFFFF;
            border: none; 
            border-radius: 12px;
            font-size: 15px; 
            font-weight: 600; 
            cursor: pointer;
            transition: var(--transition-smooth);
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 10px;
            font-family: var(--font-body);
            box-shadow: 0 4px 14px rgba(28, 43, 30, 0.15);
        }
        .btn-primary:hover { 
            background: var(--color-primary-light); 
            transform: translateY(-1px); 
            box-shadow: 0 6px 20px rgba(28, 43, 30, 0.25);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .btn-primary:disabled { 
            background: #D1CFC8; 
            cursor: not-allowed; 
            transform: none; 
            box-shadow: none;
        }

        .note-text {
            font-size: 12px; 
            color: var(--color-text-muted); 
            text-align: center;
            margin-top: 20px; 
            line-height: 1.6;
        }
        .signin-link {
            text-align: center; 
            margin-top: 24px;
            font-size: 14px; 
            color: var(--color-text-muted);
            border-top: 1px solid var(--color-border);
            padding-top: 24px;
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

        /* Alert error */
        .alert-error {
            background: var(--color-error-bg); 
            border: 1px solid var(--color-error-border);
            border-radius: 12px; 
            padding: 14px 16px;
            font-size: 13px; 
            color: var(--color-error); 
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 6px;
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
        <img src="https://images.unsplash.com/photo-1604999333679-b86d54738315?w=1200&q=80" alt="Authentic Bali culture gate">
        <div class="auth-photo-overlay"></div>
        <div class="auth-photo-brand">
            <span class="auth-photo-brand-dot"></span>
            CittaLoka
        </div>
        <div class="auth-photo-quote">
            <h2>Immerse yourself in the living rhythm of the world's most vibrant cultures.</h2>
            <p>Connect with local hosts, learn centuries-old traditions, and create memories that live forever in your heart.</p>
        </div>
    </div>

    {{-- Kolom Kanan: Form --}}
    <div class="auth-form">
        <div class="auth-form-inner">

            <p class="label-tag">Create Your Account</p>
            <h1 class="auth-title">Join CittaLoka</h1>
            <p class="auth-subtitle">Begin your journey to authentic cultural discoveries today.</p>

            {{-- Error dari session --}}
            @if (session('error'))
                <div class="alert-error">
                    <div style="display:flex; align-items:center; gap:8px; font-weight:600;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Error
                    </div>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <div style="display:flex; align-items:center; gap:8px; font-weight:600;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Please check the fields below
                    </div>
                    <ul style="list-style:none; padding-left:0; font-size:12px; margin-top:4px;">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Google OAuth Button --}}
            <a href="{{ route('auth.google.redirect') }}" class="btn-google">
                <svg viewBox="0 0 24 24" width="20" height="20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                </svg>
                Continue with Google
            </a>

            <div class="divider">or register with email</div>

            {{-- Form Register --}}
            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <input type="hidden" name="role" value="{{ session('register_role', 'user') }}">

                <!-- Full Name Field -->
                <div class="field">
                    <label for="name">Full Name</label>
                    <div class="input-icon-wrap">
                        <input
                            type="text" id="name" name="name"
                            placeholder="Your full name"
                            value="{{ old('name') }}"
                            class="{{ $errors->has('name') ? 'error' : '' }}"
                            required autofocus
                        >
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    @error('name') 
                        <div class="error-msg">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="field">
                    <label for="email">Email Address</label>
                    <div class="input-icon-wrap">
                        <input
                            type="email" id="email" name="email"
                            placeholder="you@example.com"
                            value="{{ old('email') }}"
                            class="{{ $errors->has('email') ? 'error' : '' }}"
                            required
                        >
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    @error('email') 
                        <div class="error-msg">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="field" x-data="{ show: false }">
                    <label for="password">Password</label>
                    <div class="input-icon-wrap password-wrap">
                        <input
                            :type="show ? 'text' : 'password'"
                            id="password" name="password"
                            placeholder="Min. 8 characters"
                            class="{{ $errors->has('password') ? 'error' : '' }}"
                            required
                        >
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        
                        <button type="button" class="password-toggle" @click="show = !show" aria-label="Toggle password visibility">
                            <svg x-show="!show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg x-show="show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                    @error('password') 
                        <div class="error-msg">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-icon-wrap">
                        <input
                            type="password" id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Re-type password"
                            required
                        >
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                </div>

                <!-- Terms and Conditions Checkbox -->
                <div class="checkbox-wrap">
                    <label class="custom-checkbox-container">
                        <input type="checkbox" id="terms" name="terms" required>
                        <span class="checkmark"></span>
                        I agree to CittaLoka's
                        <a href="#">Terms of Service</a> and
                        <a href="#">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn-primary">
                    Create Account
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>

            <p class="note-text">
                By creating an account, you agree to receive cultural updates and booking notifications.
            </p>

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
