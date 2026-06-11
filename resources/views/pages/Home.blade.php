@extends('layouts.app')

@section('title', 'CittaLoka — Experience Bali from the inside')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════
     SECTION 1: HERO
════════════════════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-6 py-16 lg:py-24">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        {{-- Teks Kiri --}}
        <div>
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full mb-6 text-xs font-medium"
                 style="background: #E8F5E9; color: #2E5E32; border: 1px solid #C8E6C9;">
                <span class="w-1.5 h-1.5 rounded-full" style="background: #2E5E32;"></span>
                Living Culture Platform
            </div>

            <h1 class="font-normal leading-tight mb-6"
                style="font-family: 'Playfair Display', serif; font-size: clamp(40px, 5vw, 64px); color: #1a2e1c; line-height: 1.1;">
                Experience Bali<br>from the inside
            </h1>

            <p class="text-base leading-relaxed mb-8" style="color: #6B7280; max-width: 440px;">
                Connect with local hosts, participate in century-old rituals, and discover the hidden soul of the Island of Gods through curated intimate journeys.
            </p>

            {{-- CTA Buttons --}}
            <div class="flex flex-wrap items-center gap-3 mb-8">
                <a href="/experiences"
                   class="flex items-center gap-2 px-6 py-3.5 rounded-lg text-sm font-medium text-white transition-all hover:-translate-y-0.5"
                   style="background: #1a2e1c;"
                   onmouseover="this.style.background='#2D4A32'"
                   onmouseout="this.style.background='#1a2e1c'">
                    Start Your Journey →
                </a>
                <a href="/soul-match"
                   class="flex items-center gap-2 px-6 py-3.5 rounded-lg text-sm font-medium transition-all hover:-translate-y-0.5"
                   style="background: white; border: 1.5px solid #E0DBD0; color: #1a2e1c;"
                   onmouseover="this.style.borderColor='#1a2e1c'"
                   onmouseout="this.style.borderColor='#E0DBD0'">
                    🤍 Find Your Soul Match
                </a>
            </div>

            {{-- Social Proof --}}
            <div class="flex items-center gap-3">
                <div class="flex -space-x-2">
                    <img src="https://i.pravatar.cc/32?img=1" class="w-8 h-8 rounded-full border-2 border-white object-cover" alt="">
                    <img src="https://i.pravatar.cc/32?img=2" class="w-8 h-8 rounded-full border-2 border-white object-cover" alt="">
                    <img src="https://i.pravatar.cc/32?img=3" class="w-8 h-8 rounded-full border-2 border-white object-cover" alt="">
                    <img src="https://i.pravatar.cc/32?img=4" class="w-8 h-8 rounded-full border-2 border-white object-cover" alt="">
                </div>
                <div>
                    <div class="flex items-center gap-1">
                        <span class="text-sm font-medium" style="color: #1a2e1c;">★ 4.9</span>
                        <span class="text-sm" style="color: #9CA3AF;">from 1,200+ travelers</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Foto Kanan --}}
        <div class="relative">
            <div class="rounded-2xl overflow-hidden" style="height: 480px;">
                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80"
                     alt="Balinese Dancer"
                     class="w-full h-full object-cover">
            </div>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     SECTION 2: 4 PILAR FITUR
════════════════════════════════════════════════════════════════ --}}
<section class="mx-6 lg:mx-auto max-w-7xl mb-16">
    <div class="rounded-2xl p-8 lg:p-12" style="background: #F0EDE6;">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">

            <div class="text-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4" style="background: #E8E4DC;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold mb-2" style="color: #1a2e1c;">Soul Match</h3>
                <p class="text-xs leading-relaxed" style="color: #6B7280;">Intelligent matching with hosts who share your core values and curiosities.</p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4" style="background: #E8E4DC;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold mb-2" style="color: #1a2e1c;">Host Story</h3>
                <p class="text-xs leading-relaxed" style="color: #6B7280;">Rich biographies and lineage documentation for every guide.</p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4" style="background: #E8E4DC;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2"/>
                        <path d="M3 9h18M9 21V9"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold mb-2" style="color: #1a2e1c;">Memory Book</h3>
                <p class="text-xs leading-relaxed" style="color: #6B7280;">Professional photography and physical journals for every journey.</p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4" style="background: #E8E4DC;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1a2e1c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                        <line x1="16" x2="16" y1="2" y2="6"/>
                        <line x1="8" x2="8" y1="2" y2="6"/>
                        <line x1="3" x2="21" y1="10" y2="10"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold mb-2" style="color: #1a2e1c;">Seasonal Calendar</h3>
                <p class="text-xs leading-relaxed" style="color: #6B7280;">Ritual dates and events exclusive to local communities.</p>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     SECTION 3: CURATED EXPERIENCES
