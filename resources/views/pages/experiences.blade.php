@extends('layouts.app')

@section('title', 'Explore Experiences — CittaLoka')

@section('content')

    {{-- ===================== HEADER ===================== --}}
    <section class="relative overflow-hidden py-20 text-center" 
        style="background: url('/images/bali-hero-bg.png') center/cover no-repeat; min-height: 280px; display: flex; align-items: center;">
        
        {{-- Overlay gelap agar teks terbaca --}}
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 max-w-2xl mx-auto px-6 w-full mt-4">
            <p class="text-xs font-semibold uppercase tracking-widest mb-3" style="color: #F3D9B8; letter-spacing: 0.2em; text-shadow: 0 1px 4px rgba(0,0,0,0.5);">
                Explore Experiences
            </p>
            <h1 class="font-normal mb-8 text-white"
                style="font-family: 'Playfair Display', serif; font-size: clamp(32px, 5vw, 56px); line-height: 1.15; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                Find Your Perfect Bali Experience
            </h1>

            {{-- Search Bar --}}
            <div class="relative">
                <svg class="absolute left-5 top-1/2 -translate-y-1/2 flex-shrink-0" width="18" height="18"
                    viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <input type="text" id="searchInput" value="{{ request('search') }}"
                    placeholder="Search experiences, hosts, or locations..."
                    class="w-full pl-14 pr-6 py-4 text-sm outline-none transition-all duration-200"
                    style="background: white; border: 1.5px solid #E2DDD5; color: #1a2e1c; border-radius: 999px; box-shadow: 0 2px 12px rgba(0,0,0,0.07);"
                    onfocus="this.style.borderColor='#1a2e1c'; this.style.boxShadow='0 2px 16px rgba(0,0,0,0.12)';"
                    onblur="this.style.borderColor='#E2DDD5'; this.style.boxShadow='0 2px 12px rgba(0,0,0,0.07)';">
            </div>
        </div>
    </section>

    {{-- ===================== FILTER BAR ===================== --}}
    <div class="sticky top-16 z-40 border-b" style="background: #FAFAF8; border-color: #E8E4DC;"
        x-data="filterBar()" x-init="init()">

        {{-- Kategori Tabs --}}
        <div class="max-w-7xl mx-auto px-6 pt-4 pb-3">
            <div class="flex items-center gap-2 overflow-x-auto pb-1" style="scrollbar-width: none;">

                {{-- All button --}}
                <button @click="filters.kategori = ''; applyFilters()"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-full border text-sm font-medium whitespace-nowrap transition-all flex-shrink-0"
                    :class="!filters.kategori
                        ? 'bg-[#1A2E1C] text-white border-[#1A2E1C]'
                        : 'bg-white text-stone-600 border-stone-200 hover:border-stone-400'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    All
                </button>

                @foreach($kategoris as $kat)
                    <button @click="filters.kategori = filters.kategori === '{{ $kat->slug }}' ? '' : '{{ $kat->slug }}'; applyFilters()"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full border text-sm whitespace-nowrap transition-all flex-shrink-0"
                        :class="filters.kategori === '{{ $kat->slug }}'
                            ? 'bg-[#1A2E1C] text-white border-[#1A2E1C]'
                            : 'bg-white text-stone-600 border-stone-200 hover:border-stone-400'">
                        {{-- Ikon per kategori --}}
                        @switch($kat->slug)
                            @case('culture')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 21h18M3 7v1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7H3l2-4h14l2 4"/><path d="M5 21V11m14 10V11M9 21V11m6 10V11"/></svg>
                                @break
                            @case('adventure')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="3 17 12 3 21 17"/><polyline points="9 17 12 14 15 17"/></svg>
                                @break
                            @case('food-drink')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                                @break
                            @case('wellness')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                @break
                            @case('art-craft')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                @break
                            @case('nature')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 8C8 10 5.9 16.17 3.82 22"/><path d="M9.18 22L21 2"/></svg>
                                @break
                            @case('photography')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                @break
                            @default
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/></svg>
                        @endswitch
                        {{ $kat->getNama(app()->getLocale()) }}
                    </button>
                @endforeach

                {{-- More arrow --}}
                <button class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center border transition"
                    style="border-color:#E2DDD5; background:white; color:#1a2e1c;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>

        {{-- Secondary Filter Row --}}
        <div class="max-w-7xl mx-auto px-6 pb-3">
            <div class="flex items-center gap-3 flex-wrap">

                {{-- Location --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all"
                        :style="filters.lokasi
                            ? 'background:#1a2e1c; color:white; border:1.5px solid #1a2e1c;'
                            : 'background:white; border:1.5px solid #E2DDD5; color:#1a2e1c;'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span x-text="filters.lokasi || 'Location'"></span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak x-transition
                        class="absolute top-full left-0 mt-2 rounded-2xl shadow-xl z-50 min-w-44 overflow-hidden"
                        style="background:white; border:1.5px solid #E2DDD5;">
                        <button @click="filters.lokasi = ''; open = false; applyFilters()"
                            class="w-full text-left px-4 py-3 text-sm hover:bg-[#F0EDE6] transition-colors"
                            :style="!filters.lokasi ? 'font-weight:600; color:#1a2e1c;' : 'color:#4A4A4A;'">
                            Semua Lokasi
                        </button>
                        @foreach(['Gianyar', 'Ubud', 'Bangli', 'Badung', 'Tabanan', 'Klungkung', 'Buleleng', 'Jembrana', 'Karangasem'] as $lok)
                            <button @click="filters.lokasi = '{{ $lok }}'; open = false; applyFilters()"
                                class="w-full text-left px-4 py-3 text-sm hover:bg-[#F0EDE6] transition-colors"
                                :style="filters.lokasi === '{{ $lok }}' ? 'font-weight:600; color:#1a2e1c;' : 'color:#4A4A4A;'">
                                {{ $lok }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- All Filters Button (buka sidebar) --}}
                <button @click="showFilters = true"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative"
                    :style="hasActiveSecondaryFilters
                        ? 'background:#1a2e1c; color:white; border:1.5px solid #1a2e1c;'
                        : 'background:white; border:1.5px solid #E2DDD5; color:#1a2e1c;'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="10" y1="18" x2="14" y2="18"/>
                    </svg>
                    All Filters
                    {{-- Dot indicator --}}
                    <span x-show="hasActiveSecondaryFilters"
                        class="w-2 h-2 rounded-full absolute -top-1 -right-1"
                        style="background:#C4783A;"></span>
                </button>

                {{-- Sort --}}
                <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium"
                    style="background:white; border:1.5px solid #E2DDD5; color:#1a2e1c;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M6 12h12M9 18h6"/>
                    </svg>
                    <select x-model="filters.sort" @change="applyFilters()" class="outline-none bg-transparent text-sm"
                        style="color:#1a2e1c;">
                        <option value="relevan">Sort: Relevance</option>
                        <option value="rating">Rating Tertinggi</option>
                        <option value="harga_asc">Harga Terendah</option>
                        <option value="harga_desc">Harga Tertinggi</option>
                    </select>
                </div>

                {{-- Grid/List Toggle (ml-auto) --}}
                <div class="ml-auto flex items-center gap-1 p-1 rounded-lg" style="background:#F0EDE6;">
                    <button @click="viewMode = 'grid'"
                        class="p-2 rounded-md transition-all"
                        :style="viewMode === 'grid' ? 'background:white; box-shadow:0 1px 4px rgba(0,0,0,0.1);' : 'background:transparent;'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="2" stroke-linecap="round">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                        </svg>
                    </button>
                    <button @click="viewMode = 'list'"
                        class="p-2 rounded-md transition-all"
                        :style="viewMode === 'list' ? 'background:white; box-shadow:0 1px 4px rgba(0,0,0,0.1);' : 'background:transparent;'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="2" stroke-linecap="round">
                            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== SIDEBAR ALL FILTERS ===== --}}
        {{-- Backdrop --}}
        <div x-show="showFilters" @click="showFilters = false" x-cloak x-transition:enter="transition-opacity duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50" style="background: rgba(0,0,0,0.35); backdrop-filter: blur(2px);">
        </div>

        {{-- Panel --}}
        <div x-show="showFilters" x-cloak
            x-transition:enter="transition-transform duration-300 ease-out"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform duration-200 ease-in"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="fixed top-0 right-0 h-full z-50 flex flex-col overflow-y-auto"
            style="width: 380px; background: white; box-shadow: -8px 0 40px rgba(0,0,0,0.12);">

            {{-- Panel Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b" style="border-color:#E8E4DC;">
                <h3 class="text-base font-semibold" style="color:#1a2e1c;">All Filters</h3>
                <button @click="showFilters = false" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-stone-100 transition">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            {{-- Panel Body --}}
            <div class="flex-1 px-6 py-6 space-y-8 overflow-y-auto">

                {{-- Price Range --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold" style="color:#1a2e1c;">Price Range</h4>
                        <span class="text-xs" style="color:#9CA3AF;" x-text="getPriceLabel()"></span>
                    </div>
                    <input type="range" min="0" max="1000000" step="50000" x-model="filters.priceMin"
                        class="w-full mb-1 accent-[#1a2e1c]">
                    <input type="range" min="0" max="1000000" step="50000" x-model="filters.priceMax"
                        class="w-full accent-[#1a2e1c]">
                    <div class="flex justify-between mt-2 text-xs" style="color:#9CA3AF;">
                        <span>0</span><span>200k</span><span>350k</span><span>500k</span><span>1M+</span>
                    </div>
                </div>

                {{-- Duration --}}
                <div>
                    <h4 class="text-sm font-semibold mb-3" style="color:#1a2e1c;">Duration</h4>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="opt in durasiOptions" :key="opt.value">
                            <button @click="filters.durasi = filters.durasi === opt.value ? '' : opt.value"
                                class="px-3.5 py-2 rounded-full text-xs font-medium border transition-all"
                                :style="filters.durasi === opt.value
                                    ? 'background:#1A2E1C; color:white; border-color:#1A2E1C;'
                                    : 'background:white; color:#4A4A4A; border-color:#E2DDD5;'"
                                x-text="opt.label"></button>
                        </template>
                    </div>
                </div>

                {{-- Experience Type --}}
                <div>
                    <h4 class="text-sm font-semibold mb-3" style="color:#1a2e1c;">Experience Type</h4>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['Outdoor', 'Indoor', 'Private', 'Family Friendly'] as $tipe)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative w-5 h-5 rounded flex-shrink-0">
                                    <input type="checkbox" class="sr-only peer"
                                        value="{{ $tipe }}"
                                        x-model="filters.tipeArr">
                                    <div class="w-5 h-5 rounded border-2 transition-all peer-checked:border-[#1a2e1c] peer-checked:bg-[#1a2e1c]"
                                        style="border-color:#D1D5DB;"></div>
                                    <svg class="absolute inset-0 m-auto w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </div>
                                <span class="text-sm" style="color:#374151;">{{ $tipe }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Rating --}}
                <div>
                    <h4 class="text-sm font-semibold mb-3" style="color:#1a2e1c;">Rating</h4>
                    <div class="flex flex-wrap gap-2">
                        <button @click="filters.rating = ''"
                            class="px-3.5 py-2 rounded-full text-xs font-medium border transition-all"
                            :style="!filters.rating
                                ? 'background:#1A2E1C; color:white; border-color:#1A2E1C;'
                                : 'background:white; color:#4A4A4A; border-color:#E2DDD5;'">
                            Any
                        </button>
                        <template x-for="r in ['4.0', '4.5', '5.0']" :key="r">
                            <button @click="filters.rating = filters.rating === r ? '' : r"
                                class="flex items-center gap-1 px-3.5 py-2 rounded-full text-xs font-medium border transition-all"
                                :style="filters.rating === r
                                    ? 'background:#1A2E1C; color:white; border-color:#1A2E1C;'
                                    : 'background:white; color:#4A4A4A; border-color:#E2DDD5;'">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="#F59E0B" stroke="#F59E0B" stroke-width="1">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                                <span x-text="r + '+'"></span>
                            </button>
                        </template>
                    </div>
                </div>

            </div>

            {{-- Panel Footer --}}
            <div class="px-6 py-4 border-t flex gap-3" style="border-color:#E8E4DC;">
                <button @click="resetFilters()"
                    class="flex-1 py-3 rounded-xl text-sm font-medium border transition-all hover:bg-stone-50"
                    style="border:1.5px solid #E2DDD5; color:#1a2e1c; background:white;">
                    Reset
                </button>
                <button @click="applyFilters(); showFilters = false"
                    class="flex-2 flex-grow py-3 rounded-xl text-sm font-semibold text-white transition-all"
                    style="background:#1a2e1c; flex-grow:2;"
                    x-text="countActiveFilters() > 0 ? 'Apply Filters (' + countActiveFilters() + ')' : 'Apply Filters'">
                </button>
            </div>

        </div>
    </div>

    {{-- ===================== RESULTS INFO + ACTIVE TAGS ===================== --}}
    <div class="max-w-7xl mx-auto px-6 pt-5 pb-2" x-data>
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium" style="color:#4A4A4A;">
                <span style="color:#1a2e1c;">{{ $experiences->total() }}</span> experiences found
            </p>
        </div>

        {{-- Active Filter Tags --}}
        <div class="flex flex-wrap gap-2">
            @if(request('kategori'))
                <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium"
                    style="background:#E8E4DC; color:#1a2e1c;">
                    {{ ucfirst(request('kategori')) }}
                    <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}" class="hover:opacity-70">✕</a>
                </span>
            @endif
            @if(request('lokasi'))
                <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium"
                    style="background:#E8E4DC; color:#1a2e1c;">
                    {{ request('lokasi') }}
                    <a href="{{ request()->fullUrlWithQuery(['lokasi' => null]) }}" class="hover:opacity-70">✕</a>
                </span>
            @endif
            @if(request('harga'))
                <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium"
                    style="background:#E8E4DC; color:#1a2e1c;">
                    {{ request('harga') }}
                    <a href="{{ request()->fullUrlWithQuery(['harga' => null]) }}" class="hover:opacity-70">✕</a>
                </span>
            @endif
            @if(request('kategori') || request('lokasi') || request('harga'))
                <a href="{{ route('experiences.index') }}"
                    class="flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium hover:opacity-80 transition"
                    style="color:#C0392B; background:#FEF2F2; border:1px solid #FECACA;">
                    Clear all
                </a>
            @endif
        </div>
    </div>

    {{-- ===================== EXPERIENCE GRID ===================== --}}
    <div class="max-w-7xl mx-auto px-6 pb-20" x-data="{ viewMode: 'grid' }">

        @if($experiences->isEmpty())
            <div class="text-center py-24">
                <div class="text-5xl mb-4">🌿</div>
                <h3 class="mb-2" style="font-family:'Playfair Display',serif; font-size:1.5rem; color:#1a2e1c;">
                    No experiences found
                </h3>
                <p style="color:#9CA3AF; font-size:0.875rem;">Try adjusting your search or filters.</p>
                <a href="{{ route('experiences.index') }}"
                    class="inline-block mt-6 px-6 py-3 rounded-xl text-sm font-medium text-white transition hover:opacity-90"
                    style="background:#1a2e1c;">
                    Clear all filters
                </a>
            </div>

        @else

            {{-- Grid Mode --}}
            <div class="grid gap-6"
                :class="viewMode === 'grid' ? 'grid-cols-2 lg:grid-cols-4' : 'grid-cols-1'">

                @foreach($experiences as $exp)
                    @php
                        $locale = app()->getLocale();
                        $judul = $exp->getJudul($locale);
                        $cover = $exp->photos->where('is_cover', true)->first() ?? $exp->photos->first();
                        $kategori = $exp->kategori?->getNama($locale) ?? 'Experience';
                    @endphp

                    <a href="{{ route('experiences.show', $exp->slug) }}"
                        class="group relative block rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
                        style="background-color: #141414; height: 420px; text-decoration:none; color:inherit;"
                        x-data="{ wishlisted: {{ auth()->check() && auth()->user()->hasWishlisted($exp->id) ? 'true' : 'false' }} }"
                        data-auth="{{ auth()->check() ? '1' : '0' }}"
                        data-login-url="{{ route('login') }}"
                        data-toggle-url="{{ route('wishlist.toggle') }}"
                        data-experience-id="{{ $exp->id }}"
                        data-csrf="{{ csrf_token() }}">

                        {{-- Background Image --}}
                        <div class="absolute inset-0">
                            @if($cover)
                                <img src="{{ $cover->url }}" alt="{{ $judul }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                    loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-stone-800">
                                    <span class="text-5xl opacity-40">🌿</span>
                                </div>
                            @endif
                        </div>

                        {{-- Heavy Dark Gradient Overlay --}}
                        <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(20,20,20,0) 0%, rgba(20,20,20,0.1) 30%, rgba(20,20,20,0.85) 65%, rgba(20,20,20,1) 100%);"></div>

                        {{-- Top Elements: Category Badge & Wishlist --}}
                        <div class="absolute top-4 left-4 right-4 flex justify-between items-start z-10">
                            {{-- Category Badge --}}
                            <div class="px-2.5 py-1.5 rounded flex items-center gap-1.5"
                                style="background: rgba(40,30,20,0.6); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.15);">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line>
                                </svg>
                                <span class="text-[9px] font-semibold text-white uppercase tracking-wider">{{ $kategori }}</span>
                            </div>

                            {{-- Wishlist --}}
                            <button @click.prevent="
                                    const card = $el.closest('[data-experience-id]');
                                    if (card.dataset.auth === '0') {
                                        window.location.href = card.dataset.loginUrl;
                                        return;
                                    }
                                    wishlisted = !wishlisted;
                                    fetch(card.dataset.toggleUrl, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': card.dataset.csrf,
                                            'Accept': 'application/json',
                                        },
                                        body: JSON.stringify({ experience_id: parseInt(card.dataset.experienceId) }),
                                    }).then(r => r.json()).then(data => {
                                        if (!data.success) wishlisted = !wishlisted;
                                    }).catch(() => { wishlisted = !wishlisted; });
                                "
                                class="w-8 h-8 flex items-center justify-center rounded-full transition-all hover:scale-110">
                                <svg width="22" height="22" viewBox="0 0 24 24"
                                    :fill="wishlisted ? 'white' : 'rgba(0,0,0,0.3)'"
                                    :stroke="wishlisted ? 'white' : 'white'" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Bottom Content --}}
                        <div class="absolute bottom-0 left-0 right-0 p-5 z-10 flex flex-col justify-end">
                            
                            {{-- Rating --}}
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-white text-[10px]">★</span>
                                <span class="text-white text-xs font-semibold">{{ number_format($exp->rating_avg, 1) }}</span>
                                <span class="text-gray-400 text-xs">({{ $exp->total_reviews }})</span>
                            </div>

                            {{-- Title --}}
                            <h3 class="text-[1.1rem] text-white font-medium mb-3 leading-snug"
                                style="font-family: 'Playfair Display', serif; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                                {{ $judul }}
                            </h3>

                            {{-- Location & Duration --}}
                            <div class="flex items-center text-gray-300 text-[11px] gap-2 mb-4">
                                <span class="flex items-center gap-1">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    {{ $exp->kabupaten ?? $exp->lokasi_nama }}
                                </span>
                                <span class="w-1 h-1 rounded-full bg-gray-500"></span>
                                <span class="flex items-center gap-1">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    {{ $exp->getDurasiFormatted() }}
                                </span>
                            </div>

                            {{-- Divider --}}
                            <div class="w-full h-px bg-white/10 mb-3"></div>

                            {{-- Host & Price Footer --}}
                            <div class="flex items-center justify-between">
                                {{-- Host --}}
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $exp->host->user->avatarUrl() }}" alt="Host" class="w-8 h-8 rounded-full object-cover border border-white/20">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-gray-400 mb-0.5">Hosted by</span>
                                        <span class="text-xs text-white font-medium">{{ $exp->host->user->name ?? 'Host' }}</span>
                                        <span class="text-[9px] text-gray-400 flex items-center gap-1 mt-0.5">
                                            Local Host
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="#10B981" stroke="#10B981"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4" stroke="white" stroke-width="2"/></svg>
                                        </span>
                                    </div>
                                </div>

                                {{-- Price --}}
                                <div class="flex flex-col items-end">
                                    <span class="text-[9px] text-gray-400 mb-0.5">From</span>
                                    <span class="text-sm text-white font-semibold">{{ $exp->getHargaFormatted() }}</span>
                                    <span class="text-[9px] text-gray-400 mt-0.5">/ person</span>
                                </div>
                            </div>
                        </div>

                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($experiences->hasPages())
                <div class="text-center mt-14">
                    {{ $experiences->links() }}
                </div>
            @endif

        @endif
    </div>

