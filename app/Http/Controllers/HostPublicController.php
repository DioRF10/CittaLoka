<?php

namespace App\Http\Controllers;

use App\Models\Host;
use App\Models\Experience;
use App\Models\HeritageTree;
use Illuminate\Http\Request;

class HostPublicController extends Controller
{
    public function show(int $id, Request $request)
    {
        $host = Host::with([
            'user',
            'experiences' => function($q) {
                $q->where('status', 'active')
                  ->with(['photos', 'kategori'])
                  ->orderBy('rating_avg', 'desc');
            },
        ])->findOrFail($id);

        // Heritage Tree
        $heritageTree = HeritageTree::where('host_id', $host->id)
            ->orderBy('generation_number')
            ->orderBy('sort_order')
            ->get();

        // Reviews (dummy untuk sekarang, nanti dari DB)
        $reviews = collect();

        $activeTab = $request->input('tab', 'story');
        $locale    = app()->getLocale();

        return view('pages.host-profile', compact(
            'host', 'heritageTree', 'reviews', 'activeTab', 'locale'
        ));
    }
}