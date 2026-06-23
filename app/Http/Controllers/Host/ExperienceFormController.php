<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\ExperiencePhoto;
use App\Models\Kategori;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExperienceFormController extends Controller
{
    private function getHost()
    {
        $host = Auth::user()->host;
        if (!$host) abort(403);
        return $host;
    }

    // ── Create — tampilkan form ───────────────────────────────────────────

    public function create()
    {
        $host      = $this->getHost();
        $kategoris = Kategori::all();

        return view('host.experiences.create', compact('host', 'kategoris'));
    }

    // ── Store — simpan experience baru ────────────────────────────────────

    public function store(Request $request)
    {
        $host = $this->getHost();

        $request->validate([
            'judul_id'        => 'required|string|max:200',
            'category_id'     => 'required|exists:kategori,id',
            'deskripsi_id'    => 'nullable|string',
            'harga'           => 'required|numeric|min:1000',
            'durasi_menit'    => 'required|integer|min:30',
            'kapasitas_min'   => 'required|integer|min:1',
            'kapasitas_max'   => 'required|integer|min:1',
            'lokasi_lat'      => 'required|numeric',
            'lokasi_lng'      => 'required|numeric',
            'lokasi_nama'     => 'required|string|max:200',
            'alamat_lengkap'  => 'required|string',
            'meeting_point'   => 'required|string',
            'kabupaten'       => 'required|string|max:100',
            'is_indoor'       => 'boolean',
            'is_seasonal'     => 'boolean',
            'photos'          => 'nullable|array|max:8',
            'photos.*'        => 'image|mimes:jpeg,jpg,png,webp|max:5120',
            'cover_index'     => 'nullable|integer',
            'included'        => 'nullable|array',
            'not_included'    => 'nullable|array',
            'what_you_do'     => 'nullable|array',
        ]);

        // Buat slug unik
        $slug = Str::slug($request->judul_id);
        $originalSlug = $slug;
        $count = 1;
        while (Experience::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Buat experience
        $experience = Experience::create([
            'host_id'        => $host->id,
            'category_id'    => $request->category_id,
            'slug'           => $slug,
            'judul'          => ['id' => $request->judul_id, 'en' => null],
            'deskripsi'      => ['id' => $request->deskripsi_id ?? '', 'en' => null],
            'harga'          => $request->harga,
            'durasi_menit'   => $request->durasi_menit,
            'kapasitas_min'  => $request->kapasitas_min,
            'kapasitas_max'  => $request->kapasitas_max,
            'lokasi_lat'     => $request->lokasi_lat,
            'lokasi_lng'     => $request->lokasi_lng,
            'lokasi_nama'    => $request->lokasi_nama,
            'alamat_lengkap' => $request->alamat_lengkap,
            'meeting_point'  => $request->meeting_point,
            'kabupaten'      => $request->kabupaten,
            'is_indoor'      => $request->boolean('is_indoor'),
            'is_seasonal'    => $request->boolean('is_seasonal'),
            'what_you_do'    => $request->what_you_do ?? [],
            'included'       => $request->included ?? [],
            'not_included'   => $request->not_included ?? [],
            'status'         => 'draft',
        ]);

        // Upload foto ke Cloudinary
        if ($request->hasFile('photos')) {
            $cloudinary  = new CloudinaryService();
            $coverIndex  = (int) $request->input('cover_index', 0);

            foreach ($request->file('photos') as $index => $photo) {
                $uploaded = $cloudinary->upload($photo, 'cittaloka/experiences/' . $experience->id);

                ExperiencePhoto::create([
                    'experience_id' => $experience->id,
                    'url'           => $uploaded['url'],
                    'is_cover'      => $index === $coverIndex,
                    'sort_order'    => $index,
                ]);
            }
        }

        return redirect()->route('host.experiences.index')
            ->with('success', 'Experience berhasil dibuat! Status: Draft. Submit untuk review admin.');
    }

    // ── Edit — tampilkan form edit ────────────────────────────────────────

    public function edit(int $id)
    {
        $host       = $this->getHost();
        $experience = Experience::with('photos')
            ->where('id', $id)
            ->where('host_id', $host->id)
            ->firstOrFail();
        $kategoris  = Kategori::all();

        return view('host.experiences.edit', compact('host', 'experience', 'kategoris'));
    }

    // ── Update — simpan perubahan ─────────────────────────────────────────

    public function update(Request $request, int $id)
    {
        $host       = $this->getHost();
        $experience = Experience::where('id', $id)
            ->where('host_id', $host->id)
            ->firstOrFail();

        $request->validate([
            'judul_id'       => 'required|string|max:200',
            'category_id'    => 'required|exists:kategori,id',
            'deskripsi_id'   => 'nullable|string',
            'harga'          => 'required|numeric|min:1000',
            'durasi_menit'   => 'required|integer|min:30',
            'kapasitas_min'  => 'required|integer|min:1',
            'kapasitas_max'  => 'required|integer|min:1',
            'lokasi_lat'     => 'required|numeric',
            'lokasi_lng'     => 'required|numeric',
            'lokasi_nama'    => 'required|string|max:200',
            'alamat_lengkap' => 'required|string',
            'meeting_point'  => 'required|string',
            'kabupaten'      => 'required|string|max:100',
            'photos'         => 'nullable|array|max:8',
            'photos.*'       => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $oldJudul = is_string($experience->judul) ? json_decode($experience->judul, true) : $experience->judul;
        $oldDeskripsi = is_string($experience->deskripsi) ? json_decode($experience->deskripsi, true) : $experience->deskripsi;

        $experience->update([
            'category_id'    => $request->category_id,
            'judul'          => ['id' => $request->judul_id, 'en' => $oldJudul['en'] ?? null],
            'deskripsi'      => ['id' => $request->deskripsi_id ?? '', 'en' => $oldDeskripsi['en'] ?? null],
            'harga'          => $request->harga,
            'durasi_menit'   => $request->durasi_menit,
            'kapasitas_min'  => $request->kapasitas_min,
            'kapasitas_max'  => $request->kapasitas_max,
            'lokasi_lat'     => $request->lokasi_lat,
            'lokasi_lng'     => $request->lokasi_lng,
            'lokasi_nama'    => $request->lokasi_nama,
            'alamat_lengkap' => $request->alamat_lengkap,
            'meeting_point'  => $request->meeting_point,
            'kabupaten'      => $request->kabupaten,
            'is_indoor'      => $request->boolean('is_indoor'),
            'is_seasonal'    => $request->boolean('is_seasonal'),
            'what_you_do'    => $request->what_you_do ?? $experience->getWhatYouDo(),
            'included'       => $request->included ?? $experience->getIncluded(),
            'not_included'   => $request->not_included ?? $experience->getNotIncluded(),
            // Reset ke draft kalau sudah active/rejected
            'status'         => in_array($experience->status, ['rejected']) ? 'draft' : $experience->status,
        ]);

        // Upload foto baru kalau ada
        if ($request->hasFile('photos')) {
            $cloudinary = new CloudinaryService();
            $existingCount = $experience->photos()->count();

            foreach ($request->file('photos') as $index => $photo) {
                if ($existingCount + $index >= 8) break; // Max 8 foto

                $uploaded = $cloudinary->upload($photo, 'cittaloka/experiences/' . $experience->id);

                ExperiencePhoto::create([
                    'experience_id' => $experience->id,
                    'url'           => $uploaded['url'],
                    'is_cover'      => $existingCount === 0 && $index === 0,
                    'sort_order'    => $existingCount + $index,
                ]);
            }
        }

        return redirect()->route('host.experiences.index')
            ->with('success', 'Experience berhasil diperbarui!');
    }

    // ── Submit for Review ─────────────────────────────────────────────────

    public function submitReview(int $id)
    {
        $host       = $this->getHost();
        $experience = Experience::where('id', $id)
            ->where('host_id', $host->id)
            ->whereIn('status', ['draft', 'rejected'])
            ->firstOrFail();

        // Validasi minimal harus ada foto
        if ($experience->photos()->count() === 0) {
            return back()->with('error', 'Tambahkan minimal 1 foto sebelum submit untuk review.');
        }

        $experience->update(['status' => 'pending_review']);

        return redirect()->route('host.experiences.index')
            ->with('success', 'Experience berhasil disubmit untuk review admin!');
    }

    // ── Delete Photo ──────────────────────────────────────────────────────

    public function deletePhoto(Request $request, int $photoId)
    {
        $host  = $this->getHost();
        $photo = ExperiencePhoto::whereHas('experience', function($q) use ($host) {
            $q->where('host_id', $host->id);
        })->findOrFail($photoId);

        // Hapus dari Cloudinary kalau ada public_id
        // (perlu simpan public_id di tabel, untuk sekarang skip)

        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
