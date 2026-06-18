<?php

namespace App\Http\Controllers;

use App\Models\MemoryBook;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemoryBookController extends Controller
{
    /**
     * Halaman Memory Book untuk traveler.
     * Route: GET /memory-book/{booking_kode}
     */
    public function show(string $kode)
    {
        // Cari booking milik traveler yang sedang login
        $booking = Booking::with([
            'experience.photos',
            'experience.host.user',
            'user',
        ])
        ->where(function($query) use ($kode) {
            $query->where('kode_booking', $kode)
                ->orWhere('kode_booking', '#' . $kode);
        })
        ->where('user_id', Auth::id())
        ->firstOrFail();

        // Cari memory book yang sudah dikirim
        $memoryBook = MemoryBook::with('photos')
            ->where('booking_id', $booking->id)
            ->where('status', 'sent')
            ->firstOrFail();

        $locale = app()->getLocale();

        return view('pages.memory-book', compact('booking', 'memoryBook', 'locale'));
    }
}
