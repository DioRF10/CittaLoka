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
        'bank_account_holder',
        'bank_account_last4',
        'xendit_account_token',
        'bank_verified_at',
        'phone_number',
        'age',
        'expertise',
        'story',
        'language_preference',
        'ktp_selfie_path',

    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'bank_verified_at' => 'datetime',
        'bank_reviewed_at'  => 'datetime',
        'expertise'        => 'array', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'host_id');
    }

    public function heritageTree()
    {
        return $this->hasMany(HeritageTree::class, 'host_id');
    }
}