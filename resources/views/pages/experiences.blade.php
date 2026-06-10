@extends('layouts.app')

@section('title', 'Explore Experiences — CittaLoka')

@section('content')

    {{-- Header --}}
    <section class="py-12 text-center" style="background: #F0EDE6;">
        <p class="text-xs font-medium uppercase tracking-widest mb-3" style="color: #C4783A; letter-spacing: 0.15em;">
            Explore Experiences</p>
        <h1 class="font-normal mb-6"
            style="font-family: 'Playfair Display', serif; font-size: clamp(28px, 4vw, 48px); color: #1a2e1c;">
            Find Your Perfect Bali Experience
        </h1>

        {{-- Search Bar --}}
        <div class="max-w-2xl mx-auto px-6">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <input type="text" id="searchInput" value="{{ request('search') }}"
                    placeholder="Search experiences, hosts, or locations..."
                    class="w-full pl-11 pr-4 py-4 rounded-2xl shadow-sm rounded-xl text-sm outline-none transition-all duration-200"
                    style="background: white; border: 1.5px solid #E2DDD5; color: #1a2e1c;"
                    onfocus="this.style.borderColor='#1a2e1c'" onblur="this.style.borderColor='#E2DDD5'">
            </div>
        </div>
    </section>

    {{-- Category Pills --}}
    <section class="bg-white border-b border-stone-200" x-data="filterBar()">

        <div class="max-w-7xl mx-auto px-6 py-4">

            <div class="flex gap-3 overflow-x-auto">

                @foreach($kategoris as $kat)

                    <button @click="
                                            filters.kategori =
                                            filters.kategori === '{{ $kat->slug }}'
                                            ? ''
                                            : '{{ $kat->slug }}';

                                            applyFilters();
                                        " class="px-5 py-2.5 rounded-full border whitespace-nowrap transition" :class="
                                            filters.kategori === '{{ $kat->slug }}'
                                            ? 'bg-[#1A2E1C] text-white border-[#1A2E1C]'
                                            : 'bg-white text-stone-700 border-stone-200'
                                        ">
                        {{ $kat->getNama(app()->getLocale()) }}
                    </button>

                @endforeach

            </div>

        </div>

    </section>

    {{-- Filter Bar --}}
    <div class="sticky top-16 z-40 border-b" style="background: #FAFAF8; border-color: #E8E4DC;" x-data="filterBar()"
        x-init="init()">
        <div class="max-w-7xl mx-auto px-6 py-3">
            <div class="flex items-center gap-2 flex-wrap">

                {{-- Kategori Dropdown --}}


                {{-- Lokasi Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-medium whitespace-nowrap transition-all"
                        :style="filters.lokasi ? 'background:#1a2e1c; color:white; border:1px solid #1a2e1c;' : 'background:white; border:1px solid #E2DDD5; color:#1a2e1c;'">
                        <span x-text="filters.lokasi || 'Lokasi'"></span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                        class="absolute top-full left-0 mt-1 rounded-xl shadow-lg z-50 min-w-40 overflow-hidden"
                        style="background:white; border:1.5px solid #E2DDD5;">
                        <button @click="filters.lokasi = ''; open = false; applyFilters()"
                            class="w-full text-left px-4 py-2.5 text-xs hover:bg-[#F0EDE6]"
                            :style="!filters.lokasi ? 'font-weight:600;color:#1a2e1c;' : 'color:#4A4A4A;'">Semua
                            Lokasi</button>
                        @foreach(['Gianyar', 'Ubud', 'Bangli', 'Badung', 'Tabanan', 'Klungkung', 'Buleleng', 'Jembrana', 'Karangasem'] as $lok)
                            <button @click="filters.lokasi = '{{ $lok }}'; open = false; applyFilters()"
                                class="w-full text-left px-4 py-2.5 text-xs hover:bg-[#F0EDE6]"
                                :style="filters.lokasi === '{{ $lok }}' ? 'font-weight:600;color:#1a2e1c;' : 'color:#4A4A4A;'">{{ $lok }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Harga Dropdown --}}
                <button @click="showFilters = true" class="
                px-4 py-2.5
                rounded-full
                border
                bg-white
                text-sm
                hover:shadow-sm
                transition">⚙️ All Filters</button>


                {{-- Sort --}}
                <div class="ml-auto flex-shrink-0">
                    <select x-model="filters.sort" @change="applyFilters()"
                        class="px-3.5 py-2 rounded-lg text-xs font-medium"
                        style="background: white; border: 1px solid #E2DDD5; color: #1a2e1c; outline:none;">
                        <option value="relevan">Sort: Relevan</option>
                        <option value="rating">Rating Tertinggi</option>
                        <option value="harga_asc">Harga Terendah</option>
                        <option value="harga_desc">Harga Tertinggi</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    {{-- Results Info --}}
    <div class="max-w-7xl mx-auto px-6 py-4">
        <p class="text-sm" style="color: #9CA3AF;">
            Menampilkan {{ $experiences->firstItem() ?? 0 }}–{{ $experiences->lastItem() ?? 0 }} dari
            {{ $experiences->total() }} experience di Bali
        </p>
    </div>
    <div class="max-w-7xl mx-auto px-6 pb-4" x-show="hasActiveFilters">

        <div class="flex flex-wrap gap-2">

            <template x-if="filters.kategori">
                <button @click="
                                    filters.kategori='';
                                    applyFilters();
                                " class="
                                    px-3 py-1.5
                                    rounded-full
                                    bg-stone-100
                                    text-sm
                                ">
                    <span x-text="filters.kategori"></span> ✕
                </button>
            </template>

            <template x-if="filters.lokasi">
                <button @click="
                                    filters.lokasi='';
                                    applyFilters();
                                " class="
                                    px-3 py-1.5
                                    rounded-full
                                    bg-stone-100
                                    text-sm
                                ">
                    <span x-text="filters.lokasi"></span> ✕
                </button>
            </template>

        </div>

    </div>

    {{-- Experience Grid --}}
    <div class="max-w-7xl mx-auto px-6 pb-16">

        @if($experiences->isEmpty())
            <div class="text-center py-20">
                <div style="font-size:3rem; margin-bottom:1rem;">🌿</div>
                <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; color:#1a2e1c; margin-bottom:0.5rem;">No
                    experiences found</h3>
                <p style="color:#9CA3AF; font-size:0.875rem;">Try adjusting your search or filter.</p>
            </div>
        @else
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($experiences as $exp)
                    @php
                        $locale = app()->getLocale();
                        $judul = $exp->getJudul($locale);
                        $cover = $exp->photos->where('is_cover', true)->first() ?? $exp->photos->first();
                        $kategori = $exp->kategori?->getNama($locale) ?? 'Experience';
                    @endphp

                    <a href="{{ route('experiences.show', $exp->slug) }}"
                        class="group cursor-pointer transition-all duration-300 hover:-translate-y-1"
                        style="text-decoration:none; color:inherit;" x-data="{ wishlisted: false }">

                        {{-- Foto --}}
                        <div class="rounded-xl overflow-hidden mb-3 relative" style="height: 280px;">
                            @if($cover)
                                <img src="{{ $cover->url }}" alt="{{ $judul }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center"
                                    style="background:linear-gradient(135deg,#2D5240,#C4A882);">
                                    <span style="font-size:3rem;">🌿</span>
                                </div>
                            @endif

                            {{-- Badge --}}
                            <div class="absolute top-3 left-3">
                                @if($exp->is_seasonal)
                                    <span class="text-xs font-medium px-2 py-1 rounded-md"
                                        style="background:#C4783A; color:white;">SEASONAL</span>
                                @else
                                    <span class="text-xs font-medium px-2 py-1 rounded-md"
                                        style="background:rgba(255,255,255,0.92); color:#1a2e1c;">{{$kategori}}</span>
                                @endif
                            </div>

                            {{-- Wishlist --}}
                            <button @click.prevent="wishlisted = !wishlisted"
                                class="absolute top-3 right-3 w-8 h-8 rounded-full flex items-center justify-center"
                                style="background:rgba(255,255,255,0.92);">
                                <svg width="14" height="14" viewBox="0 0 24 24" :fill="wishlisted ? '#EF4444' : 'none'"
                                    :stroke="wishlisted ? '#EF4444' : '#1a2e1c'" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                            </button>
                        </div>

                        {{-- Info --}}
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs flex items-center gap-1" style="color:#9CA3AF;">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                    {{ $exp->kabupaten ?? $exp->lokasi_nama }}
                                </span>
                                <span class="text-xs flex items-center gap-1" style="color:#1a2e1c;">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="#F59E0B" stroke="#F59E0B"
                                        stroke-width="1">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                    <span class="font-medium">{{ number_format($exp->rating_avg, 1) }}</span>
                                    <span style="color:#9CA3AF;">({{ $exp->total_reviews }})</span>
                                </span>
                            </div>

                            <h3 class="text-sm font-medium mb-1 group-hover:text-[#C4783A] transition-colors leading-snug"
                                style="color:#1a2e1c;">{{ $judul }}</h3>
                            <p class="text-xs mb-3" style="color:#9CA3AF;">by {{ $exp->host->user->name ?? 'Host' }}</p>

                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs" style="color:#9CA3AF;">From </span>
                                    <span class="text-sm font-semibold"
                                        style="color:#1a2e1c;">{{ $exp->getHargaFormatted() }}</span>
                                    <span class="text-xs" style="color:#9CA3AF;">/person</span>
                                </div>
                                <span class="text-xs flex items-center gap-1" style="color:#9CA3AF;">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    {{ $exp->getDurasiFormatted() }}
                                </span>
                            </div>
                        </div>

                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($experiences->hasPages())
                <div class="text-center mt-12">
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
                filters: {
                    kategori: '{{ request('kategori') }}',
                    lokasi: '{{ request('lokasi') }}',
                    harga: '{{ request('harga') }}',
                    durasi: '{{ request('durasi') }}',
                    tipe: '{{ request('tipe') }}',
                    sort: '{{ request('sort', 'relevan') }}',
                    search: '{{ request('search') }}',
                },

                kategoris: @json($kategoris->map(fn($k) => ['slug' => $k->slug, 'nama' => $k->getNama(app()->getLocale())])),

                hargaOptions: [
                    { value: '0-200000', label: 'Di bawah Rp 200.000' },
                    { value: '200000-350000', label: 'Rp 200.000 – 350.000' },
                    { value: '350000-500000', label: 'Rp 350.000 – 500.000' },
                    { value: '500000-99999999', label: 'Di atas Rp 500.000' },
                ],

                durasiOptions: [
                    { value: '0-120', label: 'Kurang dari 2 jam' },
                    { value: '120-240', label: '2 – 4 jam' },
                    { value: '240-360', label: '4 – 6 jam' },
                    { value: '360-99999', label: 'Lebih dari 6 jam' },
                ],

                get hasActiveFilters() {
                    return this.filters.kategori || this.filters.lokasi || this.filters.harga ||
                        this.filters.durasi || this.filters.tipe || this.filters.search;
                },

                init() {
                    // Search dengan debounce
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
                    if (this.filters.search) params.set('search', this.filters.search);
                    if (this.filters.kategori) params.set('kategori', this.filters.kategori);
                    if (this.filters.lokasi) params.set('lokasi', this.filters.lokasi);
                    if (this.filters.harga) params.set('harga', this.filters.harga);
                    if (this.filters.durasi) params.set('durasi', this.filters.durasi);
                    if (this.filters.tipe) params.set('tipe', this.filters.tipe);
                    if (this.filters.sort && this.filters.sort !== 'relevan') params.set('sort', this.filters.sort);

                    window.location.href = '{{ route('experiences.index') }}?' + params.toString();
                },

                resetFilters() {
                    window.location.href = '{{ route('experiences.index') }}';
                },
            }
        }
    </script>
@endpush