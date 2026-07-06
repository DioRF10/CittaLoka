<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order',
        'max_discount',
        'max_usage',
        'used_count',
        'expired_at',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'expired_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Cek apakah kupon ini valid dipakai untuk order dengan subtotal tertentu.
     * Mengembalikan pesan error (string) kalau tidak valid, atau null kalau valid.
     */
    public function validationError(float $subtotal): ?string
    {
        if (!$this->is_active) {
            return 'Kupon tidak berlaku lagi.';
        }

        if ($this->expired_at && $this->expired_at->isPast()) {
            return 'Kupon sudah kedaluwarsa.';
        }

        if ($this->max_usage !== null && $this->used_count >= $this->max_usage) {
            return 'Kupon sudah mencapai batas penggunaan.';
        }

        if ($subtotal < (float) $this->min_order) {
            return 'Minimal transaksi untuk kupon ini adalah Rp ' . number_format((float) $this->min_order, 0, ',', '.') . '.';
        }

        return null;
    }

    /**
     * Hitung nominal diskon untuk subtotal tertentu.
     * Diskon dipotong dari total tagihan traveler (bukan dari platform fee),
     * jadi host_earning tidak pernah terdampak oleh kupon.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->discount_type === 'percentage') {
            $discount = $subtotal * ((float) $this->discount_value / 100);
            if ($this->max_discount !== null) {
                $discount = min($discount, (float) $this->max_discount);
            }
        } else {
            $discount = (float) $this->discount_value;
        }

        // Diskon tidak boleh melebihi subtotal itu sendiri
        return round(min($discount, $subtotal));
    }
}
