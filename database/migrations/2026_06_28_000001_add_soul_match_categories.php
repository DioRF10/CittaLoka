<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('kategori')->insertOrIgnore([
            [
                'nama' => json_encode(['id' => 'Petualangan Alam', 'en' => 'Nature Adventure']),
                'slug' => 'adventure',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => json_encode(['id' => 'Pemandangan & Seni', 'en' => 'Scenic & Art']),
                'slug' => 'scenic',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('kategori')->whereIn('slug', ['adventure', 'scenic'])->delete();
    }
};
