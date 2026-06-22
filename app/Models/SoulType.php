<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoulType extends Model
{
    protected $table = 'soul_type';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'warna_hex',
        'ikon_url',
    ];

    protected $casts = [
        'nama' => 'array',
        'deskripsi' => 'array',
    ];

    // ── Helpers ───────────────────────────────────────────────────────────

    public function getNama(string $locale = 'id'): string
    {
        $nama = $this->nama;
        if (is_string($nama)) {
            $nama = json_decode($nama, true);
        }
        if (!is_array($nama)) {
            return '';
        }
        return $nama[$locale] ?? $nama['id'] ?? $nama['en'] ?? '';
    }

    public function getDeskripsi(string $locale = 'id'): string
    {
        $deskripsi = $this->deskripsi;
        if (is_string($deskripsi)) {
            $deskripsi = json_decode($deskripsi, true);
        }
        if (!is_array($deskripsi)) {
            return '';
        }
        return $deskripsi[$locale] ?? $deskripsi['id'] ?? $deskripsi['en'] ?? '';
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function users()
    {
        return $this->hasMany(User::class, 'soul_type_id');
    }
}
