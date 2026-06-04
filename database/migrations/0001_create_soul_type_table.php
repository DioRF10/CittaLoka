<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soul_type', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->json('nama');        // {"id": "Sang Pencipta", "en": "The Creator"}
            $table->json('deskripsi')->nullable();
            $table->string('warna_hex', 10);
            $table->string('ikon_url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soul_type');
    }
};
