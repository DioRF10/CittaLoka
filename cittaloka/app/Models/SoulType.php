<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoulType extends Model
{
    protected $table = 'soul_type';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'soul_type_id');
    }
}