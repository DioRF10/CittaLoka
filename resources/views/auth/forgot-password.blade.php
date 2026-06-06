<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CittaLoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-[#FAFAF8]" style="font-family: 'DM Sans', sans-serif;">

<div class="w-full px-6 py-8" style="max-width: 480px;">
    <div class="rounded-[32px] border border-[#E8E4DC] bg-white px-8 py-10 shadow-[0_30px_60px_-40px_rgba(15,23,42,0.25)]">

        <div class="flex items-center justify-center mb-6">
            <div class="w-16 h-16 rounded-3xl flex items-center justify-center" style="background: #F0EDE6;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1A2E1C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 12c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8-8-3.582-8-8Z"/>
                    <path d="M9.5 12.5l1.5 1.5 4-4"/>
                </svg>
            </div>
        </div>

        <p class="text-[11px] font-medium uppercase tracking-[0.23em] mb-3" style="color: #C4783A;">
            Account Recovery
        </p>

        <h1 class="font-normal mb-3" style="font-family: 'Playfair Display', serif; font-size: 34px; color: #1A2E1C; line-height: 1.15;">
            Forgot your password?
        </h1>

        <p class="text-sm leading-relaxed mb-8" style="color: #6B7280;">
            No worries. Enter your email and we'll send a secure link to reset your password.
        </p>

        @if (session('status'))
            <div class="rounded-2xl px-4 py-3 mb-5 text-sm" style="background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl px-4 py-3 mb-5 text-sm text-left" style="background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6 text-left">
                <label for="email" class="block text-[11px] font-medium uppercase tracking-[0.15em] mb-1.5" style="color: #6B7280;">
                    Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required autofocus
                    class="w-full rounded-2xl border border-[#E2DDD5] bg-white px-4 py-3 text-sm text-[#1A2E1C] outline-none transition duration-200 focus:border-[#1A2E1C] focus:ring-2 focus:ring-[#C8E6C9]"
                >
            </div>

            <button type="submit" class="w-full rounded-2xl bg-[#1A2E1C] py-4 text-sm font-medium text-white transition duration-200 hover:bg-[#2D4A32]">
                Send Reset Link →
            </button>
        </form>

        <p class="text-sm text-[#6B7280] mt-5">
            <a href="{{ route('login') }}" class="font-medium text-[#C4783A] hover:text-[#1A2E1C]">
                ← Back to sign in
            </a>
        </p>

    </div>
</div>

</body>
</html>