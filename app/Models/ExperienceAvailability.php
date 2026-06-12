<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceAvailability extends Model
{
    protected $table = 'experience_availabilities';

    protected $fillable = [
        'experience_id',
        'date',
        'time',
        'max_slot',
        'booked_slot',
        'is_blocked',
    ];

    protected $casts = [
        'date'       => 'date',
        'is_blocked' => 'boolean',
    ];

    // Sisa slot yang tersedia
    public function getAvailableSlot(): int
    {
        return $this->max_slot - $this->booked_slot;
    }

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
}