════════════════════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-6 mb-20">

    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="text-xs font-medium uppercase tracking-widest mb-2" style="color: #C4783A; letter-spacing: 0.12em;">Explore Experiences</p>
            <h2 class="font-normal" style="font-family: 'Playfair Display', serif; font-size: 32px; color: #1a2e1c;">Curated Cultural Journeys</h2>
        </div>
        <a href="/experiences" class="text-sm font-medium hover:underline hidden md:block" style="color: #1a2e1c;">
            View all experiences →
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            /** @var \App\Models\Experience $exp */
        @endphp
        @forelse($featuredExperiences ?? [] as $exp)
        <a href="/experiences/{{ $exp->slug }}" class="group block overflow-hidden rounded-[32px] border border-[#E8E4DC] bg-white shadow-[0_10px_40px_rgba(31,40,24,0.08)] transition-transform duration-300 hover:-translate-y-1">
            <div class="relative overflow-hidden">
                <img src="{{ $exp->photos->first()?->url ?? 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=400&q=80' }}"
                     alt="{{ $exp->title }}"
                     class="w-full h-[260px] object-cover transition-transform duration-500 group-hover:scale-105">

                <div class="absolute inset-x-0 top-4 px-4 flex items-start justify-between">
                    <span class="rounded-full bg-[#1A2E1C] px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.24em] text-white shadow-sm">
                        {{ strtoupper($exp->kategori ? $exp->kategori->getNama() : 'BEST SELLER') }}
                    </span>
                    <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-[#1A2E1C] shadow-sm transition hover:bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 0 1 6.364 0L12 7.636l1.318-1.318a4.5 4.5 0 1 1 6.364 6.364L12 21.682l-7.682-7.682a4.5 4.5 0 0 1 0-6.364z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="space-y-3 p-5">
                <div class="flex items-center justify-between text-[12px] text-[#6B7280]">
                    <span class="inline-flex items-center gap-2"><span>📍</span>{{ $exp->kabupaten }}</span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-[#EFF5EE] px-3 py-1 font-semibold text-[#1A2E1C]">★ {{ number_format((float) $exp->rating_avg, 1) }}</span>
                </div>

                <h3 class="text-lg font-semibold leading-tight text-[#1a2e1c] group-hover:text-[#C4783A] transition-colors">
                    {{ $exp->getJudul() }}
                </h3>

                <p class="text-sm text-[#6B7280]">by {{ $exp->host?->user?->name }}</p>

                <div class="flex items-center justify-between border-t border-[#E8E4DC] pt-4 text-sm text-[#6B7280]">
                    <div>
                        <span class="text-[#6B7280]">From </span>
                        <span class="font-semibold text-[#1a2e1c]">{{ $exp->getHargaFormatted() }}</span>
                        <span>/person</span>
                    </div>
                    <span>⏱ {{ $exp->getDurasiFormatted() }}</span>
                </div>
            </div>
        </a>
        @empty
        {{-- Placeholder saat belum ada data --}}
        @foreach([
            ['title' => 'Batik Painting Session', 'location' => 'Gianyar', 'price' => '200.000', 'rating' => '4.1', 'duration' => '2.5', 'category' => 'BEST SELLER', 'img' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80'],
            ['title' => 'Balinese Home Cooking', 'location' => 'Ubud', 'price' => '320.000', 'rating' => '4.9', 'duration' => '4', 'category' => 'POPULAR', 'img' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&q=80'],
            ['title' => 'A Day with Rice Farmer', 'location' => 'Tabanan', 'price' => '280.000', 'rating' => '5.0', 'duration' => '6', 'category' => 'NEW', 'img' => 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=400&q=80'],
            ['title' => 'Gamelan Music Lesson', 'location' => 'Klungkung', 'price' => '220.000', 'rating' => '4.1', 'duration' => '2', 'category' => 'BEST SELLER', 'img' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=400&q=80'],
        ] as $item)
        <div class="group block cursor-pointer overflow-hidden rounded-[32px] border border-[#E8E4DC] bg-white shadow-[0_10px_40px_rgba(31,40,24,0.08)] transition-transform duration-300 hover:-translate-y-1">
            <div class="relative overflow-hidden">
                <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}" class="w-full h-[260px] object-cover transition-transform duration-500 group-hover:scale-105" />
                <div class="absolute inset-x-0 top-4 px-4 flex items-start justify-between">
                    <span class="rounded-full bg-[#1A2E1C] px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.24em] text-white shadow-sm">{{ $item['category'] }}</span>
                    <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-[#1A2E1C] shadow-sm transition hover:bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 0 1 6.364 0L12 7.636l1.318-1.318a4.5 4.5 0 1 1 6.364 6.364L12 21.682l-7.682-7.682a4.5 4.5 0 0 1 0-6.364z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="space-y-3 p-5">
                <div class="flex items-center justify-between text-[12px] text-[#6B7280]">
                    <span class="inline-flex items-center gap-2"><span>📍</span>{{ $item['location'] }}</span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-[#EFF5EE] px-3 py-1 font-semibold text-[#1A2E1C]">★ {{ $item['rating'] }}</span>
                </div>
                <h3 class="text-lg font-semibold leading-tight text-[#1a2e1c] group-hover:text-[#C4783A] transition-colors">{{ $item['title'] }}</h3>
                <p class="text-sm text-[#6B7280]">by Local Host</p>
                <div class="flex items-center justify-between border-t border-[#E8E4DC] pt-4 text-sm text-[#6B7280]">
                    <div>
                        <span class="text-[#6B7280]">From </span>
                        <span class="font-semibold text-[#1a2e1c]">Rp {{ $item['price'] }}</span>
                        <span>/person</span>
                    </div>
                    <span>⏱ {{ $item['duration'] }} jam</span>
                </div>
            </div>
        </div>
        @endforeach
        @endforelse
    </div>

</section>

{{-- ═══════════════════════════════════════════════════════════════
     SECTION 4: OUR STORY
════════════════════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-6 mb-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <div>
            <p class="text-xs font-medium uppercase tracking-widest mb-4" style="color: #C4783A; letter-spacing: 0.12em;">Our Story</p>
            <h2 class="font-normal mb-6" style="font-family: 'Playfair Display', serif; font-size: clamp(28px, 3vw, 40px); color: #1a2e1c; line-height: 1.2;">
                Preserving the heartbeat of Balinese tradition.
            </h2>
            <p class="text-sm leading-relaxed mb-4" style="color: #6B7280;">
                CittaLoka was born from a desire to bridge the gap between curious global travelers and the keepers of Bali's sacred traditions. We believe that true travel isn't just about seeing; it's about belonging.
            </p>
            <p class="text-sm leading-relaxed mb-8" style="color: #6B7280;">
                Our platform carefully vets every host, ensuring they are not just guides, but legitimate practitioners of their craft, farmers of their land, and storytellers of their lineage. We reinvest 20% of all bookings back into local community education and heritage restoration.
            </p>
            <a href="/about"
               class="inline-flex items-center gap-2 px-6 py-3.5 rounded-lg text-sm font-medium text-white transition-all hover:-translate-y-0.5"
               style="background: #1a2e1c;"
               onmouseover="this.style.background='#2D4A32'"
               onmouseout="this.style.background='#1a2e1c'">
                Learn Our Story →
            </a>
        </div>

        <div class="rounded-2xl overflow-hidden" style="height: 460px;">
            <img src="https://images.unsplash.com/photo-1604999333679-b86d54738315?w=600&q=80"
                 alt="Balinese Woman"
                 class="w-full h-full object-cover">
        </div>

    </div>
</section>


<section class="mx-6 lg:mx-auto max-w-7xl mb-20">
    <div class="rounded-2xl p-10 lg:p-16" style="background: #F0EDE6;">
        <div class="text-center mb-12">
            <p class="text-xs font-medium uppercase tracking-widest mb-3" style="color: #C4783A; letter-spacing: 0.12em;">Process</p>
            <h2 class="font-normal" style="font-family: 'Playfair Display', serif; font-size: 32px; color: #1a2e1c;">Your Path to Inner Bali</h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach([
                ['icon' => '🤍', 'step' => '01', 'title' => 'Soul Match', 'desc' => 'Tell us your interests and we\'ll find your perfect cultural host.'],
                ['icon' => '📅', 'step' => '02', 'title' => 'Choose Experience', 'desc' => 'Select a curated journey that fits your schedule and soul style.'],
                ['icon' => '🌿', 'step' => '03', 'title' => 'Live the Experience', 'desc' => 'Step into their world, participate fully, and feel the connection.'],
                ['icon' => '📖', 'step' => '04', 'title' => 'Keep the Memory', 'desc' => 'Receive your custom physical memory book and professional photos.'],
            ] as $step)
            <div class="text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl" style="background: white;">
                    {{ $step['icon'] }}
                </div>
                <p class="text-xs font-medium mb-2" style="color: #C4783A;">{{ $step['step'] }}</p>
                <h3 class="text-sm font-semibold mb-2" style="color: #1a2e1c;">{{ $step['title'] }}</h3>
                <p class="text-xs leading-relaxed" style="color: #6B7280;">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     SECTION 6: TESTIMONIALS
════════════════════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-6 mb-20">
    <h2 class="font-normal text-center mb-10" style="font-family: 'Playfair Display', serif; font-size: 32px; color: #1a2e1c;">What They Say</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach([
            ['quote' => 'I\'ve been to Bali so times, but I never truly saw it until CittaLoka. Spending a day in the rice fields with Made was the highlight of my year.', 'name' => 'Sophie Martin', 'role' => 'Journalist, Paris', 'img' => 'https://i.pravatar.cc/40?img=5'],
            ['quote' => 'The level of intimacy and respect in these experiences is unmatched. You aren\'t just a tourist; you are a guest in their ancestral home.', 'name' => 'James Chen', 'role' => 'Architect, Singapore', 'img' => 'https://i.pravatar.cc/40?img=8'],
            ['quote' => 'Finding a host who truly understood my interest in sacred geometry was incredible. My Soul Match was perfect from the first minute.', 'name' => 'Maria Gonzalez', 'role' => 'Designer, Barcelona', 'img' => 'https://i.pravatar.cc/40?img=9'],
        ] as $t)
        <div class="p-6 rounded-2xl" style="background: #F5F2EC;">
            <p class="text-sm leading-relaxed mb-6 italic" style="color: #4B5563;">
                "{{ $t['quote'] }}"
            </p>
            <div class="flex items-center gap-3">
                <img src="{{ $t['img'] }}" alt="{{ $t['name'] }}" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="text-sm font-medium" style="color: #1a2e1c;">{{ $t['name'] }}</p>
                    <p class="text-xs" style="color: #9CA3AF;">{{ $t['role'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     SECTION 7: CTA BANNER
════════════════════════════════════════════════════════════════ --}}
<section class="mx-6 lg:mx-auto max-w-7xl mb-20">
    <div class="rounded-2xl px-8 py-16 text-center" style="background: #0D1A0F;">
        <h2 class="font-normal mb-4" style="font-family: 'Playfair Display', serif; font-size: clamp(28px, 3vw, 42px); color: #E8E4DC;">
            Ready to start your conscious journey?
        </h2>
        <p class="text-sm mb-8" style="color: #6B7A6D;">
            Join a community of travelers who value depth over distance and connection over checklists.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="/experiences"
               class="flex items-center gap-2 px-6 py-3.5 rounded-lg text-sm font-medium transition-all hover:-translate-y-0.5"
               style="background: white; color: #1a2e1c;"
               onmouseover="this.style.background='#F5F2EC'"
               onmouseout="this.style.background='white'">
                Start Your Journey →
            </a>
            <a href="/soul-match"
               class="flex items-center gap-2 px-6 py-3.5 rounded-lg text-sm font-medium transition-all hover:-translate-y-0.5"
               style="background: transparent; border: 1.5px solid #2D4A32; color: #A8C5A0;"
               onmouseover="this.style.borderColor='#4A7A4E'"
               onmouseout="this.style.borderColor='#2D4A32'">
                🤍 Find Your Soul Match
            </a>
        </div>
    </div>
</section>

@endsection