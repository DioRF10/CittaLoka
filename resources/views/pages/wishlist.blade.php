@extends('layouts.app')

@section('title', 'My Wishlist | CittaLoka')

@section('content')

@php
    $locale = app()->getLocale();
@endphp

<style>
    .wishlist-header-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 2.75rem;
        font-weight: 500;
        color: #1a2e1c;
        line-height: 1.1;
    }
    .wishlist-share-box {
        background: #F0EDE6;
        border-radius: 16px;
        padding: 1.75rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .wishlist-share-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.4rem;
        font-weight: 600;
        color: #1a2e1c;
        margin-bottom: 0.3rem;
    }
    .wishlist-share-desc {
        font-size: 0.875rem;
        color: #6B7280;
        max-width: 480px;
        line-height: 1.5;
    }
    .btn-share-wishlist {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.4rem;
        background: #1a2e1c;
        color: white;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        border: none;
        cursor: pointer;
    }
    .empty-wishlist-icon-box {
        width: 140px;
        height: 140px;
        background: linear-gradient(135deg, #F5F2EC, #EDE7DC);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        position: relative;
    }
</style>

<div class="max-w-7xl mx-auto px-6 py-12">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
        <div>
            <h1 class="wishlist-header-title">My Wishlist</h1>
            <p style="font-size:0.9rem; color:#6B7280; margin-top:0.5rem;">
                {{ $wishlists->count() }} {{ $wishlists->count() === 1 ? 'experience' : 'experiences' }} saved
                @if($lastUpdated)
                    · Last updated {{ \Carbon\Carbon::parse($lastUpdated)->format('M d, Y') }}
                @endif
            </p>
        </div>

        @if($wishlists->isNotEmpty())
            <form method="GET" action="{{ route('wishlist.index') }}">
                <select name="sort" onchange="this.form.submit()"
                    class="text-sm rounded-lg px-4 py-2 border"
                    style="border-color:#E8E4DC; color:#1a2e1c; background:white;">
                    <option value="recent" {{ $sort === 'recent' ? 'selected' : '' }}>Sort by: Recently Added</option>
                    <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Sort by: Price (Low to High)</option>
                    <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Sort by: Price (High to Low)</option>
                    <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Sort by: Highest Rated</option>
                </select>
            </form>
        @endif
    </div>

    @if($wishlists->isNotEmpty())

        {{-- Share Box --}}
        <div class="wishlist-share-box mb-10">
            <div>
                <div class="wishlist-share-title">Share your curated collection</div>
                <div class="wishlist-share-desc">Inspire your travel companions by sharing your curated list of Balinese culture and artisanal experiences.</div>
            </div>
            <button type="button" class="btn-share-wishlist" disabled style="opacity:0.6; cursor:not-allowed;" title="Coming soon">
                Share Wishlist
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </button>
        </div>

        {{-- Grid Card --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($wishlists as $item)
                @php
                    $exp = $item->experience;
                    if (!$exp) continue;
                    $judul = $exp->getJudul($locale);
                    $cover = $exp->photos->where('is_cover', true)->first() ?? $exp->photos->first();
                    $kategori = $exp->kategori?->getNama($locale) ?? 'Experience';
                @endphp

                <a href="{{ route('experiences.show', $exp->slug) }}"
                    class="group relative block rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
                    style="background-color: #141414; height: 420px; text-decoration:none; color:inherit;">

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

                        {{-- Heart — selalu filled karena di wishlist --}}
                        <button type="button"
                            onclick="event.preventDefault(); event.stopPropagation(); removeFromWishlist({{ $exp->id }}, this)"
                            class="w-8 h-8 flex items-center justify-center rounded-full transition-all hover:scale-110">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Bottom Content --}}
                    <div class="absolute bottom-0 left-0 right-0 p-5 z-10 flex flex-col justify-end">
                        
                        {{-- Rating --}}
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="text-white text-[10px]">★</span>
                            <span class="text-white text-xs font-semibold">{{ number_format($exp->rating_avg ?? 0, 1) }}</span>
                            <span class="text-gray-400 text-xs">({{ $exp->total_reviews ?? 0 }})</span>
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
                                {{ $exp->durasi_menit ? round($exp->durasi_menit / 60) . ' Hours' : '-' }}
                            </span>
                        </div>

                        {{-- Divider --}}
                        <div class="w-full h-px bg-white/10 mb-3"></div>

                        {{-- Host & Price Footer --}}
                        <div class="flex items-center justify-between">
                            {{-- Host --}}
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $exp->host?->user?->avatarUrl() ?? 'https://ui-avatars.com/api/?name=Host' }}" alt="Host" class="w-8 h-8 rounded-full object-cover border border-white/20">
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-gray-400 mb-0.5">Hosted by</span>
                                    <span class="text-xs text-white font-medium">{{ $exp->host?->user?->name ?? 'Host' }}</span>
                                    <span class="text-[9px] text-gray-400 flex items-center gap-1 mt-0.5">
                                        Local Host
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="#10B981" stroke="#10B981"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4" stroke="white" stroke-width="2"/></svg>
                                    </span>
                                </div>
                            </div>

                            {{-- Price --}}
                            <div class="flex flex-col items-end">
                                <span class="text-[9px] text-gray-400 mb-0.5">From</span>
                                <span class="text-sm text-white font-semibold">Rp {{ number_format($exp->harga, 0, ',', '.') }}</span>
                                <span class="text-[9px] text-gray-400 mt-0.5">/ person</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    @else

        {{-- Empty State --}}
        <div class="text-center" style="padding:5rem 0;">
            <div class="empty-wishlist-icon-box">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="1.2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </div>
            <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:2rem; font-weight:500; color:#1a2e1c; margin-bottom:0.75rem;">
                Your wishlist is empty
            </h2>
            <p style="font-size:0.95rem; color:#6B7280; max-width:420px; margin:0 auto 2rem; line-height:1.6;">
                Save experiences you'd love to try by tapping the heart icon. Your curated collection of cultural journeys will wait for you here.
            </p>
            <a href="{{ route('experiences.index') }}"
                style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.8rem 1.75rem; background:#1a2e1c; color:white; border-radius:10px; font-size:0.9rem; font-weight:600; text-decoration:none;">
                Explore Experiences
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

    @endif

</div>

<script>
function removeFromWishlist(experienceId, btnEl) {
    fetch('{{ route("wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ experience_id: experienceId }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && !data.wishlisted) {
            // Hapus card dari tampilan, lalu reload supaya count & empty state akurat
            window.location.reload();
        }
    })
    .catch(() => alert('Gagal menghapus dari wishlist.'));
}
</script>

@endsection