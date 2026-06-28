<?php

namespace App\Http\Controllers;

use App\Models\SoulMatchResult;
use App\Models\SoulType;
use App\Services\SoulQuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoulMatchController extends Controller
{
    public function __construct(private SoulQuizService $quiz)
    {
    }

    // ── Halaman intro ────────────────────────────────────────────────────

    public function intro()
    {
        return view('pages.soul-match.intro');
    }

    // ── Halaman kuesioner ────────────────────────────────────────────────

    public function show()
    {
        $questions = SoulQuizService::QUESTIONS;
        shuffle($questions);

        return view('pages.soul-match.quiz', [
            'questions' => $questions,
        ]);
    }

    // ── Proses jawaban & simpan hasil ───────────────────────────────────

    public function submit(Request $request)
    {
        $rules = [];
        foreach (SoulQuizService::QUESTIONS as $q) {
            $rules["answers.{$q['id']}"] = 'required|integer|min:1|max:5';
        }
        $validated = $request->validate($rules);

        $scores = $this->quiz->scoreAnswers($validated['answers']);
        $result = $this->quiz->determineResult($scores);

        $soulType = SoulType::where('kode', $result['primary'])->first();

        if (! $soulType) {
            return back()->with('error', 'Terjadi kesalahan saat menghitung hasil. Coba lagi.');
        }

        $record = SoulMatchResult::create([
            'user_id' => Auth::id(),
            'soul_type_id' => $soulType->id,
            'answers' => $validated['answers'],
        ]);

        if (Auth::check()) {
            Auth::user()->update(['soul_type_id' => $soulType->id]);
        } else {
            session(['soul_match_result_id' => $record->id]);
        }

        return redirect()->route('soul-match.results', ['result' => $record->id]);
    }

    // ── Halaman hasil ────────────────────────────────────────────────────

    public function results(Request $request)
    {
        $resultId = $request->query('result') ?? session('soul_match_result_id');

        $result = SoulMatchResult::with('soulType')->find($resultId);

        if (! $result) {
            return redirect()->route('soul-match.intro')
                ->with('error', 'Hasil quiz tidak ditemukan. Coba isi quiz-nya lagi.');
        }

        $secondaryKode = $this->quiz->determineResult(
            $this->quiz->scoreAnswers($result->answers)
        )['secondary'];

        $secondaryType = $secondaryKode ? SoulType::where('kode', $secondaryKode)->first() : null;

        $matchedHosts = $this->quiz->findMatchingHosts($result->soulType->kode);

        return view('pages.soul-match.results', [
            'soulType' => $result->soulType,
            'secondaryType' => $secondaryType,
            'matchedHosts' => $matchedHosts,
        ]);
    }
}
