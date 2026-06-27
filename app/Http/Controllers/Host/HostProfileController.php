<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\ReviewReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostReviewController extends Controller
{
    private function getHost()
    {
        $host = Auth::user()->host;
        if (! $host) abort(403);
        return $host;
    }

    // ── List Review (yang udah approved/tayang) ────────────────────────────

    public function index()
    {
        $host = $this->getHost();

        $reviews = $host->reviews()
            ->where('status', 'approved')
            ->with(['user', 'experience', 'photos', 'reply'])
            ->latest('published_at')
            ->paginate(10);

        return view('host.reviews', compact('reviews'));
    }

    // ── Balas Review ─────────────────────────────────────────────────────

    public function reply(Request $request, string $review)
    {
        $host = $this->getHost();

        $reviewModel = $host->reviews()
            ->where('id', $review)
            ->where('status', 'approved')
            ->firstOrFail();

        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        ReviewReply::updateOrCreate(
            ['review_id' => $reviewModel->id],
            ['host_id' => $host->id, 'reply' => $request->reply]
        );

        return back()->with('success', 'Balasan berhasil dikirim.');
    }
}
