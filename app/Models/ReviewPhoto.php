<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewPhoto extends Model
{
    const UPDATED_AT = null;

    protected $table = 'reviews_photo';

    protected $fillable = [
        'review_id',
        'url',
        'sort_order',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