@endsection

@push('scripts')
<script>
function filterBar() {
    return {
        showFilters: false,
        viewMode: 'grid',

        filters: {
            kategori: '{{ request('kategori') }}',
            lokasi: '{{ request('lokasi') }}',
            harga: '{{ request('harga') }}',
            durasi: '{{ request('durasi') }}',
            tipeArr: [],
            rating: '',
            sort: '{{ request('sort', 'relevan') }}',
            search: '{{ request('search') }}',
            priceMin: 0,
            priceMax: 1000000,
        },

        hargaOptions: [
            { value: '0-200000',       label: 'Di bawah Rp 200.000' },
            { value: '200000-350000',  label: 'Rp 200.000 – 350.000' },
            { value: '350000-500000',  label: 'Rp 350.000 – 500.000' },
            { value: '500000-99999999',label: 'Di atas Rp 500.000' },
        ],

        durasiOptions: [
            { value: 'any',     label: 'Any' },
            { value: '0-120',   label: '< 2 hours' },
            { value: '120-240', label: '2 – 4 hours' },
            { value: '240-360', label: '4 – 6 hours' },
            { value: '360-99999', label: '6+ hours' },
        ],

        get hasActiveFilters() {
            return this.filters.kategori || this.filters.lokasi || this.filters.harga ||
                   this.filters.durasi || this.filters.search;
        },

        get hasActiveSecondaryFilters() {
            return this.filters.harga || this.filters.durasi || this.filters.tipeArr.length > 0 || this.filters.rating;
        },

        countActiveFilters() {
            let count = 0;
            if (this.filters.harga || (this.filters.priceMin > 0 || this.filters.priceMax < 1000000)) count++;
            if (this.filters.durasi) count++;
            if (this.filters.tipeArr.length > 0) count += this.filters.tipeArr.length;
            if (this.filters.rating) count++;
            return count;
        },

        getPriceLabel() {
            const min = parseInt(this.filters.priceMin);
            const max = parseInt(this.filters.priceMax);
            const fmt = (n) => n >= 1000000 ? '1M+' : (n >= 1000 ? (n/1000)+'k' : n);
            return 'Rp' + fmt(min) + ' – Rp' + fmt(max);
        },

        init() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let timeout;
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        this.filters.search = e.target.value;
                        this.applyFilters();
                    }, 500);
                });
            }
        },

        applyFilters() {
            const params = new URLSearchParams();
            if (this.filters.search)   params.set('search',   this.filters.search);
            if (this.filters.kategori) params.set('kategori', this.filters.kategori);
            if (this.filters.lokasi)   params.set('lokasi',   this.filters.lokasi);
            if (this.filters.durasi && this.filters.durasi !== 'any') params.set('durasi', this.filters.durasi);
            if (this.filters.rating)   params.set('rating',   this.filters.rating);
            if (this.filters.tipeArr.length) params.set('tipe', this.filters.tipeArr.join(','));

            // Price range
            if (this.filters.priceMin > 0 || this.filters.priceMax < 1000000) {
                params.set('harga', this.filters.priceMin + '-' + this.filters.priceMax);
            } else if (this.filters.harga) {
                params.set('harga', this.filters.harga);
            }

            if (this.filters.sort && this.filters.sort !== 'relevan') params.set('sort', this.filters.sort);

            window.location.href = '{{ route('experiences.index') }}?' + params.toString();
        },

        resetFilters() {
            this.filters = {
                ...this.filters,
                kategori: '', lokasi: '', harga: '', durasi: '',
                tipeArr: [], rating: '', search: '',
                priceMin: 0, priceMax: 1000000,
            };
        },
    }
}
</script>
@endpush