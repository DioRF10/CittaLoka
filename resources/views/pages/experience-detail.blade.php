@extends('layouts.app')

@section('title', $experience->getJudul())

@section('content')

@php
    $locale  = app()->getLocale();
    $judul   = $experience->getJudul($locale);
    $desk    = $experience->getDeskripsi($locale);
    $harga   = $experience->getHargaFormatted();
    $durasi  = $experience->getDurasiFormatted();
    $host    = $experience->host;
    $hostUser= $host->user;
    $photos  = $experience->photos;
    $cover   = $photos->where('is_cover', true)->first() ?? $photos->first();
    $others  = $photos->where('is_cover', false)->take(2);
    $avails  = $experience->availabilities;
    $serviceFee = 25000;
@endphp

<div x-data="bookingWidget()" x-init="init()">

{{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
<div style="background:#F7F3ED; padding: 0.75rem 0; border-bottom: 1px solid #EDE7DC;">
    <div style="max-width:1200px; margin:0 auto; padding:0 2rem;">
        <nav style="font-size:0.8rem; color:#7A7A6E; display:flex; align-items:center; gap:0.4rem;">
            <a href="{{ route('home') }}" style="color:#7A7A6E; text-decoration:none;">Home</a>
            <span>/</span>
            <a href="{{ route('experiences.index') }}" style="color:#7A7A6E; text-decoration:none;">Experiences</a>
            <span>/</span>
            <span style="color:#1E3A2F; font-weight:500;">{{ $judul }}</span>
        </nav>
    </div>
</div>

<div style="max-width:1200px; margin:0 auto; padding:2rem;">

    {{-- ── Photo Gallery ───────────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:2fr 1fr; grid-template-rows:1fr 1fr; gap:0.75rem; height:480px; border-radius:16px; overflow:hidden; margin-bottom:2rem; position:relative;">

        {{-- Cover photo --}}
        <div style="grid-row: 1 / 3; background:#EDE7DC; overflow:hidden;">
            @if($cover)
                <img src="{{ $cover->url }}" alt="{{ $judul }}" style="width:100%; height:100%; object-fit:cover;">
            @else
                <div style="width:100%; height:100%; background:linear-gradient(135deg,#2D5240,#C4A882); display:flex; align-items:center; justify-content:center; font-size:4rem;">🌿</div>
            @endif
        </div>

        {{-- Photo 2 --}}
        <div style="background:#EDE7DC; overflow:hidden;">
            @if($others->count() >= 1)
                <img src="{{ $others->first()->url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
            @else
                <div style="width:100%; height:100%; background:#D4C9B8;"></div>
            @endif
        </div>

        {{-- Photo 3 --}}
        <div style="background:#EDE7DC; overflow:hidden; position:relative;">
            @if($others->count() >= 2)
                <img src="{{ $others->last()->url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
            @else
                <div style="width:100%; height:100%; background:#C8BAA5;"></div>
            @endif

            {{-- See all photos button --}}
            <button
                style="position:absolute; bottom:1rem; right:1rem; background:white; border:1.5px solid #EDE7DC; border-radius:8px; padding:0.5rem 1rem; font-size:0.8rem; font-weight:500; cursor:pointer; display:flex; align-items:center; gap:0.4rem; font-family:'DM Sans',sans-serif;"
                onclick="alert('Gallery coming soon')"
            >
                🖼 See all photos
            </button>
        </div>
    </div>

    {{-- ── Main Layout: Konten Kiri + Booking Widget Kanan ────────────── --}}
    <div style="display:grid; grid-template-columns:1fr 380px; gap:3rem; align-items:start;">

        {{-- ════════════════════════════════════════════════════════════ --}}
        {{-- KIRI — Detail Content                                        --}}
        {{-- ════════════════════════════════════════════════════════════ --}}
        <div>

            {{-- Badge + Rating --}}
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
                <span style="background:#EDE7DC; color:#1E3A2F; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; padding:0.3rem 0.75rem; border-radius:999px;">
                    {{ strtoupper($experience->kategori?->getNama($locale) ?? 'Experience') }}
                </span>
                <span style="display:flex; align-items:center; gap:0.3rem; font-size:0.85rem; color:#7A7A6E;">
                    <span style="color:#C4783A;">★</span>
                    <strong style="color:#2C2C2C;">{{ number_format($experience->rating_avg, 1) }}</strong>
                    <span>({{ $experience->total_reviews }} reviews)</span>
                </span>
            </div>

            {{-- Judul --}}
            <h1 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:2.5rem; font-weight:500; color:#1E3A2F; line-height:1.2; margin-bottom:1rem;">
                {{ $judul }}
            </h1>

            {{-- Meta --}}
            <div style="display:flex; align-items:center; gap:1.5rem; margin-bottom:1.5rem; font-size:0.875rem; color:#7A7A6E;">
                <span style="display:flex; align-items:center; gap:0.35rem;">
                    📍 {{ $experience->lokasi_nama }}
                </span>
                <span style="display:flex; align-items:center; gap:0.35rem;">
                    ⏱ {{ $durasi }}
                </span>
                <span style="display:flex; align-items:center; gap:0.35rem;">
                    👥 Max {{ $experience->kapasitas_max }} guests
                </span>
            </div>

            {{-- Divider --}}
            <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

            {{-- Host Card --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; padding:1.25rem; background:#F7F3ED; border-radius:12px; border:1.5px solid #EDE7DC;">
                <div style="display:flex; align-items:center; gap:1rem;">
                    <img
                        src="{{ $hostUser->avatarUrl() }}"
                        alt="{{ $hostUser->name }}"
                        style="width:52px; height:52px; border-radius:50%; object-fit:cover; border:2px solid #EDE7DC;"
                    >
                    <div>
                        <div style="font-size:0.75rem; color:#7A7A6E; margin-bottom:0.2rem;">Hosted by</div>
                        <div style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.1rem; font-weight:500; color:#1E3A2F;">{{ $hostUser->name }}</div>
                        <div style="font-size:0.78rem; color:#7A7A6E;">
                            {{ $host->bio ? Str::limit($host->bio, 50) : '3rd generation master · Fluent in English' }}
                        </div>
                    </div>
                </div>
                <a
                    href="/hosts/{{ $host->id }}"
                    style="border:1.5px solid #1E3A2F; color:#1E3A2F; padding:0.6rem 1.25rem; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none; white-space:nowrap; font-family:'DM Sans',sans-serif;"
                >
                    View Full Profile
                </a>
            </div>

            {{-- Divider --}}
            <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

            {{-- About this Experience --}}
            <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">
                About this Experience
            </h2>
            <div style="font-size:0.9rem; color:#4A4A4A; line-height:1.8; margin-bottom:1.5rem;">
                {!! nl2br(e($desk)) !!}
            </div>

            {{-- Divider --}}
            <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

            {{-- What You'll Do --}}
            <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">
                What You'll Do
            </h2>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
                @php
                    $activities = [
                        ['icon' => '☕', 'title' => 'Introduction & Coffee',  'desc' => 'Welcome ceremony and discussion of Balinese culture history over local coffee and traditional snacks.'],
                        ['icon' => '✏️', 'title' => 'Design Selection',       'desc' => 'Choose from traditional masks or abstract shapes. Sketch your design onto the selected block of wood.'],
                        ['icon' => '🪚', 'title' => 'Guided Carving',         'desc' => 'Hands-on instruction using traditional tools. Learn techniques for roughing out, shaping, and fine detailing.'],
                        ['icon' => '✨', 'title' => 'Finishing',              'desc' => 'Sand and polish your creation to take home as a unique souvenir of your time in Bali.'],
                    ];
                @endphp
                @foreach($activities as $act)
                    <div style="padding:1rem; background:#F7F3ED; border-radius:10px; border:1.5px solid #EDE7DC;">
                        <div style="font-size:1.25rem; margin-bottom:0.5rem;">{{ $act['icon'] }}</div>
                        <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F; margin-bottom:0.3rem;">{{ $act['title'] }}</div>
                        <div style="font-size:0.8rem; color:#7A7A6E; line-height:1.6;">{{ $act['desc'] }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Divider --}}
            <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

            {{-- Inclusions & Details --}}
            <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">
                Inclusions &amp; Details
            </h2>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; margin-bottom:1.5rem;">
                <div>
                    <div style="font-size:0.72rem; font-weight:600; color:#1E3A2F; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.4rem;">
                        <span style="color:#2D5240;">✓</span> What's Included
                    </div>
                    @php
                        $included = ['All carving tools and materials', 'A block of high-quality local wood', 'Traditional Balinese coffee and snacks', 'Your finished carving to take home'];
                    @endphp
                    @foreach($included as $item)
                        <div style="display:flex; align-items:flex-start; gap:0.5rem; margin-bottom:0.5rem; font-size:0.85rem; color:#4A4A4A;">
                            <span style="color:#2D5240; font-weight:bold; flex-shrink:0;">✓</span>
                            {{ $item }}
                        </div>
                    @endforeach
                </div>
                <div>
                    <div style="font-size:0.72rem; font-weight:600; color:#C0392B; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.4rem;">
                        <span>✗</span> What's Not Included
                    </div>
                    @php
                        $notIncluded = ['Transportation to/from the workshop', 'Full lunch (snacks only provided)', 'Personal gratuities for the artisan'];
                    @endphp
                    @foreach($notIncluded as $item)
                        <div style="display:flex; align-items:flex-start; gap:0.5rem; margin-bottom:0.5rem; font-size:0.85rem; color:#4A4A4A;">
                            <span style="color:#C0392B; font-weight:bold; flex-shrink:0;">✗</span>
                            {{ $item }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Divider --}}
            <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

            {{-- Meeting Point --}}
            <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F; margin-bottom:0.5rem;">
                Meeting Point
            </h2>
            <div style="display:flex; align-items:flex-start; gap:0.5rem; font-size:0.875rem; color:#4A4A4A; margin-bottom:1rem;">
                <span>📍</span>
                <span>{{ $experience->meeting_point ?? $experience->alamat_lengkap }}</span>
            </div>

            {{-- Map Placeholder --}}
            <div style="background:#EDE7DC; border-radius:12px; height:200px; display:flex; align-items:center; justify-content:center; margin-bottom:1.5rem; border:1.5px solid #D4C4AC;">
                @if($experience->lokasi_lat && $experience->lokasi_lng)
                    <iframe
                        src="https://maps.google.com/maps?q={{ $experience->lokasi_lat }},{{ $experience->lokasi_lng }}&z=15&output=embed"
                        width="100%"
                        height="200"
                        style="border:0; border-radius:12px;"
                        allowfullscreen
                        loading="lazy"
                    ></iframe>
                @else
                    <div style="text-align:center; color:#7A7A6E;">
                        <div style="font-size:2rem; margin-bottom:0.5rem;">🗺</div>
                        <div style="font-size:0.8rem;">Interactive Map</div>
                    </div>
                @endif
            </div>

            {{-- Divider --}}
            <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

            {{-- Guest Reviews --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F;">
                    Guest Reviews
                </h2>
                <a href="#" style="font-size:0.8rem; color:#1E3A2F; text-decoration:underline; font-weight:500;">
                    See all {{ $experience->total_reviews }} reviews
                </a>
            </div>

            {{-- Rating Summary --}}
            <div style="display:flex; align-items:center; gap:2rem; margin-bottom:1.5rem;">
                <div style="text-align:center;">
                    <div style="font-size:3rem; font-weight:300; color:#1E3A2F; font-family:'DM Sans',sans-serif; line-height:1;">
                        {{ number_format($experience->rating_avg, 1) }}
                    </div>
                    <div style="color:#C4783A; font-size:1.1rem; margin:0.25rem 0;">★★★★★</div>
                    <div style="font-size:0.75rem; color:#7A7A6E;">{{ $experience->total_reviews }} reviews</div>
                </div>
                <div style="flex:1;">
                    @foreach([5,4,3,2,1] as $star)
                        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.3rem;">
                            <span style="font-size:0.75rem; color:#7A7A6E; width:8px;">{{ $star }}</span>
                            <div style="flex:1; height:6px; background:#EDE7DC; border-radius:999px; overflow:hidden;">
                                <div style="height:100%; background:#1E3A2F; border-radius:999px; width:{{ $star === 5 ? '80%' : ($star === 4 ? '12%' : ($star === 3 ? '5%' : '2%')) }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Review Cards --}}
            @php
                $dummyReviews = [
                    ['name' => 'Rizky Pratama', 'date' => 'May 2024', 'rating' => 5, 'avatar' => 'R', 'text' => 'Amazing experience! The host was super friendly and patient. I\'m so proud of my creation!'],
                    ['name' => 'Nadia Ayu',     'date' => 'April 2024', 'rating' => 5, 'avatar' => 'N', 'text' => 'Cocok untuk pemula. Penjelasannya sangat detail dan sabar. Anak saya juga ikut dan happy.'],
                    ['name' => 'Dimas Putra',   'date' => 'April 2024', 'rating' => 5, 'avatar' => 'D', 'text' => 'Salah satu experience terbaik di Bali. Highly recommended!'],
                ];
            @endphp
            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1rem;">
                @foreach($dummyReviews as $review)
                    <div style="background:#F7F3ED; border-radius:12px; padding:1rem; border:1.5px solid #EDE7DC;">
                        <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.75rem;">
                            <div style="width:36px; height:36px; border-radius:50%; background:#1E3A2F; color:white; display:flex; align-items:center; justify-content:center; font-size:0.875rem; font-weight:500; flex-shrink:0;">
                                {{ $review['avatar'] }}
                            </div>
                            <div>
                                <div style="font-size:0.85rem; font-weight:500; color:#2C2C2C;">{{ $review['name'] }}</div>
                                <div style="font-size:0.72rem; color:#7A7A6E;">{{ $review['date'] }}</div>
                            </div>
                        </div>
                        <div style="color:#C4783A; font-size:0.75rem; margin-bottom:0.5rem;">★★★★★</div>
                        <div style="font-size:0.8rem; color:#4A4A4A; line-height:1.6;">{{ $review['text'] }}</div>
                    </div>
                @endforeach
            </div>

        </div>{{-- end kiri --}}

        {{-- ════════════════════════════════════════════════════════════ --}}
        {{-- KANAN — Booking Widget (Sticky)                              --}}
        {{-- ════════════════════════════════════════════════════════════ --}}
        <div
            x-data="bookingWidget()"
            x-init="init()"
            style="position:sticky; top:2rem; align-self:start;"
        >
            <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; padding:1.5rem; box-shadow:0 4px 24px rgba(30,58,47,0.08);">

                {{-- Harga --}}
                <div style="display:flex; align-items:baseline; gap:0.4rem; margin-bottom:0.25rem;">
                    <span style="font-size:1.5rem; font-weight:600; color:#1E3A2F; font-family:'DM Sans',sans-serif;">{{ $harga }}</span>
                    <span style="font-size:0.875rem; color:#7A7A6E;">/ person</span>
                </div>

                {{-- Rating --}}
                <div style="display:flex; align-items:center; gap:0.3rem; margin-bottom:1.25rem; font-size:0.8rem;">
                    <span style="color:#C4783A;">★</span>
                    <strong>{{ number_format($experience->rating_avg, 1) }}</strong>
                    <a href="#reviews" style="color:#7A7A6E; text-decoration:underline;">({{ $experience->total_reviews }} reviews)</a>
                </div>

                {{-- ── Unified Input Group ── --}}
                <div style="border:1.5px solid #E2DDD5; border-radius:10px; overflow:hidden; margin-bottom:1.25rem;" @click.away="showCalendar = false">

                    {{-- Date Row --}}
                    <div style="position:relative;">
                        <button @click="showCalendar = !showCalendar"
                            style="width:100%; padding:0.85rem 1rem; border:none; border-bottom:1.5px solid #E2DDD5; font-size:0.85rem; font-family:'DM Sans',sans-serif; text-align:left; background:white; color:#2C2C2C; display:flex; justify-content:space-between; align-items:center; cursor:pointer; outline:none;">
                            <div>
                                <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.2rem;">Date</div>
                                <div x-text="selectedDate ? selectedDateLabel : 'Select a date'" :style="!selectedDate ? 'color:#9CA3AF;' : 'color:#1E3A2F; font-weight:500;'" style="font-size:0.85rem;"></div>
                            </div>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </button>

                        {{-- Calendar Popup --}}
                        <div x-show="showCalendar" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 transform -translate-y-1" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            style="position:absolute; top:calc(100% + 0.35rem); left:-1.5px; right:-1.5px; background:white; border:1.5px solid #E2DDD5; border-radius:12px; padding:1.25rem; box-shadow:0 12px 32px rgba(30,58,47,0.12); z-index:50;"
                            x-init="$watch('showCalendar', val => { $el.style.display = val ? 'block' : 'none' })"
                            :style="showCalendar ? '' : 'display:none'">

                            {{-- Month Header --}}
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                                <button @click.stop="prevMonth()" :disabled="isPrevDisabled()"
                                    style="width:28px; height:28px; border:1.5px solid #E2DDD5; border-radius:6px; background:white; display:flex; align-items:center; justify-content:center; font-size:0.9rem; color:#7A7A6E;"
                                    :style="isPrevDisabled() ? { opacity: 0.3, cursor: 'not-allowed' } : { cursor: 'pointer' }">‹</button>
                                <span style="font-size:0.875rem; font-weight:600; color:#1E3A2F;" x-text="monthLabel"></span>
                                <button @click.stop="nextMonth()"
                                    style="width:28px; height:28px; border:1.5px solid #E2DDD5; border-radius:6px; background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:0.9rem; color:#7A7A6E;">›</button>
                            </div>

                            {{-- Day Names --}}
                            <div style="display:grid; grid-template-columns:repeat(7,1fr); gap:2px; margin-bottom:4px;">
                                <template x-for="d in ['Mo','Tu','We','Th','Fr','Sa','Su']" :key="d">
                                    <div style="text-align:center; font-size:0.65rem; color:#9CA3AF; font-weight:600; padding:2px 0;" x-text="d"></div>
                                </template>
                            </div>

                            {{-- Day Grid --}}
                            <div style="display:grid; grid-template-columns:repeat(7,1fr); gap:2px;">
                                <template x-for="n in firstDayOfMonth" :key="'e'+n"><div></div></template>
                                <template x-for="day in daysInMonth" :key="day">
                                    <button @click.stop="selectDate(day); showCalendar = false" :disabled="!isAvailable(day) || isPast(day)"
                                        x-text="day" :style="getDayStyle(day)"
                                        style="width:100%; aspect-ratio:1; border:none; border-radius:6px; font-size:0.78rem; font-family:'DM Sans',sans-serif; transition:all 0.15s;"></button>
                                </template>
                            </div>

                            {{-- Legend --}}
                            <div style="display:flex; gap:1rem; margin-top:0.75rem; font-size:0.65rem; color:#9CA3AF; justify-content:center;">
                                <span style="display:flex; align-items:center; gap:0.25rem;">
                                    <span style="width:8px; height:8px; border-radius:2px; background:#EBF5EE; border:1px solid #B8DFC8; display:inline-block;"></span> Available
                                </span>
                                <span style="display:flex; align-items:center; gap:0.25rem;">
                                    <span style="width:8px; height:8px; border-radius:2px; background:#1E3A2F; display:inline-block;"></span> Selected
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Time Row (Muncul setelah tanggal dipilih) --}}
                    <div x-show="selectedDate" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0"
                        style="position:relative; border-bottom:1.5px solid #E2DDD5; display:none;"
                        x-init="$watch('selectedDate', val => { if(val) $el.style.display = 'block'; else $el.style.display = 'none'; })"
                        :style="selectedDate ? '' : 'display:none'">
                        <div style="padding:0.85rem 1rem;">
                            <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.5rem;">Time</div>
                            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:0.4rem;">
                                <template x-for="t in availableTimes" :key="t.time">
                                    <button @click="if(t.slot > 0) selectedTime = t.time"
                                        :disabled="t.slot === 0"
                                        :style="selectedTime === t.time 
                                            ? { background: '#1E3A2F', color: 'white', borderColor: '#1E3A2F' } 
                                            : (t.slot === 0 ? { background: '#F9FAFB', color: '#D1D5DB', borderColor: '#E5E7EB', cursor: 'not-allowed' } : { background: 'white', color: '#1E3A2F', borderColor: '#D1D5DB', cursor: 'pointer' })"
                                        style="padding:0.4rem 0.6rem; border:1px solid; border-radius:8px; text-align:center; transition:all 0.15s; outline:none; width:100%;">
                                        <div x-text="t.time" style="font-size:0.8rem; font-weight:600; font-family:'DM Sans',sans-serif; margin-bottom:0.1rem;"></div>
                                        <div x-text="t.slot > 0 ? t.slot + ' slots' : 'Full'" style="font-size:0.65rem; font-family:'DM Sans',sans-serif; opacity:0.85;"></div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Guests Row --}}
                    <div style="position:relative;">
                        <div style="padding:0.85rem 1rem;">
                            <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.2rem;">Guests</div>
                            <select x-model="guests"
                                style="width:100%; padding:0; border:none; font-size:0.85rem; font-family:'DM Sans',sans-serif; font-weight:500; outline:none; appearance:none; color:#1E3A2F; background:transparent; cursor:pointer;">
                                @for($i = $experience->kapasitas_min; $i <= $experience->kapasitas_max; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}</option>
                                @endfor
                            </select>
                        </div>
                        <svg style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); pointer-events:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </div>
                </div>

                {{-- Book Button --}}
                <button
                    @click="bookNow()"
                    :disabled="!selectedDate || !selectedTime || !isLoggedIn"
                    style="width:100%; padding:0.9rem; background:#1E3A2F; color:white; border:none; border-radius:10px; font-size:0.9rem; font-weight:600; font-family:'DM Sans',sans-serif; margin-bottom:0.6rem; transition:all 0.2s; letter-spacing:0.01em;"
                    :style="(!selectedDate || !selectedTime || !isLoggedIn) ? { opacity: 0.45, cursor: 'not-allowed' } : { opacity: 1, cursor: 'pointer' }"
                    onmouseover="if(!this.disabled)this.style.background='#2D4A32'"
                    onmouseout="this.style.background='#1E3A2F'"
                >
                    <span x-text="!isLoggedIn ? 'Login to Book' : 'Book This Experience'"></span>
                </button>

                {{-- Wishlist Button --}}
                <button
                    @click="toggleWishlist()"
                    style="width:100%; padding:0.8rem; background:white; color:#1E3A2F; border:1.5px solid #E2DDD5; border-radius:10px; font-size:0.85rem; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif; margin-bottom:1rem; display:flex; align-items:center; justify-content:center; gap:0.4rem; transition:all 0.2s;"
                    onmouseover="this.style.borderColor='#1E3A2F'"
                    onmouseout="this.style.borderColor='#E2DDD5'"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" :fill="inWishlist ? '#EF4444' : 'none'" :stroke="inWishlist ? '#EF4444' : 'currentColor'" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <span x-text="inWishlist ? 'Saved to Wishlist' : 'Add to Wishlist'"></span>
                </button>

                <p style="text-align:center; font-size:0.75rem; color:#7A7A6E; margin-bottom:1rem;">
                    You won't be charged yet
                </p>

                {{-- Price Breakdown --}}
                <div style="border-top:1px solid #EDE7DC; padding-top:1rem;">
                    <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:#6B7280; margin-bottom:0.5rem;">
                        <span x-text="`${priceFormatted} × ${guests} person${guests > 1 ? 's' : ''}`"></span>
                        <span x-text="subtotalFormatted" style="color:#4A4A4A;"></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:#6B7280; margin-bottom:0.85rem;">
                        <span>Service fee</span>
                        <span style="color:#4A4A4A;">Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.95rem; font-weight:700; color:#1E3A2F; padding-top:0.85rem; border-top:1px solid #EDE7DC;">
                        <span>Total</span>
                        <span x-text="totalFormatted"></span>
                    </div>
                </div>

                {{-- Free Cancellation --}}
                <div style="display:flex; align-items:center; justify-content:center; gap:0.4rem; font-size:0.75rem; color:#2D5240; margin-top:1rem; padding-top:1rem; border-top:1px solid #EDE7DC;">
                    <span>🛡️</span>
                    <span>Free cancellation up to 24 hours before</span>
                </div>

            </div>
        </div>{{-- end kanan --}}

    </div>{{-- end main grid --}}
</div>{{-- end container --}}

</div>{{-- end x-data --}}

@endsection

@push('scripts')
<script>
function bookingWidget() {
    return {
        showCalendar: false,
        selectedDate: null,
        selectedTime: null,
        availableTimes: [],
        guests: {{ $experience->kapasitas_min }},
        inWishlist: false,
        isLoggedIn: @auth true @else false @endauth,
        currentYear: new Date().getFullYear(),
        currentMonth: new Date().getMonth(),

        basePrice: {{ $experience->harga }},
        serviceFee: {{ $serviceFee }},
        availableDates: @json($avails->pluck('date')->map(fn($d) => $d->format('Y-m-d'))),

        get monthLabel() {
            return new Date(this.currentYear, this.currentMonth, 1)
                .toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        },

        get daysInMonth() {
            return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        },

        get firstDayOfMonth() {
            let day = new Date(this.currentYear, this.currentMonth, 1).getDay();
            return day === 0 ? 6 : day - 1;
        },

        get selectedDateLabel() {
            if (!this.selectedDate) return '';
            const d = new Date(this.selectedDate + 'T00:00:00');
            return d.toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        },

        get priceFormatted() {
            return 'Rp ' + this.basePrice.toLocaleString('id-ID');
        },
        get subtotal() { return this.basePrice * this.guests; },
        get subtotalFormatted() { return 'Rp ' + this.subtotal.toLocaleString('id-ID'); },
        get total() { return this.subtotal + this.serviceFee; },
        get totalFormatted() { return 'Rp ' + this.total.toLocaleString('id-ID'); },

        init() {},

        getDateString(day) {
            const m = String(this.currentMonth + 1).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            return `${this.currentYear}-${m}-${d}`;
        },

        isAvailable(day) {
            return this.availableDates.includes(this.getDateString(day));
        },

        isPast(day) {
            const today = new Date(); today.setHours(0,0,0,0);
            return new Date(this.currentYear, this.currentMonth, day) <= today;
        },

        isSelected(day) {
            return this.selectedDate === this.getDateString(day);
        },

        getDayStyle(day) {
            if (this.isSelected(day)) {
                return { background: '#1E3A2F', color: 'white', cursor: 'pointer', fontWeight: '500' };
            }
            if (this.isPast(day) || !this.isAvailable(day)) {
                return { background: '#F5F5F5', color: '#CCCCCC', cursor: 'not-allowed' };
            }
            return { background: '#EBF5EE', color: '#1E3A2F', cursor: 'pointer', border: '1px solid #B8DFC8' };
        },

        selectDate(day) {
            if (!this.isAvailable(day) || this.isPast(day)) return;
            this.selectedDate = this.getDateString(day);
            this.selectedTime = null; // Reset time
            
            // Mock data jam dan slot
            this.availableTimes = [
                { time: '09:00', slot: Math.floor(Math.random() * 5) + 1 },
                { time: '11:00', slot: Math.floor(Math.random() * 8) + 2 },
                { time: '13:00', slot: Math.floor(Math.random() * 3) },
                { time: '15:00', slot: Math.floor(Math.random() * 6) + 1 },
                { time: '17:00', slot: Math.floor(Math.random() * 4) }
            ];
        },

        prevMonth() {
            if (this.isPrevDisabled()) return;
            if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; }
            else this.currentMonth--;
        },

        nextMonth() {
            if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; }
            else this.currentMonth++;
        },

        isPrevDisabled() {
            const t = new Date();
            return this.currentYear === t.getFullYear() && this.currentMonth <= t.getMonth();
        },

        bookNow() {
            @auth
                if (!this.selectedDate || !this.selectedTime) { alert('Pilih tanggal dan jam terlebih dahulu.'); return; }
                window.location.href = `/checkout/{{ $experience->slug }}?date=${this.selectedDate}&time=${this.selectedTime}&guests=${this.guests}`;
            @else
                window.location.href = '/login?returnUrl=' + encodeURIComponent(window.location.pathname);
            @endauth
        },

        toggleWishlist() {
            @auth
                this.inWishlist = !this.inWishlist;
            @else
                window.location.href = '/login?returnUrl=' + encodeURIComponent(window.location.pathname);
            @endauth
        },
    }
}
</script>
@endpush