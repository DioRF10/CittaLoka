<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeasonalEvent extends Model
{
    protected $table = 'seasonal_events';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'start_date',
        'end_date',
        'area',
        'thumbnail_url',
        'is_recurring',
        'is_active',
    ];

    protected $casts = [
        'nama'         => 'array',
        'deskripsi'    => 'array',
        'start_date'   => 'date',
        'end_date'     => 'date',
        'is_recurring' => 'boolean',
        'is_active'    => 'boolean',
    ];

    // ── Helpers ──────────────────────────────────────────────────────────

    public function getNama(string $locale = 'id'): string
    {
        return $this->nama[$locale] ?? $this->nama['id'] ?? '-';
    }

    public function getDeskripsi(string $locale = 'id'): ?string
    {
        if (!$this->deskripsi) return null;
        return $this->deskripsi[$locale] ?? $this->deskripsi['id'] ?? null;
    }

    public function isMultiDay(): bool
    {
        return $this->end_date && !$this->end_date->equalTo($this->start_date);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'seasonal_event_experiences', 'seasonal_event_id', 'experience_id');
    }
}