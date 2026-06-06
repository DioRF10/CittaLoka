<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CittaLoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-[#FAFAF8]" style="font-family: 'DM Sans', sans-serif;">

<div class="w-full px-6 py-8" style="max-width: 480px;">
    <div class="rounded-[32px] border border-[#E8E4DC] bg-white px-8 py-10 shadow-[0_30px_60px_-40px_rgba(15,23,42,0.25)]">

        <div class="text-center mb-8">
            <div class="mx-auto mb-6 w-16 h-16 rounded-3xl flex items-center justify-center" style="background: #F0EDE6;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1A2E1C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="7.5" cy="15.5" r="5.5"/>
                    <path d="m21 2-9.6 9.6"/>
                    <path d="m15.5 7.5 3 3L22 7l-3-3"/>
                </svg>
            </div>

            <p class="text-[11px] font-medium uppercase tracking-[0.23em] mb-3" style="color: #C4783A;">
                Create New Password
            </p>

            <h1 class="font-normal mb-3" style="font-family: 'Playfair Display', serif; font-size: 34px; color: #1A2E1C; line-height: 1.15;">
                Set a new password
            </h1>

            <p class="text-sm leading-relaxed" style="color: #6B7280;">
                Your new password must be different from your previous one.
            </p>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl px-4 py-3 mb-5 text-sm text-left" style="background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" x-data="passwordValidator()" class="space-y-5 text-left">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

            <div>
                <label for="password" class="block text-[11px] font-medium uppercase tracking-[0.15em] mb-1.5" style="color: #6B7280;">
                    New Password
                </label>
                <div class="relative">
                    <input
                        :type="showPass ? 'text' : 'password'"
                        id="password"
                        name="password"
                        x-model="password"
                        placeholder="newPass123"
                        required
                        class="w-full rounded-2xl border border-[#E2DDD5] bg-white px-4 py-3 pr-12 text-sm text-[#1A2E1C] outline-none transition duration-200 focus:border-[#1A2E1C] focus:ring-2 focus:ring-[#C8E6C9]"
                    >
                    <button type="button" @click="showPass = !showPass" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#B0A898]">
                        <svg x-show="!showPass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="18" height="18">
                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="showPass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" style="display: none;">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <line x1="2" x2="22" y1="2" y2="22"/>
                        </svg>
                    </button>
                </div>
                <div class="flex gap-1 mt-1.5">
                    <div class="h-0.5 flex-1 rounded-full transition-colors duration-300" :style="strength >= 1 ? 'background: #2E5E32' : 'background: #E2DDD5'"></div>
                    <div class="h-0.5 flex-1 rounded-full transition-colors duration-300" :style="strength >= 2 ? 'background: #2E5E32' : 'background: #E2DDD5'"></div>
                    <div class="h-0.5 flex-1 rounded-full transition-colors duration-300" :style="strength >= 3 ? 'background: #2E5E32' : 'background: #E2DDD5'"></div>
                    <div class="h-0.5 flex-1 rounded-full transition-colors duration-300" :style="strength >= 4 ? 'background: #2E5E32' : 'background: #E2DDD5'"></div>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-[11px] font-medium uppercase tracking-[0.15em] mb-1.5" style="color: #6B7280;">
                    Confirm New Password
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="newPass123"
                    required
                    class="w-full rounded-2xl border border-[#E2DDD5] bg-white px-4 py-3 text-sm text-[#1A2E1C] outline-none transition duration-200 focus:border-[#1A2E1C] focus:ring-2 focus:ring-[#C8E6C9]"
                >
            </div>

            <div class="rounded-2xl p-4 mb-6" style="background: #F5F2EC;">
                <div class="flex items-center gap-2.5 mb-2">
                    <svg :style="has8chars ? 'color: #2E5E32' : 'color: #B0A898'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    <span class="text-sm transition-colors duration-200" :style="has8chars ? 'color: #1A2E1C' : 'color: #9CA3AF'">At least 8 characters</span>
                </div>
                <div class="flex items-center gap-2.5 mb-2">
                    <svg :style="hasNumber ? 'color: #2E5E32' : 'color: #B0A898'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    <span class="text-sm transition-colors duration-200" :style="hasNumber ? 'color: #1A2E1C' : 'color: #9CA3AF'">Contains a number</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <svg :style="hasUpper ? 'color: #2E5E32' : 'color: #B0A898'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    <span class="text-sm transition-colors duration-200" :style="hasUpper ? 'color: #1A2E1C' : 'color: #9CA3AF'">Contains uppercase letter</span>
                </div>
            </div>

            <button type="submit" class="w-full rounded-2xl bg-[#1A2E1C] py-4 text-sm font-medium text-white transition duration-200 hover:bg-[#2D4A32]">
                Reset Password →
            </button>
        </form>

    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function passwordValidator() {
    return {
        password: '',
        showPass: false,
        get has8chars() { return this.password.length >= 8 },
        get hasNumber() { return /\d/.test(this.password) },
        get hasUpper() { return /[A-Z]/.test(this.password) },
        get strength() {
            let s = 0;
            if (this.has8chars) s++;
            if (this.hasNumber) s++;
            if (this.hasUpper) s++;
            if (this.password.length >= 12) s++;
            return s;
        }
    }
}
</script>
</body>
</html>