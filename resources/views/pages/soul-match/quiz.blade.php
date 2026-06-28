@extends('layouts.app')

@section('title', 'Soul Match Quiz — CittaLoka')

@section('content')

<section class="py-14" style="background:#FAFAF8;">
    <div class="max-w-2xl mx-auto px-6">

        <p class="text-xs font-semibold tracking-widest mb-2" style="color:#C4783A;">SOUL MATCH</p>
        <h1 class="text-2xl font-medium mb-2" style="font-family:'Cormorant Garamond',Georgia,serif; color:#1a2e1c;">
            Seberapa setuju kamu dengan tiap pernyataan ini?
        </h1>
        <p class="text-sm mb-8" style="color:#9CA3AF;">
            Jawab berdasarkan kebiasaan kamu sehari-hari, bukan jawaban yang "ideal".
        </p>

        @if($errors->any())
            <div class="mb-6 px-4 py-3 rounded-lg text-sm" style="background:#FEF2F2; color:#B91C1C; border:1px solid #FECACA;">
                Mohon jawab semua pernyataan sebelum melanjutkan.
            </div>
        @endif

        <form action="{{ route('soul-match.submit') }}" method="POST">
            @csrf

            <div class="flex flex-col gap-5">
                @foreach($questions as $i => $q)
                    <div class="rounded-xl border p-5" style="background:white; border-color:#E8E4DC;">
                        <p class="text-sm font-medium mb-4" style="color:#1a2e1c;">
                            {{ $i + 1 }}. {{ $q['text']['id'] }}
                        </p>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] flex-shrink-0" style="color:#9CA3AF; width:90px;">Sangat Tidak Setuju</span>
                            @for($val = 1; $val <= 5; $val++)
                                <label class="flex-1 flex flex-col items-center gap-1 cursor-pointer">
                                    <input type="radio" name="answers[{{ $q['id'] }}]" value="{{ $val }}" required
                                        class="w-4 h-4" style="accent-color:#1a2e1c;">
                                    <span class="text-[10px]" style="color:#C4BEB1;">{{ $val }}</span>
                                </label>
                            @endfor
                            <span class="text-[11px] flex-shrink-0 text-right" style="color:#9CA3AF; width:90px;">Sangat Setuju</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit"
                class="w-full mt-8 py-3 rounded-lg text-white font-medium transition-all hover:-translate-y-0.5"
                style="background:#1a2e1c;">
                Lihat Hasilnya
            </button>
        </form>
    </div>
</section>

@endsection
