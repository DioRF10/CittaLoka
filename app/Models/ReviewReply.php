<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    const UPDATED_AT = null;

    protected $table = 'review_replies';

    protected $fillable = [
        'review_id',
        'host_id',
        'reply',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function host()
    {
        return $this->belongsTo(Host::class);
    }
}
