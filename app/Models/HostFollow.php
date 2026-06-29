<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostFollow extends Model
{
    protected $table = 'host_follows';

    protected $fillable = [
        'user_id',
        'host_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function host()
    {
        return $this->belongsTo(Host::class, 'host_id');
    }
}