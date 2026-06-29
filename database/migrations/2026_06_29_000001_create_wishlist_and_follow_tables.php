<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('wishlists')) {
            Schema::create('wishlists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('experience_id')->constrained('experience')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['user_id', 'experience_id']); // tidak boleh duplikat
            });
        }

        if (!Schema::hasTable('host_follows')) {
            Schema::create('host_follows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('host_id')->constrained('host')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['user_id', 'host_id']); // tidak boleh duplikat
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('host_follows');
    }
};