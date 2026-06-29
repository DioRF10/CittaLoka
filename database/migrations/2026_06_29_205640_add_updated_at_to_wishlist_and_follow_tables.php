<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah updated_at ke wishlists jika belum ada
        if (!Schema::hasColumn('wishlists', 'updated_at')) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable();
            });
        }

        // Tambah updated_at ke host_follows jika belum ada
        if (Schema::hasTable('host_follows') && !Schema::hasColumn('host_follows', 'updated_at')) {
            Schema::table('host_follows', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('wishlists', 'updated_at')) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->dropColumn('updated_at');
            });
        }
        if (Schema::hasTable('host_follows') && Schema::hasColumn('host_follows', 'updated_at')) {
            Schema::table('host_follows', function (Blueprint $table) {
                $table->dropColumn('updated_at');
            });
        }
    }
};
