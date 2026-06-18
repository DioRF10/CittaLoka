<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeritageTree extends Model
{
    protected $table = 'heritage_tree';

    protected $fillable = [
        'host_id',
        'teacher_name',
        'skill_description',
        'learned_from_year',
        'generation_number',
        'sort_order',
        'photo_url',
    ];

    // Tidak ada updated_at di migration (hanya created_at)
    const UPDATED_AT = null;

    public function host()
    {
        return $this->belongsTo(Host::class);
    }
}