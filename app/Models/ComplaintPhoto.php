<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintPhoto extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'complaint_id',
        'url',
        'sort_order',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
