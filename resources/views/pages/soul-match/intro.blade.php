@extends('layouts.app')

@section('title', 'Soul Match — CittaLoka')

@section('content')

<section class="py-20" style="background:#FAFAF8;">
    <div class="max-w-3xl mx-auto px-6 text-center">

        <p class="text-xs font-semibold tracking-widest mb-4" style="color:#C4783A;">SOUL MATCH</p>

        <h1 class="text-4xl md:text-5xl font-medium mb-6" style="font-family:'Cormorant Garamond',Georgia,serif; color:#1a2e1c;">
            Temukan Koneksi Bali yang Sesuai Jiwamu
        </h1>

        <p class="text-base mb-8" style="color:#6B7280; line-height:1.7;">
            Jawab beberapa pernyataan singkat tentang bagaimana kamu menjalani perjalanan, dan kami akan
            menunjukkan host serta experience yang paling selaras dengan caramu memaknai sebuah perjalanan.
        </p>

        <div class="flex items-center justify-center gap-4 text-sm mb-10" style="color:#9CA3AF;">
            <span>18 pernyataan</span>
            <span>•</span>
            <span>~3 menit</span>
            <span>•</span>
            <span>Hasil personal</span>
        </div>

        @if(session('error'))
            <div class="mb-6 px-4 py-3 rounded-lg text-sm" style="background:#FEF2F2; color:#B91C1C; border:1px solid #FECACA;">
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('soul-match.quiz') }}"
            class="inline-flex items-center gap-2 px-8 py-3 rounded-lg text-white font-medium transition-all hover:-translate-y-0.5"
            style="background:#1a2e1c;">
            Mulai Soul Match →
        </a>

        <p class="text-xs mt-6" style="color:#9CA3AF;">
            Terinspirasi riset psikologi travel & tourism studies — bukan tes psikologi klinis.
        </p>
    </div>
</section>

@endsection
