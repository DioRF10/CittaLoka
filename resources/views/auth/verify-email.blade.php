<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - CittaLoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center"
    style="background: #FAFAF8; font-family: 'DM Sans', sans-serif;">

    <div class="w-full text-center px-8" style="max-width: 480px;">

        {{-- Icon --}}
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background: #F0EDE6;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="16" x="2" y="4" rx="2" />
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
            </svg>
        </div>

        <p class="text-xs font-medium uppercase tracking-widest mb-3" style="color: #C4783A; letter-spacing: 0.15em;">
            Almost there!</p>

        <h1 class="font-normal mb-4" style="font-family: 'Playfair Display', serif; font-size: 32px; color: #1a2e1c;">
            Check your email
        </h1>

        <p class="text-sm leading-relaxed mb-8" style="color: #6B7280;">
            We've sent a verification link to
            <strong style="color: #1a2e1c;">{{ auth()->user()->email }}</strong>.
            Click the link in the email to activate your account.
        </p>

        {{-- Status resend --}}
        @if (session('status') == 'verification-link-sent')
            <div class="rounded-lg px-4 py-3 mb-5 text-sm"
                style="background: #F0FDF4; border: 1px solid #BBF7D0; color: #16A34A;">
                ✓ New verification email sent!
            </div>
        @endif

        {{-- Resend button --}}
        <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
            @csrf
            <button type="submit"
                class="w-full py-4 rounded-lg text-sm font-medium text-white transition-all duration-200 hover:-translate-y-0.5"
                style="background: #1a2e1c;" onmouseover="this.style.background='#2D4A32'"
                onmouseout="this.style.background='#1a2e1c'">
                Resend Verification Email
            </button>
        </form>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm hover:underline" style="color: #9CA3AF;">
                Log out and use different account
            </button>
        </form>

    </div>

</body>

</html>