@extends('layouts.app')

@section('title', 'Hasil Soul Match — CittaLoka')

@section('content')

<section class="py-16" style="background:#FAFAF8;">
    <div class="max-w-5xl mx-auto px-6">

        {{-- ===== Reveal kartu Soul Type ===== --}}
        <div class="rounded-2xl p-10 text-center mb-16" style="background:#EFEBE3;">
            <p class="text-xs font-semibold tracking-widest mb-4" style="color:#C4783A;">YOUR SOUL TYPE</p>

            <div class="w-16 h-16 rounded-xl mx-auto mb-5 flex items-center justify-center"
                style="background:{{ $soulType->warna_hex }}1A;">
                <span class="text-3xl" style="color:{{ $soulType->warna_hex }};">✦</span>
            </div>

            <h1 class="text-4xl md:text-5xl font-medium mb-4" style="font-family:'Cormorant Garamond',Georgia,serif; color:#1a2e1c;">
                {{ $soulType->getNama() }}
            </h1>

            <p class="max-w-xl mx-auto text-sm mb-2" style="color:#6B7280; line-height:1.8;">
                {{ $soulType->getDeskripsi() }}
            </p>

            @if($secondaryType)
                <p class="text-xs mt-3" style="color:#9CA3AF;">
                    Dengan sedikit jiwa <span style="color:#1a2e1c; font-weight:600;">{{ $secondaryType->getNama() }}</span> di dalamnya.
                </p>
            @endif

            <div class="flex items-center justify-center gap-3 mt-8">
                <a href="{{ route('soul-match.quiz') }}"
                    class="px-5 py-2.5 rounded-lg text-sm font-medium transition-colors"
                    style="background:white; color:#1a2e1c; border:1.5px solid #E2DDD5;">
                    ↻ Isi Ulang Quiz
                </a>
            </div>
        </div>

        {{-- ===== Top 3 Matches (host) ===== --}}
        <div class="text-center mb-10">
            <h2 class="text-3xl font-medium mb-3" style="font-family:'Cormorant Garamond',Georgia,serif; color:#1a2e1c;">
                Host yang Cocok Buat Kamu
            </h2>
            <p class="text-sm max-w-lg mx-auto" style="color:#9CA3AF;">
                Berdasarkan jiwa {{ $soulType->getNama() }}-mu, ini host yang experience-nya paling selaras.
            </p>
        </div>

        @if($matchedHosts->isEmpty())
            <p class="text-center text-sm py-10" style="color:#9CA3AF;">
                Belum ada host yang cocok buat tipe ini saat ini — coba lagi nanti ya, host baru terus bertambah!
            </p>
        @else
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($matchedHosts as $host)
                    @php
                        $photo = $host->user?->avatar
                            ? $host->user->avatarUrl()
                            : ($host->best_experience?->photos->first()->url ?? null);
                        $location = $host->best_experience?->kabupaten ?? $host->village;
                    @endphp
                    <div class="rounded-xl overflow-hidden border" style="background:white; border-color:#E8E4DC;">
                        <div class="relative h-44" style="background:#EFEBE3;">
                            @if($photo)
                                <img src="{{ $photo }}" alt="{{ $host->user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl font-medium" style="color:#C4BEB1;">
                                    {{ strtoupper(substr($host->user->name ?? '?', 0, 1)) }}
                                </div>
                            @endif
                            <span class="absolute top-3 right-3 text-xs font-semibold px-2.5 py-1 rounded-full"
                                style="background:white; color:#C4783A;">
                                ★ {{ $host->match_score }}% Match
                            </span>
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-medium" style="font-family:'Cormorant Garamond',Georgia,serif; color:#1a2e1c;">
                                {{ $host->user->name ?? 'Host' }}
                            </h3>
                            <p class="text-xs uppercase tracking-wide mb-3" style="color:#9CA3AF;">
                                {{ $location ?? 'Bali' }}
                            </p>

                            @if($host->bio)
                                <p class="text-sm mb-3" style="color:#6B7280; line-height:1.6;">
                                    {{ \Illuminate\Support\Str::limit($host->bio, 90) }}
                                </p>
                            @endif

                            @if($host->best_experience)
                                <div class="rounded-lg p-3 mb-4" style="background:#F7F3ED;">
                                    <p class="text-[10px] font-semibold tracking-wide mb-1" style="color:#C4783A;">COCOK LEWAT EXPERIENCE INI</p>
                                    <p class="text-sm font-medium" style="color:#1a2e1c;">{{ $host->best_experience->getJudul() }}</p>
                                </div>
                            @endif

                            <div class="flex flex-col gap-2">
                                <a href="{{ route('hosts.show', $host->id) }}"
                                    class="text-center text-sm font-medium py-2 rounded-lg text-white"
                                    style="background:#1a2e1c;">
                                    View Profile
                                </a>
                                <a href="{{ route('hosts.show', $host->id) }}#experiences"
                                    class="text-center text-sm font-medium py-2 rounded-lg"
                                    style="background:white; color:#1a2e1c; border:1.5px solid #E2DDD5;">
                                    See Experiences
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection
