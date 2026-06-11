<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperiencePhoto extends Model
{
    protected $table = 'experience_photos';

    public $timestamps = false;

    protected $fillable = [
        'experience_id',
        'url',
        'is_cover',
        'sort_order',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
}