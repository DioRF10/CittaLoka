<?php

use function Livewire\Volt\{computed};

$user = computed(fn() => auth()->user());

?>

<nav class="w-full border-b sticky top-0 z-50" style="background: #FAFAF8; border-color: #E8E4DC;" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">

        {{-- Logo --}}
        <a href="/" class="flex-shrink-0">
            <img src="{{ asset('images/auth/bb.png') }}" alt="CittaLoka" class="h-10 sm:h-12 w-auto">
        </a>

        {{-- Menu Tengah (desktop) --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="/experiences"
                class="text-sm font-medium transition-colors hover:text-[#1a2e1c] {{ request()->is('experiences*') ? 'text-[#1a2e1c]' : 'text-[#6B7280]' }}">
                Explore
            </a>
            <a href="/soul-match"
                class="text-sm font-medium transition-colors hover:text-[#1a2e1c] {{ request()->is('soul-match*') ? 'text-[#1a2e1c]' : 'text-[#6B7280]' }}">
                Soul Match
            </a>

            <a href="/seasonal-calendar"
                class="text-sm font-medium transition-colors hover:text-[#1a2e1c] {{ request()->is('seasonal-calendar*') ? 'text-[#1a2e1c]' : 'text-[#6B7280]' }}">
                Seasonal Calendar
            </a>
        </div>

        {{-- Kanan --}}
        <div class="flex items-center gap-1.5 sm:gap-3">

            {{-- Language Toggle --}}
            <button
                class="hidden sm:inline-block text-xs font-medium px-3 py-1.5 rounded-full transition duration-200 hover:bg-[#F0EDE6] hover:text-[#1a2e1c] hover:shadow-sm cursor-pointer"
                style="color: #6B7280; border: 1px solid transparent;">
                EN
            </button>

            @guest
                {{-- Kondisi 1: Guest --}}
                <a href="{{ route('login') }}"
                    class="text-xs sm:text-sm font-medium px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-lg transition-colors hover:bg-[#F0EDE6] whitespace-nowrap"
                    style="color: #1a2e1c;">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="text-xs sm:text-sm font-medium px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-lg text-white transition-all hover:-translate-y-0.5 whitespace-nowrap"
                    style="background: #1a2e1c;" onmouseover="this.style.background='#2D4A32'"
                    onmouseout="this.style.background='#1a2e1c'">
                    Sign Up
                </a>
            @endguest

            @auth
                {{-- Notifikasi Bell — dropdown fungsional --}}
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-lg hover:bg-[#F0EDE6] transition-colors" aria-label="Notifikasi">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                        </svg>
                        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-0.5 right-0.5 flex items-center justify-center text-[10px] font-bold text-white rounded-full"
                                style="background:#C4783A; min-width:16px; height:16px; padding:0 3px;">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="notifOpen" @click.outside="notifOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        style="display:none; background-color:#FFFFFF;"
                        class="absolute right-0 mt-2 w-80 rounded-xl border border-[#E8E4DC] bg-white shadow-lg z-50 max-h-96 overflow-y-auto">

                        <div class="px-4 py-3 border-b flex items-center justify-between" style="border-color:#E8E4DC;">
                            <span class="text-sm font-semibold" style="color:#1a2e1c;">Notification</span>
                            <div style="display:flex; align-items:center; gap:0.6rem;">
                                @if($unreadCount > 0)
                                    <form method="POST" action="{{ route('notifications.read-all') }}">
                                        @csrf
                                        <button type="submit" class="text-xs hover:underline" style="color:#C4783A;">Tandai dibaca</button>
                                    </form>
                                @endif
                                @php $totalNotifs = auth()->user()->notifications()->count(); @endphp
                                @if($totalNotifs > 0)
                                    <form method="POST" action="{{ route('notifications.delete-all') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs hover:underline" style="color:#9CA3AF;" onclick="return confirm('Hapus semua notifikasi?')">Hapus semua</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @php $recentNotifs = auth()->user()->notifications()->latest()->take(8)->get(); @endphp

                        @forelse($recentNotifs as $notif)
                            <a href="{{ route('notifications.click', $notif->id) }}"
                                class="block px-4 py-3 border-b hover:bg-[#F5F2EC] transition-colors"
                                style="border-color:#F0EDE6; {{ $notif->read_at ? '' : 'background:#FBF8F3;' }}">
                                <div class="flex items-start gap-2">
                                    @if(!$notif->read_at)
                                        <span class="mt-1.5 w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:#C4783A;"></span>
                                    @else
                                        <span class="mt-1.5 w-1.5 h-1.5 flex-shrink-0"></span>
                                    @endif
                                    <div class="flex-1">
                                        <p class="text-sm font-medium" style="color:#1a2e1c;">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
                                        <p class="text-xs mt-0.5" style="color:#6B7280;">{{ $notif->data['message'] ?? '' }}</p>
                                        <p class="text-[11px] mt-1" style="color:#9CA3AF;">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-8 text-center">
                                <p class="text-sm" style="color:#9CA3AF;">Belum ada notifikasi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if(auth()->user()->role === 'host')
                    {{-- Kondisi 3: Host --}}
                    <a href="/dashboard" class="text-sm font-medium px-4 py-2 rounded-lg transition-colors hover:bg-[#F0EDE6]"
                        style="color: #1a2e1c;">
                        Dashboard
                    </a>
                @endif

                {{-- Avatar Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-full border transition-all duration-200 hover:bg-[#F5F2EC] hover:border-[#C4BEB1] cursor-pointer"
                        style="border-color: #E8E4DC;" aria-label="Open profile menu">

                        {{-- Avatar --}}
                        <div class="w-9 h-9 rounded-full overflow-hidden flex items-center justify-center"
                            style="background: #F0EDE6;">

                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatarUrl() }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <span class="text-sm font-medium" style="color: #1a2e1c;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            @endif

                        </div>

                        {{-- Chevron --}}
                        <svg class="w-4 h-4 text-[#6B7280] transition-transform duration-200"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95" style="display:none; background-color: #FFFFFF;"
                        class="absolute right-0 mt-2 w-52 rounded-xl border border-[#E8E4DC] bg-white shadow-lg py-1 z-50">

                        <div class="px-4 py-3 border-b" style="border-color: #E8E4DC;">
                            <p class="text-sm font-medium truncate" style="color: #1a2e1c;">{{ auth()->user()->name }}</p>
                            <p class="text-xs truncate mt-0.5" style="color: #9CA3AF;">{{ auth()->user()->email }}</p>
                        </div>

                        @if(auth()->user()->role === 'host')
                            <a href="/dashboard"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm rounded-xl hover:bg-[#F5F2EC] hover:text-[#1a2e1c] transition-colors duration-150 cursor-pointer"
                                style="color: #1a2e1c;">
                                Dashboard
                            </a>
                            <a href="/dashboard/experiences"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm rounded-xl hover:bg-[#F5F2EC] hover:text-[#1a2e1c] transition-colors duration-150 cursor-pointer"
                                style="color: #1a2e1c;">
                                My Experiences
                            </a>
                            <a href="/dashboard/bookings"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm rounded-xl hover:bg-[#F5F2EC] hover:text-[#1a2e1c] transition-colors duration-150 cursor-pointer"
                                style="color: #1a2e1c;">
                                Bookings
                            </a>
                            <a href="/dashboard/earnings"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm rounded-xl hover:bg-[#F5F2EC] hover:text-[#1a2e1c] transition-colors duration-150 cursor-pointer"
                                style="color: #1a2e1c;">
                                Earnings
                            </a>
                        @else
                            <a href="{{ route('bookings.index') }}"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm rounded-xl hover:bg-[#F5F2EC] hover:text-[#1a2e1c] transition-colors duration-150 cursor-pointer"
                                style="color: #1a2e1c;">
                                My Bookings
                            </a>
                            <a href="{{ route('memory-books.index') }}"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm rounded-xl hover:bg-[#F5F2EC] hover:text-[#1a2e1c] transition-colors duration-150 cursor-pointer"
                                style="color: #1a2e1c;">
                                My Memory Books
                            </a>
                            <a href="/wishlist"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm rounded-xl hover:bg-[#F5F2EC] hover:text-[#1a2e1c] transition-colors duration-150 cursor-pointer"
                                style="color: #1a2e1c;">
                                Wishlist
                            </a>
                            <a href="{{ route('my-profile.index') }}"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm rounded-xl hover:bg-[#F5F2EC] hover:text-[#1a2e1c] transition-colors duration-150 cursor-pointer"
                                style="color: #1a2e1c;">
                                My Profile
                            </a>
                        @endif

                        <div class="border-t mt-1" style="border-color: #E8E4DC;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-sm rounded-xl hover:bg-[#FEF2F2] hover:text-[#B91C1C] transition-colors duration-150 cursor-pointer"
                                    style="color: #EF4444;">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth

            {{-- Hamburger (mobile only) --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg hover:bg-[#F0EDE6] transition-colors" aria-label="Menu">
                <svg x-show="!mobileMenuOpen" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
                <svg x-show="mobileMenuOpen" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>

        </div>

    </div>

    {{-- Mobile Menu Panel --}}
    <div x-show="mobileMenuOpen" x-cloak @click.outside="mobileMenuOpen = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="md:hidden border-t" style="border-color: #E8E4DC; background: #FAFAF8;">
        <div class="px-4 py-3 flex flex-col gap-1">
            <a href="/experiences" @click="mobileMenuOpen = false"
                class="px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->is('experiences*') ? 'text-[#1a2e1c] bg-[#F0EDE6]' : 'text-[#4A4A4A]' }}">
                Explore
            </a>
            <a href="/soul-match" @click="mobileMenuOpen = false"
                class="px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->is('soul-match*') ? 'text-[#1a2e1c] bg-[#F0EDE6]' : 'text-[#4A4A4A]' }}">
                Soul Match
            </a>
            <a href="/about" @click="mobileMenuOpen = false"
                class="px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->is('about*') ? 'text-[#1a2e1c] bg-[#F0EDE6]' : 'text-[#4A4A4A]' }}">
                About
            </a>
            <a href="/seasonal-calendar" @click="mobileMenuOpen = false"
                class="px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->is('seasonal-calendar*') ? 'text-[#1a2e1c] bg-[#F0EDE6]' : 'text-[#4A4A4A]' }}">
                Seasonal Calendar
            </a>
            <div class="border-t my-1" style="border-color: #E8E4DC;"></div>
            <button class="text-left px-3 py-2.5 rounded-lg text-sm font-medium text-[#4A4A4A] sm:hidden">
                🌐 English
            </button>
        </div>
    </div>
</nav>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>