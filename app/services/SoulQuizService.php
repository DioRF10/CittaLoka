<?php

namespace App\Services;

use App\Models\Host;
use App\Models\SoulType;
use Illuminate\Support\Collection;

class SoulQuizService
{
    /**
     * Bank 18 pernyataan (3 per Soul Type, termasuk 1 reverse-item per dimensi).
     * Subset representatif dari instrumen lengkap 40-item (lihat dokumen
     * "Soul Match Quiz — Rancangan Instrumen & Metodologi" untuk basis teorinya).
     *
     * direction: 'positive' = skor langsung dipakai, 'reverse' = skor dikoreksi (6 - skor).
     */
    public const QUESTIONS = [
        ['id' => 1, 'soul_type' => 'the_creator', 'direction' => 'positive',
            'text' => ['id' => 'Saya merasa paling puas saat berhasil membuat sesuatu dengan tangan saya sendiri.', 'en' => 'I feel most satisfied when I successfully make something with my own hands.']],
        ['id' => 2, 'soul_type' => 'the_creator', 'direction' => 'positive',
            'text' => ['id' => 'Saat traveling, saya ingin membawa pulang sesuatu yang saya buat sendiri, bukan sekadar oleh-oleh.', 'en' => 'When traveling, I want to bring home something I made myself, not just a souvenir.']],
        ['id' => 3, 'soul_type' => 'the_creator', 'direction' => 'reverse',
            'text' => ['id' => 'Saya lebih suka menjadi penonton daripada peserta aktif dalam sebuah aktivitas.', 'en' => 'I prefer being a spectator rather than an active participant in an activity.']],

        ['id' => 4, 'soul_type' => 'the_seeker', 'direction' => 'positive',
            'text' => ['id' => 'Saya sering merenungkan apa arti suatu pengalaman bagi hidup saya sendiri.', 'en' => 'I often reflect on what an experience means for my own life.']],
        ['id' => 5, 'soul_type' => 'the_seeker', 'direction' => 'positive',
            'text' => ['id' => 'Saya traveling bukan sekadar untuk senang-senang, tapi untuk memahami diri lebih dalam.', 'en' => 'I travel not just for fun, but to understand myself more deeply.']],
        ['id' => 6, 'soul_type' => 'the_seeker', 'direction' => 'reverse',
            'text' => ['id' => 'Saya jarang memikirkan makna yang lebih dalam dari sebuah pengalaman — saya hanya menjalaninya.', 'en' => 'I rarely think about the deeper meaning of an experience — I just go through it.']],

        ['id' => 7, 'soul_type' => 'the_connector', 'direction' => 'positive',
            'text' => ['id' => 'Bagian paling berkesan dari perjalanan saya biasanya orang-orangnya, bukan tempatnya.', 'en' => 'The most memorable part of my trips is usually the people, not the places.']],
        ['id' => 8, 'soul_type' => 'the_connector', 'direction' => 'positive',
            'text' => ['id' => 'Saya lebih suka makan bersama keluarga lokal daripada makan sendirian di tempat bagus.', 'en' => 'I prefer eating with a local family over dining alone somewhere fancy.']],
        ['id' => 9, 'soul_type' => 'the_connector', 'direction' => 'reverse',
            'text' => ['id' => 'Saya cenderung menjaga jarak dengan orang yang baru saya kenal.', 'en' => 'I tend to keep my distance from people I just met.']],

        ['id' => 10, 'soul_type' => 'the_guardian', 'direction' => 'positive',
            'text' => ['id' => 'Saya merasa punya tanggung jawab untuk ikut menjaga sebuah tradisi tetap hidup, bukan sekadar menontonnya.', 'en' => 'I feel responsible for helping keep a tradition alive, not just watching it.']],
        ['id' => 11, 'soul_type' => 'the_guardian', 'direction' => 'positive',
            'text' => ['id' => 'Saya khawatir tradisi lokal akan hilang karena modernisasi.', 'en' => 'I worry that local traditions will disappear due to modernization.']],
        ['id' => 12, 'soul_type' => 'the_guardian', 'direction' => 'reverse',
            'text' => ['id' => 'Saya tidak terlalu peduli apakah suatu tradisi akan tetap ada di masa depan atau tidak.', 'en' => 'I don\u2019t really care whether a tradition survives into the future or not.']],

        ['id' => 13, 'soul_type' => 'the_wanderer', 'direction' => 'positive',
            'text' => ['id' => 'Saya lebih suka traveling tanpa itinerary yang ketat.', 'en' => 'I prefer traveling without a strict itinerary.']],
        ['id' => 14, 'soul_type' => 'the_wanderer', 'direction' => 'positive',
            'text' => ['id' => 'Saya merasa bersemangat, bukan takut, saat tersesat di tempat yang asing.', 'en' => 'I feel excited, not scared, when I get lost somewhere unfamiliar.']],
        ['id' => 15, 'soul_type' => 'the_wanderer', 'direction' => 'reverse',
            'text' => ['id' => 'Saya lebih suka semuanya direncanakan dengan rapi daripada dadakan.', 'en' => 'I prefer everything to be neatly planned rather than spontaneous.']],

        ['id' => 16, 'soul_type' => 'the_dreamer', 'direction' => 'positive',
            'text' => ['id' => 'Saya traveling untuk sejenak melarikan diri dari rutinitas, bukan untuk menjadi produktif.', 'en' => 'I travel to briefly escape my routine, not to be productive.']],
        ['id' => 17, 'soul_type' => 'the_dreamer', 'direction' => 'positive',
            'text' => ['id' => 'Saya bisa duduk diam cukup lama hanya untuk menikmati momen tanpa melakukan apa-apa.', 'en' => 'I can sit quietly for a while just enjoying a moment without doing anything.']],
        ['id' => 18, 'soul_type' => 'the_dreamer', 'direction' => 'reverse',
            'text' => ['id' => 'Saya lebih fokus pada hasil nyata dari sebuah perjalanan dibanding perasaan yang muncul selama perjalanan.', 'en' => 'I focus more on tangible outcomes of a trip than on the feelings it brings up.']],
    ];

