<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExperienceController extends Controller
{
    // ── Listing ──────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Experience::with(['host.user', 'kategori', 'photos'])
            ->where('status', 'active');

        // Filter kategori
        if ($request->kategori) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('slug', $request->input('kategori'));
            });
        }

        // Filter lokasi
        if ($request->lokasi) {
            $query->where('kabupaten', $request->input('lokasi'));
        }

        // Filter harga (format: min-max)
        if ($request->harga) {
            [$min, $max] = explode('-', $request->input('harga'));
            $query->whereBetween('harga', [(int)$min, (int)$max]);
        }

        // Filter durasi (format: min-max dalam menit)
        if ($request->durasi && $request->durasi !== 'any') {
            [$min, $max] = explode('-', $request->input('durasi'));
            $query->whereBetween('durasi_menit', [(int)$min, (int)$max]);
        }

        // Filter tipe (bisa multiple: "Outdoor,Indoor")
        if ($request->tipe) {
            $tipes = explode(',', $request->input('tipe'));
            if (in_array('Indoor', $tipes) && !in_array('Outdoor', $tipes)) {
                $query->where('is_indoor', true);
            } elseif (in_array('Outdoor', $tipes) && !in_array('Indoor', $tipes)) {
                $query->where('is_indoor', false);
            }
        }

        // Filter rating
        if ($request->rating) {
            $query->where('rating_avg', '>=', (float) $request->input('rating'));
        }

        // Search — pakai LIKE karena judul double-encoded JSON
        if ($request->search) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi_nama', 'like', "%{$search}%")
                  ->orWhere('kabupaten', 'like', "%{$search}%");
            });
        }

        // Sort
        match($request->input('sort', 'relevan')) {
            'harga_asc'  => $query->orderBy('harga', 'asc'),
            'harga_desc' => $query->orderBy('harga', 'desc'),
            'rating'     => $query->orderBy('rating_avg', 'desc'),
            default      => $query->orderBy('is_featured', 'desc')->orderBy('rating_avg', 'desc'),
        };

        $experiences = $query->paginate(12)->withQueryString();
        $kategoris   = Kategori::all();

        return view('pages.experiences', compact('experiences', 'kategoris'));
    }

    // ── Detail ───────────────────────────────────────────────────────────

    public function show(string $slug)
    {
        $experience = Experience::with([
            'host.user',
            'kategori',
            'photos',
            'availabilities' => function ($q) {
                $q->where('date', '>=', now()->toDateString())
                  ->where('is_blocked', false)
                  ->orderBy('date')
                  ->orderBy('time');
            },
        ])->where('slug', $slug)
          ->where('status', 'active')
          ->firstOrFail();

        $reviews    = [];
        $serviceFee = 25000;

        return view('pages.experience-detail', compact('experience', 'reviews', 'serviceFee'));
    }

    // ── API: Get available times by date ─────────────────────────────────

    public function getTimes(string $slug, Request $request)
    {
        $experience = Experience::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $date = $request->input('date');

        if (!$date) {
            return response()->json(['times' => []]);
        }

        $slots = $experience->availabilities()
            ->where('date', $date)
            ->where('is_blocked', false)
            ->orderBy('time')
            ->get()
            ->filter(fn($a) => $a->time !== null)
            ->map(fn($a) => [
                'time'           => Carbon::parse($a->time)->format('H:i'),
                'available_slot' => $a->getAvailableSlot(),
                'max_slot'       => $a->max_slot,
            ])
            ->values();

        return response()->json(['times' => $slots]);
    }
}