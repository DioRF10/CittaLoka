<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoulMatchResult extends Model
{
    const UPDATED_AT = null;

    protected $table = 'soul_match_results';

    protected $fillable = [
        'user_id',
        'soul_type_id',
        'answers',
    ];

    protected $casts = [
        'answers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soulType()
    {
        return $this->belongsTo(SoulType::class, 'soul_type_id');
    }
}
