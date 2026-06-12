<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';

    protected $fillable = [
        'booking_id',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'gross_amount',
        'currency',
        'payment_type',
        'va_number',
        'transaction_status',
        'fraud_status',
        'snap_token',
        'pdf_url',
        'raw_response',
        'transaction_time',
        'settlement_time',
        'expired_at',
    ];

    protected $casts = [
        'raw_response'     => 'array',
        'transaction_time' => 'datetime',
        'settlement_time'  => 'datetime',
        'expired_at'       => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}