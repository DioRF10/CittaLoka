<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';

    protected $fillable = [
        'user_id',
        'experience_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function experience()
    {
        return $this->belongsTo(Experience::class, 'experience_id');
    }
}