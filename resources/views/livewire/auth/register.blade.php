<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - CittaLoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #F5F2EC; min-height: 100vh; }

        .auth-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* Kolom kiri — foto */
        .auth-photo { position: relative; overflow: hidden; }
        .auth-photo img { width: 100%; height: 100%; object-fit: cover; }
        .auth-photo-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.55) 100%);
        }
        .auth-photo-brand {
            position: absolute; top: 32px; left: 32px;
            font-family: 'Playfair Display', serif;
            font-size: 22px; color: white; letter-spacing: 0.02em;
        }
        .auth-photo-quote { position: absolute; bottom: 40px; left: 32px; right: 32px; }
        .auth-photo-quote h3 {
            font-family: 'Playfair Display', serif;
            font-size: 20px; color: white; font-weight: 400; margin-bottom: 8px;
        }
        .auth-photo-quote p {
            font-size: 13px; color: rgba(255,255,255,0.75);
            font-style: italic; line-height: 1.5;
        }

        /* Kolom kanan — form */
        .auth-form {
            display: flex; align-items: center; justify-content: center;
            padding: 48px 64px; background: #F5F2EC;
            overflow-y: auto;
        }
        .auth-form-inner { width: 100%; max-width: 440px; }

        .label-tag {
            font-size: 11px; font-weight: 500; letter-spacing: 0.12em;
            color: #C4783A; text-transform: uppercase; margin-bottom: 12px;
        }
        .auth-title {
            font-family: 'Playfair Display', serif;
            font-size: 34px; color: #1C2B1E; font-weight: 400;
            margin-bottom: 28px; line-height: 1.2;
        }

        /* Google Button */
        .btn-google {
            width: 100%; padding: 14px 16px;
            background: white; border: 1.5px solid #E0DBD0;
            border-radius: 8px; font-size: 14px; font-weight: 500;
            color: #1C2B1E; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s; text-decoration: none;
        }
        .btn-google:hover { border-color: #1C2B1E; background: #F9F7F3; }
        .btn-google img { width: 18px; height: 18px; }

        /* Divider */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0; color: #9CA3AF; font-size: 12px;
            letter-spacing: 0.08em; text-transform: uppercase;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: #E0DBD0;
        }

        /* Form Fields */
        .field { margin-bottom: 16px; }
        .field label {
            display: block; font-size: 11px; font-weight: 500;
            letter-spacing: 0.08em; color: #6B7280;
            text-transform: uppercase; margin-bottom: 6px;
        }
        .field input {
            width: 100%; padding: 12px 14px;
            border: 1.5px solid #E0DBD0; border-radius: 8px;
            font-size: 14px; color: #1C2B1E;
            background: white; font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s; outline: none;
        }
        .field input:focus { border-color: #1C2B1E; }
        .field input::placeholder { color: #C4BDB0; }
        .field input.error { border-color: #E53E3E; }
        .field .error-msg {
            font-size: 12px; color: #E53E3E; margin-top: 4px;
        }

        /* Password wrapper */
        .password-wrap { position: relative; }
        .password-wrap input { padding-right: 44px; }
        .password-toggle {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #9CA3AF; font-size: 16px; padding: 0;
        }

        /* Checkbox */
        .checkbox-wrap {
            display: flex; align-items: flex-start; gap: 10px;
            margin: 20px 0;
        }
        .checkbox-wrap input[type="checkbox"] {
            width: 16px; height: 16px; margin-top: 2px;
            accent-color: #1C2B1E; cursor: pointer; flex-shrink: 0;
        }
        .checkbox-wrap label {
            font-size: 13px; color: #6B7280; cursor: pointer; line-height: 1.5;
        }
        .checkbox-wrap a { color: #C4783A; text-decoration: none; }
        .checkbox-wrap a:hover { text-decoration: underline; }

        /* Primary Button */
        .btn-primary {
            width: 100%; padding: 15px;
            background: #1C2B1E; color: white;
            border: none; border-radius: 8px;
            font-size: 15px; font-weight: 500; cursor: pointer;
            transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            font-family: 'DM Sans', sans-serif;
        }
        .btn-primary:hover { background: #2D4A32; transform: translateY(-1px); }
        .btn-primary:disabled { background: #D1CFC8; cursor: not-allowed; transform: none; }

        .note-text {
            font-size: 12px; color: #9CA3AF; text-align: center;
            margin-top: 16px; line-height: 1.5;
        }
        .signin-link {
            text-align: center; margin-top: 20px;
            font-size: 14px; color: #6B7280;
        }
        .signin-link a { color: #1C2B1E; font-weight: 500; text-decoration: none; }
        .signin-link a:hover { text-decoration: underline; }

        /* Alert error */
        .alert-error {
            background: #FEF2F2; border: 1px solid #FECACA;
            border-radius: 8px; padding: 12px 14px;
            font-size: 13px; color: #DC2626; margin-bottom: 16px;
        }

        @media (max-width: 768px) {
            .auth-wrap { grid-template-columns: 1fr; }
            .auth-photo { display: none; }
            .auth-form { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<div class="auth-wrap">

    {{-- Kolom Kiri: Foto --}}
    <div class="auth-photo">
        <img src="https://images.unsplash.com/photo-1604999333679-b86d54738315?w=800&q=80" alt="Bali">
        <div class="auth-photo-overlay"></div>
        <div class="auth-photo-brand">CittaLoka</div>
        <div class="auth-photo-quote">
            <h3>CittaLoka</h3>
            <p>"Immerse yourself in the living rhythm of the world's most vibrant cultures."</p>
        </div>
    </div>

    {{-- Kolom Kanan: Form --}}
    <div class="auth-form">
        <div class="auth-form-inner">

            <p class="label-tag">Create Your Account</p>
            <h1 class="auth-title">Welcome to CittaLoka</h1>

            {{-- Error dari session --}}
            @if (session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- Google OAuth --}}
            <a href="{{ route('auth.google.redirect') }}" class="btn-google">
                <img src="https://www.google.com/favicon.ico" alt="Google">
                Continue with Google
            </a>

            <div class="divider">or register with email</div>

            {{-- Form Register --}}
            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <input type="hidden" name="role" value="{{ session('register_role', 'user') }}">

                <div class="field">
                    <label for="name">Full Name</label>
                    <input
                        type="text" id="name" name="name"
                        placeholder="Your full name"
                        value="{{ old('name') }}"
                        class="{{ $errors->has('name') ? 'error' : '' }}"
                        required autofocus
                    >
                    @error('name') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input
                        type="email" id="email" name="email"
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        class="{{ $errors->has('email') ? 'error' : '' }}"
                        required
                    >
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="password-wrap" x-data="{ show: false }">
                        <input
                            :type="show ? 'text' : 'password'"
                            id="password" name="password"
                            placeholder="Min. 8 characters"
                            class="{{ $errors->has('password') ? 'error' : '' }}"
                            required
                        >
                        <button type="button" class="password-toggle" @click="show = !show">
                            <span x-text="show ? '🙈' : '👁'"></span>
                        </button>
                    </div>
                    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="password-wrap">
                        <input
                            type="password" id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Re-type password"
                            required
                        >
                    </div>
                </div>

                <div class="checkbox-wrap">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        I agree to CittaLoka's
                        <a href="#">Terms of Service</a> and
                        <a href="#">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn-primary">
                    Create Account →
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

{{-- Alpine.js untuk toggle password --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>
