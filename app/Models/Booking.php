<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\MemoryBook;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'kode_booking',
        'user_id',
        'experience_id',
        'host_id',
        'availability_id',
        'experience_title_snapshot',
        'host_name_snapshot',
        'location_snapshot',
        'harga_per_orang_snapshot',
        'tanggal_experience',
        'jam_experience',
        'jumlah_peserta',
        'is_private',
        'total_harga',
        'platform_fee',
        'host_earning',
        'coupon_id',
        'discount_amount',
        'status',
        'payment_status',
        'notes_for_host',
        'cancelled_at',
        'cancel_reason',
        'completed_at',
    ];

    protected $casts = [
        'tanggal_experience' => 'date',
        'is_private'         => 'boolean',
        'cancelled_at'       => 'datetime',
        'completed_at'       => 'datetime',
    ];

    // ── Generate kode booking unik ────────────────────────────────────────

    public static function generateKode(): string
    {
        do {
            $kode = 'CTL-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (self::where('kode_booking', $kode)->exists());

        return $kode;
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function getTotalFormatted(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending_payment' => 'Menunggu Pembayaran',
            'confirmed'       => 'Dikonfirmasi',
            'completed'       => 'Selesai',
            'cancelled'       => 'Dibatalkan',
            'expired'         => 'Kedaluwarsa',
            'refunded'        => 'Dikembalikan',
            default           => $this->status,
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'confirmed'       => '#2D5240',
            'completed'       => '#1E3A2F',
            'pending_payment' => '#C4783A',
            'cancelled',
            'expired'         => '#C0392B',
            'refunded'        => '#7A7A6E',
            default           => '#7A7A6E',
        };
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    public function availability()
    {
        return $this->belongsTo(ExperienceAvailability::class, 'availability_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function memoryBook()
    {
        return $this->hasOne(MemoryBook::class);
    }

    // public function coupon()
    // {
    //     return $this->belongsTo(Coupon::class);
    // }
}