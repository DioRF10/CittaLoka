<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Your Inbox - CittaLoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-[#FAFAF8]" style="font-family: 'DM Sans', sans-serif;">

<div class="w-full px-6 py-8" style="max-width: 480px;" x-data="countdown()">
    <div class="rounded-[32px] border border-[#E8E4DC] bg-white px-8 py-10 shadow-[0_30px_60px_-40px_rgba(15,23,42,0.25)] text-center">

        <div class="mx-auto mb-6 w-20 h-20 rounded-3xl flex items-center justify-center" style="background: #F0EDE6;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#1A2E1C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="16" x="2" y="4" rx="2"/>
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
        </div>

        <p class="text-[11px] font-medium uppercase tracking-[0.23em] mb-3" style="color: #C4783A;">
            Email Sent
        </p>

        <h1 class="font-normal mb-4" style="font-family: 'Playfair Display', serif; font-size: 34px; color: #1A2E1C; line-height: 1.15;">
            Check your inbox
        </h1>

        <p class="text-sm leading-relaxed mb-8" style="color: #6B7280;">
            We sent a password reset link to
            <strong style="color: #1A2E1C;">{{ session('reset_email', 'your email') }}</strong>.
            The link expires in 30 minutes.
        </p>

        <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full mb-8" style="background: #F0EDE6; border: 1px solid #E0DBD0; color: #1A2E1C;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1A2E1C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span class="text-sm font-medium" x-text="timeLeft"></span>
        </div>

        <div class="grid gap-3 mb-6">
            <a href="mailto:" class="w-full rounded-2xl bg-[#1A2E1C] py-4 text-sm font-medium text-white transition duration-200 hover:bg-[#2D4A32]">
                Open Email App →
            </a>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <input type="hidden" name="email" value="{{ session('reset_email') }}">
                <button type="submit" class="w-full rounded-2xl border border-[#E2DDD5] bg-white py-4 text-sm font-medium text-[#1A2E1C] transition duration-200 hover:border-[#1A2E1C]">
                    Resend Email
                </button>
            </form>
        </div>

        <p class="text-sm text-[#6B7280]">
            <a href="{{ route('login') }}" class="font-medium text-[#C4783A] hover:text-[#1A2E1C]">
                ← Back to sign in
            </a>
        </p>

    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function countdown() {
    return {
        seconds: 30 * 60,
        timeLeft: '30:00',
        init() {
            const interval = setInterval(() => {
                if (this.seconds <= 0) {
                    clearInterval(interval);
                    this.timeLeft = 'Expired';
                    return;
                }
                this.seconds--;
                const m = Math.floor(this.seconds / 60).toString().padStart(2, '0');
                const s = (this.seconds % 60).toString().padStart(2, '0');
                this.timeLeft = `${m}:${s}`;
            }, 1000);
        }
    }
}
</script>
</body>
</html>