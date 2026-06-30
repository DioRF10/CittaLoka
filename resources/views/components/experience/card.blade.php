@props([
    'exp',
    'href' => null,
])

@php
    $locale = app()->getLocale();
    $judul = $exp->getJudul($locale);
    $cover = $exp->photos->where('is_cover', true)->first() ?? $exp->photos->first();
    $kategori = $exp->kategori?->getNama($locale) ?? 'Experience';
    $location = $exp->kabupaten ?? $exp->lokasi_nama ?? 'Bali';
    $hostName = $exp->host?->user?->name ?? 'Local Host';
    $href = $href ?? route('experiences.show', $exp->slug);
@endphp

<a href="{{ $href }}"
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
                :fill="wishlisted ? '#EF4444' : 'rgba(0,0,0,0.3)'"
                :stroke="wishlisted ? '#EF4444' : 'white'" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </button>
    </div>

    {{-- Bottom Content --}}
    <div class="absolute bottom-0 left-0 right-0 p-5 z-10 flex flex-col justify-end">
        
        {{-- Rating --}}
        <div class="flex items-center gap-1.5 mb-2">
            <span class="text-[#F6B84B] text-[10px]">★</span>
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
                {{ $location }}
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
                    <span class="text-xs text-white font-medium">{{ $hostName }}</span>
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
