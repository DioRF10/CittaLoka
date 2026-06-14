<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Host extends Model
{
    use SoftDeletes;

    protected $table = 'host';

    protected $fillable = [
        'user_id',
        'bio',
        'village',
        'video_url',
        'ktp_path',
        'ktp_status',
        'ktp_rejection_note',
        'is_active',
        'is_verified',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}