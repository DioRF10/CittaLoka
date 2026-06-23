<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $table = 'payouts';

    protected $fillable = [
        'host_id',
        'booking_id',
        'jumlah_bruto',
        'komisi_rate',
        'komisi_platform',
        'jumlah_bersih',
        'status',
        'bank_transfer_ref',
        'paid_at',
    ];

    protected $casts = [
        'jumlah_bruto' => 'decimal:2',
        'komisi_rate' => 'decimal:2',
        'komisi_platform' => 'decimal:2',
        'jumlah_bersih' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
