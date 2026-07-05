<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\ReviewPhoto;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // ── Form Tulis Review ────────────────────────────────────────────────

    public function create(string $kode)
    {
        $booking = Booking::with(['experience.photos', 'experience.host.user'])
            ->where('kode_booking', $kode)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->firstOrFail();

        return view('pages.write-review', compact('booking'));
    }

    // ── Simpan Review ─────────────────────────────────────────────────────

    public function store(Request $request, string $kode)
    {
        $booking = Booking::where('kode_booking', $kode)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->firstOrFail();

        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'text'     => 'nullable|string|max:2000',
            'photos'   => 'nullable|array|max:6',
            'photos.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $review = Review::create([
            'booking_id'    => $booking->id,
            'user_id'       => Auth::id(),
            'experience_id' => $booking->experience_id,
            'host_id'       => $booking->host_id,
            'rating'        => $request->rating,
            'text'          => $request->text,
            'status'        => 'approved',
            'published_at'  => now(),
        ]);

        if ($request->hasFile('photos')) {
            $cloudinary = new CloudinaryService();

            foreach ($request->file('photos') as $index => $photo) {
                $uploaded = $cloudinary->upload($photo, 'cittaloka/reviews/' . $review->id);

                ReviewPhoto::create([
                    'review_id'  => $review->id,
                    'url'        => $uploaded['url'],
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('bookings.index')
            ->with('success', 'Terima kasih! Review kamu sudah berhasil terkirim dan telah tayang.');
    }
}
