@extends('layouts.app')

@section('title', 'Explore Experiences — CittaLoka')

@section('content')

{{-- Header --}}
<section class="py-12 text-center" style="background: #F0EDE6;">
    <p class="text-xs font-medium uppercase tracking-widest mb-3" style="color: #C4783A; letter-spacing: 0.15em;">Explore Experiences</p>
    <h1 class="font-normal mb-6" style="font-family: 'Playfair Display', serif; font-size: clamp(28px, 4vw, 48px); color: #1a2e1c;">
        Find Your Perfect Bali Experience
    </h1>

    {{-- Search Bar --}}
    <div class="max-w-xl mx-auto px-6">
        <div class="relative">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input
                type="text"
                placeholder="Search experiences, hosts, or locations..."
                class="w-full pl-11 pr-4 py-3.5 rounded-xl text-sm outline-none transition-all duration-200"
                style="background: white; border: 1.5px solid #E2DDD5; color: #1a2e1c;"
                onfocus="this.style.borderColor='#1a2e1c'"
                onblur="this.style.borderColor='#E2DDD5'"
            >
        </div>
    </div>
</section>

{{-- Filter Bar --}}
<div class="sticky top-16 z-40 border-b" style="background: #FAFAF8; border-color: #E8E4DC;">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between gap-4 overflow-x-auto">

        {{-- Filter Dropdowns --}}
        <div class="flex items-center gap-2 flex-nowrap">
            @foreach(['Semua Kategori', 'Lokasi', 'Harga', 'Durasi', 'Bahasa'] as $filter)
            <button class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-medium whitespace-nowrap transition-all hover:border-[#1a2e1c]"
                    style="background: white; border: 1px solid #E2DDD5; color: #1a2e1c;">
                {{ $filter }}
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </button>
            @endforeach

            {{-- Toggle buttons --}}
            <button class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-medium whitespace-nowrap"
                    style="background: white; border: 1px solid #E2DDD5; color: #1a2e1c;">
                🌿 Outdoor
            </button>
            <button class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-medium whitespace-nowrap"
                    style="background: white; border: 1px solid #E2DDD5; color: #1a2e1c;">
                Seasonal Only
            </button>
        </div>

        {{-- Sort + View --}}
        <div class="flex items-center gap-3 flex-shrink-0">
            <button class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-medium"
                    style="background: white; border: 1px solid #E2DDD5; color: #1a2e1c;">
                Sort: Relevan
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </button>
            <div class="flex items-center gap-1">
                <button class="p-2 rounded-lg transition-colors" style="background: #1a2e1c; color: white;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/>
                        <rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
                    </svg>
                </button>
                <button class="p-2 rounded-lg transition-colors hover:bg-[#F0EDE6]" style="color: #9CA3AF;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Results Info --}}
<div class="max-w-7xl mx-auto px-6 py-4">
    <p class="text-sm" style="color: #9CA3AF;">Menampilkan 24 dari 180 experience di Bali</p>
</div>

{{-- Experience Grid --}}
<div class="max-w-7xl mx-auto px-6 pb-16">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

        @php
        $placeholders = [
            ['title' => 'Make Your Own Balinese Offering', 'host' => 'Pak Wayan', 'location' => 'Ubud', 'price' => '250.000', 'rating' => '4.9', 'reviews' => '120', 'duration' => '3', 'category' => 'CRAFT & ART', 'img' => 'https://images.unsplash.com/photo-1604147706283-d7119b5b822c?w=400&q=80'],
            ['title' => 'Balinese Home Cooking', 'host' => 'Ibu Kadek', 'location' => 'Ubud', 'price' => '320.000', 'rating' => '4.8', 'reviews' => '95', 'duration' => '4', 'category' => 'FOOD & COOKING', 'img' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&q=80'],
            ['title' => 'Silver Jewelry Making', 'host' => 'Pak Nyoman', 'location' => 'Celuk', 'price' => '400.000', 'rating' => '5.0', 'reviews' => '112', 'duration' => '4', 'category' => 'CRAFT & ART', 'img' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=400&q=80'],
            ['title' => 'A Day with Rice Farmer', 'host' => 'Made Artha', 'location' => 'Tabanan', 'price' => '280.000', 'rating' => '5.0', 'reviews' => '42', 'duration' => '6', 'category' => 'FARMING & NATURE', 'img' => 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=400&q=80'],
            ['title' => 'Batik Painting Session', 'host' => 'Bli Agus', 'location' => 'Gianyar', 'price' => '200.000', 'rating' => '4.7', 'reviews' => '56', 'duration' => '2.5', 'category' => 'CRAFT & ART', 'img' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80'],
            ['title' => 'Batik Painting Session', 'host' => 'Bli Agus', 'location' => 'Gianyar', 'price' => '200.000', 'rating' => '4.7', 'reviews' => '56', 'duration' => '2.5', 'category' => 'CRAFT & ART', 'img' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80'],
            ['title' => 'Traditional Dance Workshop', 'host' => 'Ni Komang', 'location' => 'Ubud', 'price' => '350.000', 'rating' => '4.9', 'reviews' => '78', 'duration' => '3', 'category' => 'DANCE & MUSIC', 'img' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80', 'seasonal' => true],
            ['title' => 'Gamelan Music Lesson', 'host' => 'Pak Ketut', 'location' => 'Klungkung', 'price' => '220.000', 'rating' => '4.8', 'reviews' => '34', 'duration' => '2', 'category' => 'MUSIC', 'img' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=400&q=80'],
        ];
        @endphp

        @foreach($placeholders as $exp)
        <div class="group cursor-pointer" x-data="{ wishlisted: false }">

            {{-- Foto --}}
            <div class="rounded-xl overflow-hidden mb-3 relative" style="height: 220px;">
                <img
                    src="{{ $exp['img'] }}"
                    alt="{{ $exp['title'] }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                >

                {{-- Badge Kategori --}}
                <div class="absolute top-3 left-3">
                    @if(isset($exp['seasonal']) && $exp['seasonal'])
                    <span class="text-xs font-medium px-2 py-1 rounded-md" style="background: #C4783A; color: white;">
                        SEASONAL
                    </span>
                    @else
                    <span class="text-xs font-medium px-2 py-1 rounded-md" style="background: rgba(255,255,255,0.92); color: #1a2e1c;">
                        {{ $exp['category'] }}
                    </span>
                    @endif
                </div>

                {{-- Wishlist Button --}}
                <button
                    @click.prevent="wishlisted = !wishlisted"
                    class="absolute top-3 right-3 w-8 h-8 rounded-full flex items-center justify-center transition-all"
                    style="background: rgba(255,255,255,0.92);"
                >
                    <svg
                        width="14" height="14" viewBox="0 0 24 24"
                        :fill="wishlisted ? '#EF4444' : 'none'"
                        :stroke="wishlisted ? '#EF4444' : '#1a2e1c'"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    >
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
            </div>

            {{-- Info --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs flex items-center gap-1" style="color: #9CA3AF;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        {{ $exp['location'] }}
                    </span>
                    <span class="text-xs flex items-center gap-1" style="color: #1a2e1c;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="#F59E0B" stroke="#F59E0B" stroke-width="1">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        <span class="font-medium">{{ $exp['rating'] }}</span>
                        <span style="color: #9CA3AF;">({{ $exp['reviews'] }})</span>
                    </span>
                </div>

                <h3 class="text-sm font-medium mb-1 group-hover:text-[#C4783A] transition-colors leading-snug" style="color: #1a2e1c;">
                    {{ $exp['title'] }}
                </h3>
                <p class="text-xs mb-3" style="color: #9CA3AF;">by {{ $exp['host'] }}</p>

                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs" style="color: #9CA3AF;">From </span>
                        <span class="text-sm font-semibold" style="color: #1a2e1c;">Rp {{ $exp['price'] }}</span>
                        <span class="text-xs" style="color: #9CA3AF;">/person</span>
                    </div>
                    <span class="text-xs flex items-center gap-1" style="color: #9CA3AF;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        {{ $exp['duration'] }} jam
                    </span>
                </div>
            </div>

        </div>
        @endforeach

    </div>

    {{-- Load More --}}
    <div class="text-center mt-12">
        <button
            class="px-8 py-3.5 rounded-xl text-sm font-medium transition-all hover:-translate-y-0.5 flex items-center gap-2 mx-auto"
            style="background: white; border: 1.5px solid #E2DDD5; color: #1a2e1c;"
            onmouseover="this.style.borderColor='#1a2e1c'"
            onmouseout="this.style.borderColor='#E2DDD5'"
        >
            Load 12 More Experiences
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14m-7-7 7 7 7-7"/>
            </svg>
        </button>
    </div>

</div>

@endsection