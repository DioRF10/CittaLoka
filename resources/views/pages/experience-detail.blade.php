@extends('layouts.app')

@section('title', $experience->getJudul())

@section('content')

@php
    $locale   = app()->getLocale();
    $judul    = $experience->getJudul($locale);
    $desk     = $experience->getDeskripsi($locale);
    $harga    = $experience->getHargaFormatted();
    $durasi   = $experience->getDurasiFormatted();
    $host     = $experience->host;
    $hostUser = $host->user;
    $photos   = $experience->photos;
    $cover    = $photos->where('is_cover', true)->first() ?? $photos->first();
    $others   = $photos->where('is_cover', false)->take(2);
    $avails   = $experience->availabilities;
    $serviceFee = 25000;

    // Ambil tanggal unik yang tersedia (untuk kalender)
    $availableDates = $avails
        ->where('is_blocked', false)
        ->where('time', '!=', null)
        ->pluck('date')
        ->map(fn($d) => $d->format('Y-m-d'))
        ->unique()
        ->values();

    // Included & Not Included dari DB, fallback ke dummy
    $includedItems    = $experience->getIncluded();
    $notIncludedItems = $experience->getNotIncluded();
    if (empty($includedItems)) {
        $includedItems = ['All materials provided', 'Local guide', 'Snacks & drinks', 'Certificate'];
    }
    if (empty($notIncludedItems)) {
        $notIncludedItems = ['Transportation', 'Personal expenses'];
    }

    // What You Do dari DB, fallback ke dummy
    $whatYouDo = $experience->getWhatYouDo();
    if (empty($whatYouDo)) {
        $whatYouDo = [
            ['icon' => '☕', 'title' => 'Welcome & Introduction',  'desc' => 'Begin with a warm Balinese welcome, local coffee, and an introduction to the cultural heritage behind this experience.'],
            ['icon' => '✏️', 'title' => 'Learn the Basics',        'desc' => 'Your host will guide you step by step through the fundamental techniques and traditions involved.'],
            ['icon' => '🎯', 'title' => 'Hands-on Practice',       'desc' => 'Get your hands involved! Practice the craft or skill with personal guidance from your experienced host.'],
            ['icon' => '✨', 'title' => 'Take Home Your Creation',  'desc' => 'Complete your experience with a finished creation or memory to bring home as a unique Bali souvenir.'],
        ];
    }
@endphp

@php $isPreview = request()->has('preview'); @endphp

{{-- ── Preview Mode Banner (khusus host) ── --}}
@if($isPreview)
<div id="preview-banner"
    style="position:fixed; top:0; left:0; right:0; z-index:9999;
           background:linear-gradient(135deg,#1E3A2F,#2D5240);
           color:white; padding:0.65rem 1.5rem;
           display:flex; align-items:center; justify-content:space-between;
           box-shadow:0 2px 12px rgba(0,0,0,0.25); font-family:'DM Sans',sans-serif;">
    <div style="display:flex; align-items:center; gap:0.75rem;">
        <span style="font-size:1.1rem;">👁</span>
        <div>
            <div style="font-size:0.8rem; font-weight:700; letter-spacing:0.04em;">MODE PREVIEW — Tampilan Publik Experience</div>
            <div style="font-size:0.72rem; opacity:0.75;">Kamu sedang melihat tampilan yang dilihat traveler. Navigasi dinonaktifkan.</div>
        </div>
    </div>
    <a href="{{ route('host.experiences.index') }}"
        style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3);
               color:white; text-decoration:none; padding:0.45rem 1rem;
               border-radius:8px; font-size:0.8rem; font-weight:500;
               transition:background 0.2s;"
        onmouseover="this.style.background='rgba(255,255,255,0.25)'"
        onmouseout="this.style.background='rgba(255,255,255,0.15)'">← Kembali ke Dashboard</a>
</div>
<div style="height:52px;"></div>{{-- spacer agar konten tidak tertutup banner --}}
@endif

