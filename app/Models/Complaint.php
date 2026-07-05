<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
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
