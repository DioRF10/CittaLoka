<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = ['nama', 'slug'];

    protected $casts = [
        'nama' => 'array',
    ];

    public function getNama(string $locale = 'id'): string
    {
        return $this->nama[$locale] ?? $this->nama['id'] ?? '';
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'category_id');
    }
}