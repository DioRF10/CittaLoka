<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{state};

new class extends Component {
    public string $role = '';

    public function selectRole(string $role): void
    {
        $this->role = $role;
    }

    public function continue(): void
    {
        if (!$this->role) return;
        session(['register_role' => $this->role]);
        $this->redirect(route('register.form'));
    }
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join CittaLoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #F5F2EC; min-height: 100vh; }

        .auth-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* Kolom kiri — foto */
        .auth-photo {
            position: relative;
            overflow: hidden;
        }
        .auth-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .auth-photo-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.5) 100%);
        }
        .auth-photo-brand {
            position: absolute;
            top: 32px;
            left: 32px;
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: white;
            letter-spacing: 0.02em;
        }
        .auth-photo-quote {
            position: absolute;
            bottom: 40px;
            left: 32px;
            right: 32px;
        }
        .auth-photo-quote h3 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: white;
            font-weight: 400;
            margin-bottom: 8px;
        }
        .auth-photo-quote p {
            font-size: 13px;
            color: rgba(255,255,255,0.75);
            font-style: italic;
            line-height: 1.5;
        }

        /* Kolom kanan — form */
        .auth-form {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 64px;
            background: #F5F2EC;
        }
        .auth-form-inner {
            width: 100%;
            max-width: 440px;
        }

        .label-tag {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.12em;
            color: #C4783A;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .auth-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            color: #1C2B1E;
            font-weight: 400;
            margin-bottom: 8px;
            line-height: 1.2;
        }
        .auth-subtitle {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 36px;
        }

        /* Role Cards */
        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 32px;
        }
        .role-card {
            border: 1.5px solid #E0DBD0;
            border-radius: 12px;
            padding: 24px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            text-align: center;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .role-card:hover {
            border-color: #1C2B1E;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .role-card.selected {
            border-color: #1C2B1E;
            border-width: 2px;
            background: #F0EDE6;
            box-shadow: 0 4px 16px rgba(28,43,30,0.12);
        }
        .role-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #F0EDE6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        .role-card.selected .role-icon {
            background: #1C2B1E;
        }
        .role-name {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #1C2B1E;
            font-weight: 400;
        }
        .role-desc {
            font-size: 12px;
            color: #9CA3AF;
            line-height: 1.4;
        }

        /* Button */
        .btn-primary {
            width: 100%;
            padding: 16px;
            background: #1C2B1E;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'DM Sans', sans-serif;
        }
        .btn-primary:hover {
            background: #2D4A32;
            transform: translateY(-1px);
        }
        .btn-primary:disabled {
            background: #D1CFC8;
            cursor: not-allowed;
            transform: none;
        }

        .signin-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #6B7280;
        }
        .signin-link a {
            color: #1C2B1E;
            font-weight: 500;
            text-decoration: none;
        }
        .signin-link a:hover { text-decoration: underline; }

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
        <img src="https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=800&q=80" alt="Bali Culture">
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

            <p class="label-tag">Join CittaLoka</p>
            <h1 class="auth-title">Choose Your Role</h1>
            <p class="auth-subtitle">Tell us how you'd like to use CittaLoka.</p>

            <div class="role-grid">
                {{-- Host Card --}}
                <div
                    class="role-card {{ $role === 'host' ? 'selected' : '' }}"
                    wire:click="selectRole('host')"
                >
                    <div class="role-icon">{{ $role === 'host' ? '🏠' : '🏠' }}</div>
                    <div>
                        <div class="role-name">Host</div>
                        <div class="role-desc">Share your culture & skills</div>
                    </div>
                </div>

                {{-- Traveler Card --}}
                <div
                    class="role-card {{ $role === 'user' ? 'selected' : '' }}"
                    wire:click="selectRole('user')"
                >
                    <div class="role-icon">🌿</div>
                    <div>
                        <div class="role-name">Traveler</div>
                        <div class="role-desc">Discover authentic Bali</div>
                    </div>
                </div>
            </div>

            <button
                class="btn-primary"
                wire:click="continue"
                {{ !$role ? 'disabled' : '' }}
            >
                Continue as {{ $role === 'host' ? 'Host' : ($role === 'user' ? 'Traveler' : '...') }} →
            </button>

            <p class="signin-link">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </p>

        </div>
    </div>

</div>

@livewireScripts
</body>
</html>