    /**
     * Pemetaan Soul Type -> slug kategori experience, dipakai untuk mencari
     * host yang relevan. Bukan algoritma matching mendalam — ini heuristik
     * berbasis kategori, dipilih karena setiap experience sudah wajib
     * punya kategori (tidak butuh data tambahan / tagging manual).
     */
    public const KATEGORI_MAP = [
        'the_creator'   => ['craft', 'cooking'],
        'the_seeker'    => ['spiritual'],
        'the_connector' => ['cooking'],
        'the_guardian'  => ['spiritual', 'dance', 'music'],
        'the_wanderer'  => ['adventure', 'farming'],
        'the_dreamer'   => ['scenic'],
    ];

    /**
     * Hitung skor tiap dimensi dari jawaban mentah.
     *
     * @param array<int, int> $answers [question_id => skor 1-5]
     * @return array<string, float> [soul_type_kode => skor rata-rata]
     */
    public function scoreAnswers(array $answers): array
    {
        $grouped = [];

        foreach (self::QUESTIONS as $q) {
            $raw = $answers[$q['id']] ?? null;
            if ($raw === null) {
                continue;
            }

            $score = $q['direction'] === 'reverse' ? (6 - (int) $raw) : (int) $raw;
            $grouped[$q['soul_type']][] = $score;
        }

        $averages = [];
        foreach ($grouped as $kode => $scores) {
            $averages[$kode] = round(array_sum($scores) / count($scores), 2);
        }

        return $averages;
    }

    /**
     * Tentukan Soul Type utama (dan sekunder kalau hasilnya berdekatan).
     *
     * @return array{primary: string, secondary: ?string, scores: array}
     */
    public function determineResult(array $scores): array
    {
        arsort($scores);
        $kodes = array_keys($scores);

        $primary = $kodes[0] ?? null;
        $secondary = null;

        if (isset($kodes[1]) && ($scores[$kodes[0]] - $scores[$kodes[1]]) < 0.3) {
            $secondary = $kodes[1];
        }

        return ['primary' => $primary, 'secondary' => $secondary, 'scores' => $scores];
    }

    /**
     * Cari host yang paling relevan untuk Soul Type tertentu.
     *
     * Catatan: skor "match" yang ditampilkan adalah heuristik untuk
     * keperluan tampilan (kombinasi rating host & jumlah experience yang
     * relevan), bukan skor probabilistik yang presisi.
     */
    public function findMatchingHosts(string $soulTypeKode, int $limit = 3): Collection
    {
        $slugs = self::KATEGORI_MAP[$soulTypeKode] ?? [];

        // ── Prioritas 1: host yang sudah declare soul type ini sendiri ──────
        $declaredHosts = Host::query()
            ->where('is_active', true)
            ->whereJsonContains('soul_type_affinities', $soulTypeKode)
            ->with(['user'])
            ->orderByDesc('rating_avg')
            ->take($limit)
            ->get();

        $hosts = $declaredHosts;

        // ── Prioritas 2: lengkapi sisa slot via kategori experience ─────────
        if ($hosts->count() < $limit && ! empty($slugs)) {
            $excludeIds = $hosts->pluck('id')->all();

            $categoryHosts = Host::query()
                ->where('is_active', true)
                ->whereNotIn('id', $excludeIds)
                ->whereHas('experiences', function ($q) use ($slugs) {
                    $q->where('status', 'active')
                        ->whereHas('kategori', fn ($k) => $k->whereIn('slug', $slugs));
                })
                ->with(['user'])
                ->withCount(['experiences as matching_experiences_count' => function ($q) use ($slugs) {
                    $q->where('status', 'active')
                        ->whereHas('kategori', fn ($k) => $k->whereIn('slug', $slugs));
                }])
                ->orderByDesc('rating_avg')
                ->orderByDesc('matching_experiences_count')
                ->take($limit - $hosts->count())
                ->get();

            $hosts = $hosts->merge($categoryHosts);
        }

        return $hosts->map(function (Host $host) use ($slugs, $soulTypeKode) {
            $isDeclared = in_array($soulTypeKode, $host->soul_type_affinities ?? []);

            $bestExperience = $host->experiences()
                ->where('status', 'active')
                ->when(! empty($slugs), fn ($q) => $q->whereHas('kategori', fn ($k) => $k->whereIn('slug', $slugs)))
                ->with(['photos', 'kategori'])
                ->orderByDesc('rating_avg')
                ->first();

            $matchingCount = $host->matching_experiences_count ?? ($bestExperience ? 1 : 0);
            $ratingComponent = ((float) $host->rating_avg / 5) * 20;
            $countComponent = min($matchingCount, 3) * 3;
            $declaredBonus = $isDeclared ? 6 : 0;
            $matchScore = (int) min(99, round(70 + $ratingComponent + $countComponent + $declaredBonus));

            $host->setAttribute('match_score', $matchScore);
            $host->setAttribute('best_experience', $bestExperience);
            $host->setAttribute('is_declared_match', $isDeclared);

            return $host;
        });
    }
}
