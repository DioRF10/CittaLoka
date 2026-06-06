<?php

use function Livewire\Volt\{computed};

$user = computed(fn() => auth()->user());

?>

<nav class="w-full border-b sticky top-0 z-50" style="background: #FAFAF8; border-color: #E8E4DC;">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- Logo --}}
        <a href="/" class="flex-shrink-0">
            <span class="text-lg font-semibold tracking-wide" style="color: #1a2e1c; font-family: 'DM Sans', sans-serif; letter-spacing: 0.05em;">
                CITTALOKA
            </span>
        </a>

        {{-- Menu Tengah --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="/experiences"
               class="text-sm font-medium transition-colors hover:text-[#1a2e1c] {{ request()->is('experiences*') ? 'text-[#1a2e1c]' : 'text-[#6B7280]' }}">
                Explore
            </a>
            <a href="/soul-match"
               class="text-sm font-medium transition-colors hover:text-[#1a2e1c] {{ request()->is('soul-match*') ? 'text-[#1a2e1c]' : 'text-[#6B7280]' }}">
                Soul Match
            </a>
            <a href="/about"
               class="text-sm font-medium transition-colors hover:text-[#1a2e1c] {{ request()->is('about*') ? 'text-[#1a2e1c]' : 'text-[#6B7280]' }}">
                About
            </a>
            <a href="/seasonal-calendar"
               class="text-sm font-medium transition-colors hover:text-[#1a2e1c] {{ request()->is('seasonal-calendar*') ? 'text-[#1a2e1c]' : 'text-[#6B7280]' }}">
                Seasonal Calendar
            </a>
        </div>

        {{-- Kanan --}}
        <div class="flex items-center gap-3">

            {{-- Language Toggle --}}
            <button class="text-xs font-medium px-2 py-1 rounded transition-colors hover:bg-[#F0EDE6]"
                    style="color: #6B7280;">
                EN
            </button>

            @guest
                {{-- Kondisi 1: Guest --}}
                <a href="{{ route('login') }}"
                   class="text-sm font-medium px-4 py-2 rounded-lg transition-colors hover:bg-[#F0EDE6]"
                   style="color: #1a2e1c;">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="text-sm font-medium px-4 py-2 rounded-lg text-white transition-all hover:-translate-y-0.5"
                   style="background: #1a2e1c;"
                   onmouseover="this.style.background='#2D4A32'"
                   onmouseout="this.style.background='#1a2e1c'">
                    Sign Up
                </a>
            @endguest

            @auth
                {{-- Notifikasi Bell --}}
                <a href="/notifications" class="relative p-2 rounded-lg hover:bg-[#F0EDE6] transition-colors">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                </a>

                @if(auth()->user()->role === 'host')
                    {{-- Kondisi 3: Host --}}
                    <a href="/dashboard"
                       class="text-sm font-medium px-4 py-2 rounded-lg transition-colors hover:bg-[#F0EDE6]"
                       style="color: #1a2e1c;">
                        Dashboard
                    </a>
                @endif

                {{-- Avatar Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="w-9 h-9 rounded-full overflow-hidden border-2 flex items-center justify-center transition-all"
                            style="border-color: #E8E4DC; background: #F0EDE6;">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <span class="text-sm font-medium" style="color: #1a2e1c;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open"
                         @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="display:none;"
                         class="absolute right-0 mt-2 w-52 rounded-xl shadow-lg py-1 z-50"
                         style="background: white; border: 1px solid #E8E4DC;">

                        <div class="px-4 py-3 border-b" style="border-color: #E8E4DC;">
                            <p class="text-sm font-medium truncate" style="color: #1a2e1c;">{{ auth()->user()->name }}</p>
                            <p class="text-xs truncate mt-0.5" style="color: #9CA3AF;">{{ auth()->user()->email }}</p>
                        </div>

                        @if(auth()->user()->role === 'host')
                            <a href="/dashboard" class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-[#F5F2EC] transition-colors" style="color: #1a2e1c;">
                                Dashboard
                            </a>
                            <a href="/dashboard/experiences" class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-[#F5F2EC] transition-colors" style="color: #1a2e1c;">
                                My Experiences
                            </a>
                            <a href="/dashboard/bookings" class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-[#F5F2EC] transition-colors" style="color: #1a2e1c;">
                                Bookings
                            </a>
                            <a href="/dashboard/earnings" class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-[#F5F2EC] transition-colors" style="color: #1a2e1c;">
                                Earnings
                            </a>
                        @else
                            <a href="/my-bookings" class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-[#F5F2EC] transition-colors" style="color: #1a2e1c;">
                                My Bookings
                            </a>
                            <a href="/wishlist" class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-[#F5F2EC] transition-colors" style="color: #1a2e1c;">
                                Wishlist
                            </a>
                            <a href="/profile" class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-[#F5F2EC] transition-colors" style="color: #1a2e1c;">
                                My Profile
                            </a>
                        @endif

                        <div class="border-t mt-1" style="border-color: #E8E4DC;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-[#FEF2F2] transition-colors" style="color: #EF4444;">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth

        </div>

    </div>
</nav>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>