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
    $badge = $exp->is_seasonal ? 'Seasonal' : $kategori;
    $href = $href ?? route('experiences.show', $exp->slug);
@endphp

<a href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'experience-image-card group relative block overflow-hidden rounded-[18px] bg-[#1a2e1c] shadow-[0_14px_34px_rgba(31,40,24,0.14)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_18px_42px_rgba(31,40,24,0.18)]',
    ]) }}
    x-data="{ wishlisted: false }">

    <div class="relative h-[286px] min-h-[286px] w-full overflow-hidden">
        @if($cover)
            <img src="{{ $cover->url }}" alt="{{ $judul }}"
                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                loading="lazy">
        @else
            <div class="flex h-full w-full items-center justify-center"
                style="background:linear-gradient(135deg,#2D5240,#C4A882);">
                <span class="text-5xl">CL</span>
            </div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/28 to-black/8"></div>

        <div class="absolute left-4 right-4 top-4 flex items-start justify-between gap-3">
            <span class="max-w-[70%] rounded-full px-3 py-1.5 text-[10px] font-bold uppercase leading-none tracking-wide text-white shadow-sm"
                style="background:rgba(196,120,58,0.88);">
                {{ $badge }}
            </span>

            <button type="button" @click.prevent="wishlisted = !wishlisted"
                class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-white transition-transform hover:scale-110"
                aria-label="Save experience">
                <svg width="23" height="23" viewBox="0 0 24 24"
                    :fill="wishlisted ? '#EF4444' : 'rgba(255,255,255,0.08)'"
                    :stroke="wishlisted ? '#EF4444' : 'white'" stroke-width="1.9"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </button>
        </div>

        <div class="absolute inset-x-4 bottom-4 text-white">
            <h3 class="line-clamp-2 text-[15px] font-bold leading-snug drop-shadow-sm">
                {{ $judul }}
            </h3>

            <div class="mt-2 flex items-center justify-between gap-3 text-[11px] font-medium">
                <span class="inline-flex min-w-0 items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 flex-shrink-0 text-[#F6B84B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span class="truncate">{{ $location }}</span>
                </span>
                <span class="inline-flex flex-shrink-0 items-center gap-1">
                    <svg class="h-3.5 w-3.5 text-[#F6B84B]" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <span>{{ number_format((float) $exp->rating_avg, 1) }}</span>
                    <span class="text-white/82">({{ $exp->total_reviews }})</span>
                </span>
            </div>

            <div class="mt-2 flex items-center justify-between gap-3 text-[11px] text-white/82">
                <span class="truncate">by {{ $hostName }}</span>
                <span class="flex-shrink-0">{{ $exp->getDurasiFormatted() }}</span>
            </div>

            <div class="mt-3 text-[12px] font-semibold">
                <span>From </span>
                <span>{{ $exp->getHargaFormatted() }}</span>
                <span class="font-medium text-white/82"> / person</span>
            </div>
        </div>
    </div>
</a>
