<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemoryBookPhoto extends Model
{
    protected $table = 'memory_book_photos';

    const UPDATED_AT = null; // tabel ini tidak punya kolom updated_at

    protected $fillable = [
        'memory_book_id',
        'url',
        'sort_order',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function memoryBook()
    {
        return $this->belongsTo(MemoryBook::class, 'memory_book_id');
    }
}