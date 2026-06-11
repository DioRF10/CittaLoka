<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
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
        if ($request->durasi) {
            [$min, $max] = explode('-', $request->input('durasi'));
            $query->whereBetween('durasi_menit', [(int)$min, (int)$max]);
        }

        // Filter indoor/outdoor
        if ($request->tipe === 'indoor') {
            $query->where('is_indoor', true);
        } elseif ($request->tipe === 'outdoor') {
            $query->where('is_indoor', false);
        }

        // Search
        if ($request->search) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(judul, '$.id') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(judul, '$.en') LIKE ?", ["%{$search}%"])
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

    public function show(string $slug)
    {
        $experience = Experience::with([
            'host.user',
            'kategori',
            'photos',
            'availabilities' => function ($q) {
                $q->where('date', '>=', now()->toDateString())
                  ->where('is_blocked', false)
                  ->orderBy('date');
            },
        ])->where('slug', $slug)
          ->where('status', 'active')
          ->firstOrFail();

        $reviews = [];

        return view('pages.experience-detail', compact('experience', 'reviews'));
    }
}