<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostReviewController extends Controller
{
    private function getHost()
    {
        return Auth::user()?->host;
    }

    public function index()
    {
        $host = $this->getHost();

        if (!$host) {
            return redirect()->route('home');
        }

        $reviews = Review::with(['user', 'experience', 'photos', 'reply'])
            ->where('host_id', $host->id)
            ->where('status', 'approved')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('host.reviews', compact('reviews'));
    }

    public function reply(Request $request, Review $review)
    {
        $host = $this->getHost();

        if (!$host) {
            return redirect()->route('home');
        }

        abort_unless($review->host_id === $host->id, 403);

        $request->validate([
            'reply' => ['required', 'string', 'max:1000'],
        ]);

        if ($review->reply) {
            $review->reply->update([
                'reply' => $request->reply,
            ]);
        } else {
            ReviewReply::create([
                'review_id' => $review->id,
                'host_id' => $host->id,
                'reply' => $request->reply,
            ]);
        }

        return redirect()->back()->with('success', 'Balasan review berhasil dikirim.');
    }
}
