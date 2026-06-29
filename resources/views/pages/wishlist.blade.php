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
                    class="group block rounded-2xl overflow-hidden bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                    style="text-decoration:none; color:inherit; box-shadow: 0 1px 6px rgba(0,0,0,0.07);">

                    <div class="relative overflow-hidden" style="height: 220px;">
                        @if($cover)
                            <img src="{{ $cover->url }}" alt="{{ $judul }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#2D5240,#C4A882);">
                                <span class="text-5xl">🌿</span>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg uppercase tracking-wide"
                                style="background:rgba(0,0,0,0.55); backdrop-filter:blur(8px); color:white; letter-spacing:0.08em;">
                                {{ strtoupper($kategori) }}
                            </span>
                        </div>

                        {{-- Heart — selalu filled karena di wishlist --}}
                        <button type="button"
                            onclick="event.preventDefault(); event.stopPropagation(); removeFromWishlist({{ $exp->id }}, this)"
                            class="absolute top-3 right-3 w-9 h-9 rounded-full flex items-center justify-center transition-all hover:scale-110"
                            style="background:rgba(255,255,255,0.95); box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="#EF4444" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs flex items-center gap-1" style="color:#9CA3AF;">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $exp->kabupaten ?? $exp->lokasi_nama }}
                            </span>
                            <span class="text-xs flex items-center gap-1">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="#F59E0B" stroke="#F59E0B" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                <strong>{{ number_format($exp->rating_avg ?? 0, 1) }}</strong> ({{ $exp->total_reviews ?? 0 }})
                            </span>
                        </div>

                        <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.05rem; font-weight:600; color:#1a2e1c; line-height:1.3; margin-bottom:0.3rem;">
                            {{ $judul }}
                        </h3>
                        <p style="font-size:0.78rem; color:#6B7280; margin-bottom:0.75rem;">by {{ $exp->host?->user?->name ?? '-' }}</p>

                        <div style="border-top:1px solid #F0EDE6; padding-top:0.75rem; display:flex; align-items:center; justify-content:space-between;">
                            <div>
                                <span style="font-size:0.72rem; color:#9CA3AF;">From</span><br>
                                <strong style="font-size:0.92rem; color:#1a2e1c;">Rp {{ number_format($exp->harga, 0, ',', '.') }}</strong><span style="font-size:0.75rem; color:#9CA3AF;">/person</span>
                            </div>
                            <span class="text-xs flex items-center gap-1" style="color:#6B7280;">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $exp->durasi_menit ? round($exp->durasi_menit / 60) . ' jam' : '-' }}
                            </span>
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