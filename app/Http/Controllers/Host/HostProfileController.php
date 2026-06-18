<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\HeritageTree;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostProfileController extends Controller
{
    private function getHost()
    {
        $host = Auth::user()->host;
        if (!$host) abort(403);
        return $host;
    }

    // ── Show Profile ──────────────────────────────────────────────────────

    public function index()
    {
        $host        = $this->getHost();
        $heritageTree = HeritageTree::where('host_id', $host->id)
            ->orderBy('sort_order')
            ->orderBy('generation_number')
            ->get();

        return view('host.profile', compact('host', 'heritageTree'));
    }

    // ── Update Profile ────────────────────────────────────────────────────

    public function update(Request $request)
    {
        $host = $this->getHost();
        $tab  = $request->input('tab', 'public');

        if ($tab === 'public') {
            $request->validate([
                'name'      => 'required|string|max:100',
                'bio'       => 'nullable|string|max:500',
                'village'   => 'nullable|string|max:100',
                'video_url' => 'nullable|url|max:255',
                'avatar'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            // Upload avatar ke Cloudinary
            if ($request->hasFile('avatar')) {
                $cloudinary = new CloudinaryService();
                $uploaded   = $cloudinary->upload(
                    $request->file('avatar'),
                    'cittaloka/avatars'
                );
                Auth::user()->update(['avatar' => $uploaded['url']]);
            }

            Auth::user()->update(['name' => $request->name]);

            $host->update([
                'bio'       => $request->bio,
                'village'   => $request->village,
                'video_url' => $request->video_url,
            ]);

        } elseif ($tab === 'account') {
            $request->validate([
                'bank_name'           => 'nullable|string|max:100',
                'bank_account_name'   => 'nullable|string|max:100',
                'bank_account_number' => 'nullable|string|max:50',
            ]);

            $host->update($request->only([
                'bank_name',
                'bank_account_name',
                'bank_account_number',
            ]));
        }

        return back()->with('success', 'Profile berhasil diperbarui!');
    }

    // ── Heritage Tree — Store ─────────────────────────────────────────────

    public function storeHeritage(Request $request)
    {
        $host = $this->getHost();

        $request->validate([
            'teacher_name'      => 'required|string|max:200',
            'skill_description' => 'nullable|string',
            'learned_from_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'generation_number' => 'nullable|integer|min:1',
        ]);

        $sortOrder = HeritageTree::where('host_id', $host->id)->max('sort_order') + 1;

        HeritageTree::create([
            'host_id'           => $host->id,
            'teacher_name'      => $request->teacher_name,
            'skill_description' => $request->skill_description,
            'learned_from_year' => $request->learned_from_year,
            'generation_number' => $request->generation_number,
            'sort_order'        => $sortOrder,
        ]);

        return back()->with('success', 'Heritage tree berhasil ditambahkan!');
    }

    // ── Heritage Tree — Delete ────────────────────────────────────────────

    public function deleteHeritage(int $id)
    {
        $host = $this->getHost();

        HeritageTree::where('id', $id)
            ->where('host_id', $host->id)
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Node berhasil dihapus.');
    }
}