<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - CittaLoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans" style="font-family: 'DM Sans', sans-serif;">

    <div class="grid grid-cols-1 lg:grid-cols-2 min-h-screen">

        {{-- Kolom Kiri: Foto --}}
        <div class="relative hidden lg:flex flex-col justify-end" style="min-height: 100vh;">
            {{-- Foto asli seperti desain --}}
            <img src="{{ asset('images/auth/travelling.png') }}" class="absolute inset-0 w-full h-full object-cover">
            {{-- Overlay tipis di bawah saja --}}
            <div class="absolute inset-0"
                style=" background: linear-gradient( to bottom,rgba(28,43,30,0.25) 0%, rgba(28,43,30,0.85) 100% );   ">
            </div>
            {{-- Logo --}}
            <div class="absolute top-12 left-12 z-10 flex items-center gap-3">

                <div class="w-2 h-2 rounded-full" style="background:#C4783A;">
                </div>

                <span class="text-white text-[26px] font-bold tracking-tight"
                    style="font-family:'Playfair Display', serif;">
                    CITTALOKA
                </span>

            </div>
            {{-- Teks pojok kiri bawah --}}
            <div class="relative z-10 px-12 pb-12 max-w-[520px]">

                <h2 class="text-white text-[42px] leading-[1.2] font-normal mb-4"
                    style="font-family:'Playfair Display', serif;">
                    Begin your cultural journey.
                </h2>

                <p class="text-[14px] italic leading-relaxed" style="color: rgba(255,255,255,0.8);">
                    Connect with local hosts, discover authentic traditions,
                    and experience cultures beyond the ordinary.
                </p>

            </div>

        </div>

        {{-- Kolom Kanan: Form --}}
        <div class="flex items-center justify-center px-10 py-6 overflow-y-auto" style="background: #FAFAF8;">
            <div class="w-full" style="max-width: 430px;">

                {{-- Label & Title --}}
                <p class="text-[11px] font-medium uppercase tracking-widest mb-1.5"
                    style="color: #C4783A; letter-spacing: 0.15em;">
                    Create Your Account
                </p>
                <h1 class="font-normal mb-4"
                    style="font-family: 'Playfair Display', serif; font-size: 28px; color: #1a2e1c; line-height: 1.15;">
                    Welcome to CittaLoka
                </h1>

                {{-- Error --}}
                @if ($errors->any())
                    <div class="rounded-lg px-4 py-3 mb-5 text-sm"
                        style="background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626;">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- Google Button --}}
                <a href="{{ route('auth.google.redirect') }}"
                    class="w-full flex items-center justify-center gap-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-sm mb-4"
                    style="background: white; border: 1.5px solid #E2DDD5; color: #1a2e1c;"
                    onmouseover="this.style.borderColor='#1a2e1c'" onmouseout="this.style.borderColor='#E2DDD5'">
                    <svg width="18" height="18" viewBox="0 0 48 48">
                        <path fill="#EA4335"
                            d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                        <path fill="#4285F4"
                            d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" />
                        <path fill="#FBBC05"
                            d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" />
                        <path fill="#34A853"
                            d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                    </svg>
                    Continue with Google
                </a>

                {{-- Divider --}}
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex-1 h-px" style="background: #E2DDD5;"></div>
                    <span class="text-[10px] uppercase tracking-widest"
                        style="color: #B0A898; letter-spacing: 0.12em;">or register with email</span>
                    <div class="flex-1 h-px" style="background: #E2DDD5;"></div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('register.store') }}"
                    x-data="{ showPass: false, showConfirm: false }">
                    @csrf
                    <input type="hidden" name="role" value="{{ session('register_role', 'user') }}">

                    {{-- Full Name --}}
                    <div class="mb-2.5">
                        <label for="name" class="block text-[11px] font-medium uppercase tracking-widest mb-1"
                            style="color: #6B7280; letter-spacing: 0.1em;">
                            Full Name
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Your full name"
                            class="w-full px-3 py-1.5 rounded-lg text-sm outline-none transition-all duration-200"
                            style="background: white; border: 1.5px solid {{ $errors->has('name') ? '#EF4444' : '#E2DDD5' }}; color: #1a2e1c;"
                            onfocus="this.style.borderColor='#1a2e1c'"
                            onblur="this.style.borderColor='{{ $errors->has('name') ? '#EF4444' : '#E2DDD5' }}'"
                            required autofocus>
                        @error('name')
                            <p class="text-xs mt-1" style="color: #EF4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-2.5">
                        <label for="email" class="block text-[11px] font-medium uppercase tracking-widest mb-1"
                            style="color: #6B7280; letter-spacing: 0.1em;">
                            Email Address
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="you@example.com"
                            class="w-full px-3 py-1.5 rounded-lg text-sm outline-none transition-all duration-200"
                            style="background: white; border: 1.5px solid {{ $errors->has('email') ? '#EF4444' : '#E2DDD5' }}; color: #1a2e1c;"
                            onfocus="this.style.borderColor='#1a2e1c'"
                            onblur="this.style.borderColor='{{ $errors->has('email') ? '#EF4444' : '#E2DDD5' }}'"
                            required>
                        @error('email')
                            <p class="text-xs mt-1" style="color: #EF4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-2.5">
                        <label for="password" class="block text-[11px] font-medium uppercase tracking-widest mb-1"
                            style="color: #6B7280; letter-spacing: 0.1em;">
                            Password
                        </label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" id="password" name="password"
                                placeholder="Min. 8 characters"
                                class="w-full px-3 py-1.5 pr-10 rounded-lg text-sm outline-none transition-all duration-200"
                                style="background: white; border: 1.5px solid #E2DDD5; color: #1a2e1c;"
                                onfocus="this.style.borderColor='#1a2e1c'" onblur="this.style.borderColor='#E2DDD5'"
                                required>
                            <button type="button" @click="showPass = !showPass"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-sm transition-colors hover:text-gray-700"
                                style="color: #B0A898;">
                                <!-- Simple Eye icon -->
                                <svg x-show="!showPass" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="18"
                                    height="18">
                                    <path
                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <!-- Simple Eye off icon -->
                                <svg x-show="showPass" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    style="display: none;" width="18" height="18">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                    <line x1="2" x2="22" y1="2" y2="22" />
                                </svg>
                            </button>
                        </div>
                        {{-- Password strength indicator --}}
                        <div class="flex gap-1 mt-1.5">
                            <div class="h-0.5 flex-1 rounded-full" style="background: #E2DDD5;"></div>
                            <div class="h-0.5 flex-1 rounded-full" style="background: #E2DDD5;"></div>
                            <div class="h-0.5 flex-1 rounded-full" style="background: #E2DDD5;"></div>
                            <div class="h-0.5 flex-1 rounded-full" style="background: #E2DDD5;"></div>
                        </div>
                        @error('password')
                            <p class="text-xs mt-1" style="color: #EF4444;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label for="password_confirmation"
                            class="block text-[11px] font-medium uppercase tracking-widest mb-1"
                            style="color: #6B7280; letter-spacing: 0.1em;">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation"
                                name="password_confirmation" placeholder="Re-type password"
                                class="w-full px-3 py-1.5 pr-10 rounded-lg text-sm outline-none transition-all duration-200"
                                style="background: white; border: 1.5px solid #E2DDD5; color: #1a2e1c;"
                                onfocus="this.style.borderColor='#1a2e1c'" onblur="this.style.borderColor='#E2DDD5'"
                                required>
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-sm transition-colors hover:text-gray-700"
                                style="color: #B0A898;">
                                <!-- Simple Eye icon -->
                                <svg x-show="!showConfirm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="18"
                                    height="18">
                                    <path
                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <!-- Simple Eye off icon -->
                                <svg x-show="showConfirm" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    style="display: none;" width="18" height="18">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                    <line x1="2" x2="22" y1="2" y2="22" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Checkbox --}}
                    <div class="flex items-start gap-3 mb-3">
                        <input type="checkbox" id="terms" name="terms" class="mt-0.5 flex-shrink-0 cursor-pointer"
                            style="width: 16px; height: 16px; accent-color: #1a2e1c;" required>
                        <label for="terms" class="text-sm cursor-pointer leading-relaxed" style="color: #6B7280;">
                            I agree to CittaLoka's
                            <a href="#" style="color: #C4783A;" class="hover:underline">Terms of Service</a>
                            and
                            <a href="#" style="color: #C4783A;" class="hover:underline">Privacy Policy</a>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full py-2.5 rounded-lg text-sm font-medium text-white transition-all duration-200 hover:-translate-y-0.5"
                        style="background: #1a2e1c;" onmouseover="this.style.background='#2D4A32'"
                        onmouseout="this.style.background='#1a2e1c'">
                        Create Account
                    </button>
                </form>

                {{-- Note --}}
                <p class="text-[11px] text-center mt-3 leading-tight" style="color: #B0A898;">
                    By creating an account, you agree to receive cultural updates and booking notifications.
                </p>

                {{-- Sign In Link --}}
                <p class="text-center mt-4 text-[13px]" style="color: #6B7280;">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium hover:underline" style="color: #1a2e1c;">Sign
                        In</a>
                </p>

            </div>
        </div>

    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>