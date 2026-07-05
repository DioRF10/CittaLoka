<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    /**
     * Batas waktu (jam) setelah booking 'completed' di mana complaint masih
     * bisa diajukan. Sengaja disamakan dengan dispute window di
     * DisburseBookingPayouts — begitu lewat batas ini, host sudah berpotensi
     * dibayar, jadi complaint baru tidak lagi diterima untuk booking itu.
     */
    public const WINDOW_HOURS = 48;

    protected $fillable = [
        'booking_id',
        'filed_by_user_id',
        'filed_by_role',
        'category',
        'description',
        'status',
        'resolution_notes',
        'resolved_by_user_id',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    // ── Eligibility ──────────────────────────────────────────────────────

    /**
     * Apakah booking ini masih boleh diajukan complaint baru?
     * - status 'confirmed': selalu boleh (belum ada batas waktu, karena
     *   completed_at belum ke-set).
     * - status 'completed': boleh selama masih dalam WINDOW_HOURS sejak
     *   completed_at.
     */
    public static function canFileFor(Booking $booking): bool
    {
        if ($booking->status === 'confirmed') {
            return true;
        }

        $deadline = self::deadlineFor($booking);

        return $deadline !== null && now()->lessThanOrEqualTo($deadline);
    }

    public static function deadlineFor(Booking $booking): ?\Illuminate\Support\Carbon
    {
        if ($booking->status === 'completed' && $booking->completed_at) {
            return $booking->completed_at->copy()->addHours(self::WINDOW_HOURS);
        }

        return null;
    }

    // ── Relationships ────────────────────────────────────────────────────

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function filedBy()
    {
        return $this->belongsTo(User::class, 'filed_by_user_id');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by_user_id');
    }

    public function photos()
    {
        return $this->hasMany(ComplaintPhoto::class);
    }

    // ── Labels ───────────────────────────────────────────────────────────

    public function getCategoryLabel(): string
    {
        return match ($this->category) {
            'no_show' => 'Tidak Hadir (No-Show)',
            'not_as_described' => 'Tidak Sesuai Deskripsi',
            'safety_concern' => 'Masalah Keamanan',
            'payment_issue' => 'Masalah Pembayaran',
            'inappropriate_behavior' => 'Perilaku Tidak Pantas',
            default => 'Lainnya',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Ditinjau',
            'in_review' => 'Sedang Ditinjau',
            'resolved' => 'Terselesaikan',
            'dismissed' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getFiledByRoleLabel(): string
    {
        return $this->filed_by_role === 'host' ? 'Host' : 'Traveler';
    }
}