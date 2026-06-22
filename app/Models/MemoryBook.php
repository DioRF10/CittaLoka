<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemoryBook extends Model
{
    protected $table = 'memory_books';

    protected $fillable = [
        'booking_id',
        'judul',             // "Terima kasih, Sarah!" — kolom baru
        'cover_photo_url',   // foto cover untuk hero & cerita host — kolom baru
        'host_message',      // pesan personal dari host (kolom lama)
        'translated_message',// terjemahan pesan (kolom lama)
        'tourist_language',  // bahasa traveler: id / en (kolom lama)
        'quote_highlight',   // kalimat emosional di hero — kolom baru
        'pesan_penutup',     // closing message — kolom baru
        'highlight_items',   // JSON [{icon, judul, deskripsi}] — kolom baru
        'status',            // not_started | pending_host | sent | overdue
        'sent_at',
        'host_notified_at',  // kolom baru
    ];

    protected $casts = [
        'highlight_items'   => 'array',
        'sent_at'           => 'datetime',
        'host_notified_at'  => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function photos()
    {
        return $this->hasMany(MemoryBookPhoto::class, 'memory_book_id')
                    ->orderBy('sort_order');
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }

    /**
     * Ambil pesan yang tepat sesuai bahasa traveler.
     * Kalau ada translated_message dan tourist_language != 'id', pakai itu.
     * Kalau tidak, pakai host_message biasa.
     */
    public function getDisplayMessage(): string
    {
        if ($this->translated_message && $this->tourist_language !== 'id') {
            return $this->translated_message;
        }
        return $this->host_message ?? '';
    }
}