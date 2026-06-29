<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Halaman "My Wishlist" — card grid.
     * Route: GET /wishlist
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'recent'); // recent | price_low | price_high | rating

        $query = Wishlist::with(['experience.photos', 'experience.kategori', 'experience.host.user'])
            ->where('user_id', Auth::id());

        $query = match ($sort) {
            'price_low'  => $query->join('experience', 'wishlists.experience_id', '=', 'experience.id')
                                   ->orderBy('experience.harga', 'asc')
                                   ->select('wishlists.*'),
            'price_high' => $query->join('experience', 'wishlists.experience_id', '=', 'experience.id')
                                   ->orderBy('experience.harga', 'desc')
                                   ->select('wishlists.*'),
            'rating'     => $query->join('experience', 'wishlists.experience_id', '=', 'experience.id')
                                   ->orderBy('experience.rating_avg', 'desc')
                                   ->select('wishlists.*'),
            default      => $query->orderBy('wishlists.created_at', 'desc'),
        };

        $wishlists = $query->get();
        $lastUpdated = $wishlists->max('created_at');

        return view('pages.wishlist', compact('wishlists', 'sort', 'lastUpdated'));
    }

    /**
     * Toggle wishlist (tambah/hapus) via AJAX.
     * Route: POST /wishlist/toggle
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'experience_id' => 'required|integer|exists:experience,id',
        ]);

        $userId = Auth::id();
        $experienceId = $request->input('experience_id');

        $existing = Wishlist::where('user_id', $userId)
            ->where('experience_id', $experienceId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success'     => true,
                'wishlisted'  => false,
                'message'     => 'Dihapus dari wishlist',
            ]);
        }

        Wishlist::create([
            'user_id'       => $userId,
            'experience_id' => $experienceId,
        ]);

        return response()->json([
            'success'     => true,
            'wishlisted'  => true,
            'message'     => 'Ditambahkan ke wishlist',
        ]);
    }
}