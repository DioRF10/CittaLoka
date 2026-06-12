<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $table = 'experience';

    protected $fillable = [
        'host_id',
        'category_id',
        'slug',
        'judul',
        'deskripsi',
        'what_you_do',
        'included',
        'not_included',
        'harga',
        'durasi_menit',
        'kapasitas_min',
        'kapasitas_max',
        'lokasi_lat',
        'lokasi_lng',
        'lokasi_nama',
        'alamat_lengkap',
        'meeting_point',
        'kabupaten',
        'bahasa',
        'cancellation_policy',
        'dress_code',
        'is_indoor',
        'is_featured',
        'is_seasonal',
        'rating_avg',
        'total_reviews',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'judul' => 'array',
        'deskripsi' => 'array',
        'what_you_do' => 'array',
        'included' => 'array',
        'not_included' => 'array',
        'bahasa' => 'array',
        'dress_code' => 'array',
        'is_indoor' => 'boolean',
        'is_featured' => 'boolean',
        'is_seasonal' => 'boolean',
        'harga' => 'decimal:2',
    ];

    // ── Helpers ───────────────────────────────────────────────────────────

    public function getJudul(string $locale = 'id'): string
    {
        $judul = $this->judul;
        if (is_string($judul)) {
            $judul = json_decode($judul, true);
        }
        if (!is_array($judul))
            return '';
        return $judul[$locale] ?? $judul['id'] ?? $judul['en'] ?? '';
    }

    public function getDeskripsi(string $locale = 'id'): string
    {
        $deskripsi = $this->deskripsi;
        if (is_string($deskripsi)) {
            $deskripsi = json_decode($deskripsi, true);
        }
        if (!is_array($deskripsi))
            return '';
        return $deskripsi[$locale] ?? $deskripsi['id'] ?? $deskripsi['en'] ?? '';
    }
    public function getHargaFormatted(): string
    {
        return 'Rp ' . number_format((float) ($this->harga ?? 0), 0, ',', '.');
    }

    public function getDurasiFormatted(): string
    {
        $menit = $this->durasi_menit;
        if ($menit < 60)
            return $menit . ' menit';
        $jam = intdiv($menit, 60);
        $sisa = $menit % 60;
        return $sisa > 0 ? "{$jam} jam {$sisa} menit" : "{$jam} jam";
    }

    public function getCoverPhoto(): ?string
    {
        $cover = $this->photos()->where('is_cover', true)->first();
        return $cover?->url ?? $this->photos()->first()?->url;
    }

    // Default what_you_do jika belum diisi host
    public function getWhatYouDo(): array
    {
        $data = $this->what_you_do;
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        return is_array($data) && count($data) > 0 ? $data : [];
    }

    // Default included jika belum diisi host
    public function getIncluded(): array
    {
        $data = $this->included;
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        return is_array($data) ? $data : [];
    }
    // Default not_included jika belum diisi host
    public function getNotIncluded(): array
    {
        $data = $this->not_included;
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        return is_array($data) ? $data : [];
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function host()
    {
        return $this->belongsTo(Host::class, 'host_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }

    public function photos()
    {
        return $this->hasMany(ExperiencePhoto::class)->orderBy('sort_order');
    }

    public function availabilities()
    {
        return $this->hasMany(ExperienceAvailability::class)->orderBy('date');
    }
}