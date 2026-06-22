<?php

namespace App\Http\Controllers;

use App\Models\MemoryBook;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemoryBookController extends Controller
{
    /**
     * Halaman daftar "My Memory Books" — card grid.
     * Route: GET /memory-books
     */
    public function index()
    {
        // Semua booking completed milik traveler, beserta memory book-nya (kalau sudah ada)
        $bookings = Booking::with(['experience.photos', 'memoryBook.photos'])
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('tanggal_experience', 'desc')
            ->get();

        return view('pages.my-memory-books', compact('bookings'));
    }

    /**
     * Halaman Memory Book untuk traveler (detail satu memory book).
     * Route: GET /memory-book/{booking_kode}
     */
    public function show(string $kode)
    {
        $booking = Booking::with([
            'experience.photos',
            'experience.host.user',
            'user',
        ])
            ->where(function ($query) use ($kode) {
                $query->where('kode_booking', $kode)
                      ->orWhere('kode_booking', '#' . $kode);
            })
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $memoryBook = MemoryBook::with('photos')
            ->where('booking_id', $booking->id)
            ->where('status', 'sent')
            ->firstOrFail();

        $locale = app()->getLocale();

        return view('pages.memory-book', compact('booking', 'memoryBook', 'locale'));
    }
}