{{-- Blokir klik link saat preview mode --}}
@if($isPreview)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (!link) return;
            // Izinkan hanya tombol kembali ke dashboard
            if (link.closest('#preview-banner')) return;
            e.preventDefault();
            e.stopPropagation();
            const toast = document.getElementById('preview-nav-toast');
            if (toast) { toast.style.opacity='1'; setTimeout(()=>toast.style.opacity='0', 2000); }
        }, true);
    });
</script>
<div id="preview-nav-toast"
    style="position:fixed; bottom:2rem; left:50%; transform:translateX(-50%);
           background:#1E3A2F; color:white; padding:0.6rem 1.25rem;
           border-radius:999px; font-size:0.8rem; font-family:'DM Sans',sans-serif;
           opacity:0; transition:opacity 0.3s; z-index:99999; pointer-events:none;
           white-space:nowrap; box-shadow:0 4px 16px rgba(0,0,0,0.2);">
    🔒 Navigasi dinonaktifkan dalam mode preview
</div>
@endif

<div x-data="bookingWidget()" x-init="init()">

    {{-- ── Breadcrumb ── --}}
    <div style="background:#F7F3ED; padding:0.75rem 0; border-bottom:1px solid #EDE7DC;">
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

        {{-- ── Photo Gallery ── --}}
        <div style="display:grid; grid-template-columns:2fr 1fr; grid-template-rows:1fr 1fr; gap:0.75rem; height:480px; border-radius:16px; overflow:hidden; margin-bottom:2rem;">

            {{-- Cover --}}
            <div style="grid-row:1/3; background:#EDE7DC; overflow:hidden;">
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
                <button onclick="alert('Gallery coming soon')"
                    style="position:absolute; bottom:1rem; right:1rem; background:white; border:1.5px solid #EDE7DC; border-radius:8px; padding:0.5rem 1rem; font-size:0.8rem; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif;">
                    🖼 See all photos
                </button>
            </div>
        </div>

        {{-- ── Main Layout ── --}}
        <div style="display:grid; grid-template-columns:1fr 380px; gap:3rem; align-items:start;">

            {{-- ══ KIRI ══ --}}
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
                    <span>📍 {{ $experience->lokasi_nama }}</span>
                    <span>⏱ {{ $durasi }}</span>
                    <span>👥 Max {{ $experience->kapasitas_max }} guests</span>
                </div>

                <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

                {{-- Host Card --}}
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; padding:1.25rem; background:#F7F3ED; border-radius:12px; border:1.5px solid #EDE7DC;">
                    <div style="display:flex; align-items:center; gap:1rem;">
                        <img src="{{ $hostUser->avatarUrl() }}" alt="{{ $hostUser->name }}"
                            style="width:52px; height:52px; border-radius:50%; object-fit:cover; border:2px solid #EDE7DC;">
                        <div>
                            <div style="font-size:0.75rem; color:#7A7A6E; margin-bottom:0.2rem;">Hosted by</div>
                            <div style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.1rem; font-weight:500; color:#1E3A2F;">{{ $hostUser->name }}</div>
                            <div style="font-size:0.78rem; color:#7A7A6E;">{{ $host->bio ? Str::limit($host->bio, 50) : 'Experienced local host · Fluent in English' }}</div>
                        </div>
                    </div>
                    <a href="/hosts/{{ $host->id }}"
                        style="border:1.5px solid #1E3A2F; color:#1E3A2F; padding:0.6rem 1.25rem; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none; white-space:nowrap; font-family:'DM Sans',sans-serif;">
                        View Full Profile
                    </a>
                </div>

                <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

                {{-- About --}}
                <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">About this Experience</h2>
                <div style="font-size:0.9rem; color:#4A4A4A; line-height:1.8; margin-bottom:1.5rem;">
                    {!! nl2br(e($desk)) !!}
                </div>

                <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

                {{-- What You'll Do --}}
                <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">What You'll Do</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
                    @foreach($whatYouDo as $act)
                        <div style="padding:1rem; background:#F7F3ED; border-radius:10px; border:1.5px solid #EDE7DC;">
                            <div style="font-size:1.25rem; margin-bottom:0.5rem;">{{ $act['icon'] ?? '🌿' }}</div>
                            <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F; margin-bottom:0.3rem;">{{ $act['title'] ?? '' }}</div>
                            <div style="font-size:0.8rem; color:#7A7A6E; line-height:1.6;">{{ $act['desc'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>

                <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

                {{-- Inclusions --}}
                <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">Inclusions &amp; Details</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; margin-bottom:1.5rem;">
                    <div>
                        <div style="font-size:0.72rem; font-weight:600; color:#1E3A2F; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.75rem;">✓ What's Included</div>
                        @foreach($includedItems as $item)
                            <div style="display:flex; align-items:flex-start; gap:0.5rem; margin-bottom:0.5rem; font-size:0.85rem; color:#4A4A4A;">
                                <span style="color:#2D5240; font-weight:bold; flex-shrink:0;">✓</span>
                                {{ is_array($item) ? ($item[$locale] ?? $item['id'] ?? '') : $item }}
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div style="font-size:0.72rem; font-weight:600; color:#C0392B; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.75rem;">✗ What's Not Included</div>
                        @foreach($notIncludedItems as $item)
                            <div style="display:flex; align-items:flex-start; gap:0.5rem; margin-bottom:0.5rem; font-size:0.85rem; color:#4A4A4A;">
                                <span style="color:#C0392B; font-weight:bold; flex-shrink:0;">✗</span>
                                {{ is_array($item) ? ($item[$locale] ?? $item['id'] ?? '') : $item }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

                {{-- Meeting Point --}}
                <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F; margin-bottom:0.5rem;">Meeting Point</h2>
                <div style="display:flex; align-items:flex-start; gap:0.5rem; font-size:0.875rem; color:#4A4A4A; margin-bottom:1rem;">
                    <span>📍</span>
                    <span>{{ $experience->meeting_point ?? $experience->alamat_lengkap ?? 'Location will be shared after booking' }}</span>
                </div>
                <div style="background:#EDE7DC; border-radius:12px; height:200px; overflow:hidden; margin-bottom:1.5rem; border:1.5px solid #D4C4AC;">
                    @if($experience->lokasi_lat && $experience->lokasi_lng)
                        <iframe src="https://maps.google.com/maps?q={{ $experience->lokasi_lat }},{{ $experience->lokasi_lng }}&z=15&output=embed"
                            width="100%" height="200" style="border:0;" allowfullscreen loading="lazy"></iframe>
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; flex-direction:column; color:#7A7A6E; gap:0.5rem;">
                            <div style="font-size:2rem;">🗺</div>
                            <div style="font-size:0.8rem;">Map will be available soon</div>
                        </div>
                    @endif
                </div>

                <hr style="border:none; border-top:1.5px solid #EDE7DC; margin-bottom:1.5rem;">

                {{-- Reviews --}}
                <div id="reviews" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                    <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F;">Guest Reviews</h2>
                    <a href="#" style="font-size:0.8rem; color:#1E3A2F; text-decoration:underline; font-weight:500;">See all {{ $experience->total_reviews }} reviews</a>
                </div>
                <div style="display:flex; align-items:center; gap:2rem; margin-bottom:1.5rem;">
                    <div style="text-align:center;">
                        <div style="font-size:3rem; font-weight:300; color:#1E3A2F; font-family:'DM Sans',sans-serif; line-height:1;">{{ number_format($experience->rating_avg, 1) }}</div>
                        <div style="color:#C4783A; font-size:1.1rem; margin:0.25rem 0;">★★★★★</div>
                        <div style="font-size:0.75rem; color:#7A7A6E;">{{ $experience->total_reviews }} reviews</div>
                    </div>
                    <div style="flex:1;">
                        @foreach([5,4,3,2,1] as $star)
                            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.3rem;">
                                <span style="font-size:0.75rem; color:#7A7A6E; width:8px;">{{ $star }}</span>
                                <div style="flex:1; height:6px; background:#EDE7DC; border-radius:999px; overflow:hidden;">
                                    <div style="height:100%; background:#1E3A2F; border-radius:999px; width:{{ $star===5?'80%':($star===4?'12%':($star===3?'5%':'2%')) }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @php
                    $dummyReviews = [
                        ['name'=>'Rizky Pratama','date'=>'May 2024','avatar'=>'R','text'=>'Amazing experience! The host was super friendly and patient.'],
                        ['name'=>'Nadia Ayu',    'date'=>'April 2024','avatar'=>'N','text'=>'Cocok untuk pemula. Penjelasannya sangat detail dan sabar.'],
                        ['name'=>'Dimas Putra',  'date'=>'April 2024','avatar'=>'D','text'=>'Salah satu experience terbaik di Bali. Highly recommended!'],
                    ];
                @endphp
                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem;">
                    @foreach($dummyReviews as $review)
                        <div style="background:#F7F3ED; border-radius:12px; padding:1rem; border:1.5px solid #EDE7DC;">
                            <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.75rem;">
                                <div style="width:36px; height:36px; border-radius:50%; background:#1E3A2F; color:white; display:flex; align-items:center; justify-content:center; font-size:0.875rem; font-weight:500; flex-shrink:0;">{{ $review['avatar'] }}</div>
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

            {{-- ══ KANAN — Booking Widget ══ --}}
            <div style="position:sticky; top:2rem; align-self:start;">
                <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; padding:1.75rem; box-shadow:0 4px 24px rgba(30,58,47,0.06); font-family:'DM Sans',sans-serif;">

                    {{-- Harga --}}
                    <div style="display:flex; align-items:baseline; gap:0.35rem; margin-bottom:0.25rem;">
                        <span style="font-size:1.6rem; font-weight:700; color:#1E3A2F; letter-spacing:-0.02em;">{{ $harga }}</span>
                        <span style="font-size:0.875rem; color:#9CA3AF;">/ person</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.3rem; margin-bottom:1.25rem; font-size:0.8rem;">
                        <span style="color:#C4783A;">★</span>
                        <strong style="color:#1E3A2F;">{{ number_format($experience->rating_avg, 1) }}</strong>
                        <a href="#reviews" style="color:#7A7A6E; text-decoration:underline;">({{ $experience->total_reviews }} reviews)</a>
                    </div>

                    {{-- Input Group --}}
                    <div style="border:1.5px solid #E2DDD5; border-radius:10px; margin-bottom:1.25rem; position:relative;" @click.away="showCalendar = false">

                        {{-- Date --}}
                        <div style="position:relative;">
                            <button @click="showCalendar = !showCalendar"
                                style="width:100%; padding:0.85rem 1rem; border:none; border-bottom:1.5px solid #E2DDD5; font-size:0.85rem; font-family:'DM Sans',sans-serif; text-align:left; background:transparent; border-radius:10px 10px 0 0; cursor:pointer; outline:none; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.2rem;">Date</div>
                                    <div x-text="selectedDate ? selectedDateLabel : 'Select a date'"
                                        :style="!selectedDate ? 'color:#9CA3AF;' : 'color:#1E3A2F; font-weight:500;'"
                                        style="font-size:0.85rem;"></div>
                                </div>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                            </button>

                            {{-- Calendar Popup --}}
                            <div x-show="showCalendar"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                style="position:absolute; top:calc(100% + 0.35rem); left:-1.5px; right:-1.5px; background:white; border:1.5px solid #E2DDD5; border-radius:12px; padding:1.25rem; box-shadow:0 12px 32px rgba(30,58,47,0.12); z-index:50;">

                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                                    <button @click.stop="prevMonth()" :disabled="isPrevDisabled()"
                                        :style="isPrevDisabled() ? {opacity:0.3,cursor:'not-allowed'} : {cursor:'pointer'}"
                                        style="width:28px; height:28px; border:1.5px solid #E2DDD5; border-radius:6px; background:white; display:flex; align-items:center; justify-content:center; font-size:0.9rem; color:#7A7A6E;">‹</button>
                                    <span style="font-size:0.875rem; font-weight:600; color:#1E3A2F;" x-text="monthLabel"></span>
                                    <button @click.stop="nextMonth()"
                                        style="width:28px; height:28px; border:1.5px solid #E2DDD5; border-radius:6px; background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:0.9rem; color:#7A7A6E;">›</button>
                                </div>

                                <div style="display:grid; grid-template-columns:repeat(7,1fr); gap:2px; margin-bottom:4px;">
                                    <template x-for="d in ['Mo','Tu','We','Th','Fr','Sa','Su']" :key="d">
                                        <div style="text-align:center; font-size:0.65rem; color:#9CA3AF; font-weight:600; padding:2px 0;" x-text="d"></div>
                                    </template>
                                </div>

                                <div style="display:grid; grid-template-columns:repeat(7,1fr); gap:2px;">
                                    <template x-for="n in firstDayOfMonth" :key="'e'+n"><div></div></template>
                                    <template x-for="day in daysInMonth" :key="day">
                                        <button @click.stop="selectDate(day)"
                                            :disabled="!isAvailable(day) || isPast(day)"
                                            x-text="day"
                                            :style="getDayStyle(day)"
                                            style="width:100%; aspect-ratio:1; border:none; border-radius:6px; font-size:0.78rem; font-family:'DM Sans',sans-serif; transition:all 0.15s;"></button>
                                    </template>
                                </div>

                                <div style="display:flex; gap:1rem; margin-top:0.75rem; font-size:0.65rem; color:#9CA3AF; justify-content:center;">
                                    <span style="display:flex; align-items:center; gap:0.25rem;">
                                        <span style="width:8px; height:8px; border-radius:2px; background:#EBF5EE; border:1px solid #B8DFC8; display:inline-block;"></span> Available
                                    </span>
                                    <span style="display:flex; align-items:center; gap:0.25rem;">
                                        <span style="width:8px; height:8px; border-radius:2px; background:#F5F5F5; display:inline-block;"></span> Unavailable
                                    </span>
                                    <span style="display:flex; align-items:center; gap:0.25rem;">
                                        <span style="width:8px; height:8px; border-radius:2px; background:#1E3A2F; display:inline-block;"></span> Selected
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Time Row — muncul setelah tanggal dipilih --}}
                        <div x-show="selectedDate || loadingTimes"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            style="border-bottom:1.5px solid #E2DDD5;">
                            <div style="padding:0.85rem 1rem;">
                                <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.5rem;">Time</div>

                                {{-- Loading --}}
                                <div x-show="loadingTimes" style="display:flex; align-items:center; gap:0.5rem; font-size:0.8rem; color:#9CA3AF; padding:0.25rem 0;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin-icon">
                                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                                    </svg>
                                    Loading available times...
                                </div>

                                {{-- Tidak ada jam --}}
                                <div x-show="!loadingTimes && availableTimes.length === 0 && selectedDate"
                                    style="font-size:0.8rem; color:#9CA3AF; padding:0.25rem 0;">
                                    Tidak ada jam tersedia untuk tanggal ini.
                                </div>

                                {{-- Time pills --}}
                                <div x-show="!loadingTimes && availableTimes.length > 0"
                                    style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.4rem;">
                                    <template x-for="t in availableTimes" :key="t.time">
                                        <button @click="if(t.available_slot > 0) selectedTime = t.time"
                                            :disabled="t.available_slot === 0"
                                            :style="selectedTime === t.time
                                                ? {background:'#1E3A2F', color:'white', borderColor:'#1E3A2F'}
                                                : (t.available_slot === 0
                                                    ? {background:'#F9FAFB', color:'#D1D5DB', borderColor:'#E5E7EB', cursor:'not-allowed'}
                                                    : {background:'white', color:'#1E3A2F', borderColor:'#D1D5DB', cursor:'pointer'})"
                                            style="padding:0.4rem 0.6rem; border:1px solid; border-radius:8px; text-align:center; transition:all 0.15s; outline:none; width:100%;">
                                            <div x-text="t.time" style="font-size:0.8rem; font-weight:600; font-family:'DM Sans',sans-serif; margin-bottom:0.1rem;"></div>
                                            <div x-text="t.available_slot > 0 ? t.available_slot + ' slots' : 'Full'" style="font-size:0.65rem; font-family:'DM Sans',sans-serif; opacity:0.85;"></div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Guests --}}
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
                            <svg style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); pointer-events:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>

                    {{-- Book Button --}}
                    @auth
                        @if(Auth::user()->isHost())
                            {{-- HOST: tidak boleh booking --}}
                            <div style="background:#FDF6EE; border:1.5px solid #F3D9B8; border-radius:10px;
                                        padding:1rem; text-align:center; margin-bottom:0.6rem;">
                                <div style="font-size:1.1rem; margin-bottom:0.35rem;">🏡</div>
                                <div style="font-size:0.8rem; font-weight:600; color:#C4783A; margin-bottom:0.2rem;">
                                    Kamu adalah Host
                                </div>
                                <div style="font-size:0.75rem; color:#7A7A6E; line-height:1.5;">
                                    Host tidak bisa melakukan booking.<br>
                                    Gunakan akun traveler untuk memesan.
                                </div>
                            </div>
                        @else
                            {{-- TRAVELER: tombol booking normal --}}
                            <button @click="bookNow()"
                                :disabled="!selectedDate || !selectedTime"
                                style="width:100%; padding:0.9rem; background:#1E3A2F; color:white; border:none; border-radius:10px; font-size:0.9rem; font-weight:600; font-family:'DM Sans',sans-serif; margin-bottom:0.6rem; transition:all 0.2s; letter-spacing:0.01em;"
                                :style="(!selectedDate || !selectedTime) ? {opacity:0.45,cursor:'not-allowed'} : {opacity:1,cursor:'pointer'}"
                                onmouseover="if(!this.disabled)this.style.background='#2D4A32'"
                                onmouseout="this.style.background='#1E3A2F'">
                                Book This Experience
                            </button>
                        @endif
                    @else
                        {{-- GUEST: arahkan ke login --}}
                        <button @click="bookNow()"
                            :disabled="!selectedDate || !selectedTime"
                            style="width:100%; padding:0.9rem; background:#1E3A2F; color:white; border:none; border-radius:10px; font-size:0.9rem; font-weight:600; font-family:'DM Sans',sans-serif; margin-bottom:0.6rem; transition:all 0.2s; letter-spacing:0.01em;"
                            :style="(!selectedDate || !selectedTime) ? {opacity:0.45,cursor:'not-allowed'} : {opacity:1,cursor:'pointer'}"
                            onmouseover="if(!this.disabled)this.style.background='#2D4A32'"
                            onmouseout="this.style.background='#1E3A2F'">
                            Login to Book
                        </button>
                    @endauth

                    {{-- Wishlist --}}
                    <button @click="toggleWishlist()"
                        style="width:100%; padding:0.8rem; background:white; color:#1E3A2F; border:1.5px solid #E2DDD5; border-radius:10px; font-size:0.85rem; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif; margin-bottom:1rem; display:flex; align-items:center; justify-content:center; gap:0.4rem; transition:all 0.2s;"
                        onmouseover="this.style.borderColor='#1E3A2F'"
                        onmouseout="this.style.borderColor='#E2DDD5'">
                        <svg width="16" height="16" viewBox="0 0 24 24"
                            :fill="inWishlist ? '#EF4444' : 'none'"
                            :stroke="inWishlist ? '#EF4444' : 'currentColor'"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        <span x-text="inWishlist ? 'Saved to Wishlist' : 'Add to Wishlist'"></span>
                    </button>

                    <p style="text-align:center; font-size:0.75rem; color:#9CA3AF; margin-bottom:1.25rem;">You won't be charged yet</p>

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
                        <div style="display:flex; justify-content:space-between; font-size:0.95rem; font-weight:700; color:#1E3A2F; padding-top:0.85rem; border-top:1px solid #EDE7DC;">
                            <span>Total</span>
                            <span x-text="totalFormatted"></span>
                        </div>
                    </div>

                    {{-- Free Cancellation --}}
                    <div style="display:flex; align-items:center; justify-content:center; gap:0.4rem; font-size:0.75rem; color:#2D5240; margin-top:1rem; padding-top:1rem; border-top:1px solid #EDE7DC;">
                        🛡️ Free cancellation up to 24 hours before
                    </div>

                </div>
            </div>{{-- end kanan --}}

        </div>{{-- end grid --}}
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
        loadingTimes: false,
        guests: {{ $experience->kapasitas_min }},
        inWishlist: false,
        isLoggedIn: @auth true @else false @endauth,
        currentYear: new Date().getFullYear(),
        currentMonth: new Date().getMonth(),

        basePrice: {{ (float)($experience->harga ?? 0) }},
        serviceFee: {{ $serviceFee }},
        availableDates: @json($availableDates),

        get monthLabel() {
            return new Date(this.currentYear, this.currentMonth, 1)
                .toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        },
        get daysInMonth() {
            return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        },
        get firstDayOfMonth() {
            let d = new Date(this.currentYear, this.currentMonth, 1).getDay();
            return d === 0 ? 6 : d - 1;
        },
        get selectedDateLabel() {
            if (!this.selectedDate) return '';
            return new Date(this.selectedDate + 'T00:00:00')
                .toLocaleDateString('en-US', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
        },
        get priceFormatted()    { return 'Rp ' + this.basePrice.toLocaleString('id-ID'); },
        get subtotal()          { return this.basePrice * this.guests; },
        get subtotalFormatted() { return 'Rp ' + this.subtotal.toLocaleString('id-ID'); },
        get total()             { return this.subtotal + this.serviceFee; },
        get totalFormatted()    { return 'Rp ' + this.total.toLocaleString('id-ID'); },

        init() {},

        getDateString(day) {
            const m = String(this.currentMonth + 1).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            return `${this.currentYear}-${m}-${d}`;
        },
        isAvailable(day) { return this.availableDates.includes(this.getDateString(day)); },
        isPast(day) {
            const today = new Date(); today.setHours(0,0,0,0);
            return new Date(this.currentYear, this.currentMonth, day) <= today;
        },
        isSelected(day) { return this.selectedDate === this.getDateString(day); },
        getDayStyle(day) {
            if (this.isSelected(day))
                return { background:'#1E3A2F', color:'white', cursor:'pointer', fontWeight:'500' };
            if (this.isPast(day) || !this.isAvailable(day))
                return { background:'#F5F5F5', color:'#CCCCCC', cursor:'not-allowed' };
            return { background:'#EBF5EE', color:'#1E3A2F', cursor:'pointer', border:'1px solid #B8DFC8' };
        },

        // Fetch jam dari server saat tanggal dipilih
        async selectDate(day) {
            if (!this.isAvailable(day) || this.isPast(day)) return;
            this.selectedDate = this.getDateString(day);
            this.selectedTime = null;
            this.availableTimes = [];
            this.loadingTimes = true;
            this.showCalendar = false;

            try {
                const res  = await fetch(`/experiences/{{ $experience->slug }}/times?date=${this.selectedDate}`);
                const data = await res.json();
                this.availableTimes = data.times ?? [];
            } catch (e) {
                console.error('Gagal fetch times:', e);
                this.availableTimes = [];
            } finally {
                this.loadingTimes = false;
            }
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
                if (!this.selectedDate || !this.selectedTime) {
                    alert('Pilih tanggal dan jam terlebih dahulu.');
                    return;
                }
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

<style>
.spin-icon { animation: spin 1s linear infinite; }
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endpush