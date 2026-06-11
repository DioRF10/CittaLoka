<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Drop foreign key dulu
        Schema::table('experience_availabilities', function (Blueprint $table) {
            $table->dropForeign(['experience_id']);
        });

        // 2. Baru drop unique index
        Schema::table('experience_availabilities', function (Blueprint $table) {
            $table->dropUnique('experience_availabilities_experience_id_date_unique');
        });

        // 3. Tambah kolom time
        Schema::table('experience_availabilities', function (Blueprint $table) {
            $table->time('time')->nullable()->after('date');
        });

        // 4. Buat unique constraint baru (experience_id + date + time)
        Schema::table('experience_availabilities', function (Blueprint $table) {
            $table->unique(['experience_id', 'date', 'time']);
        });

        // 5. Buat ulang foreign key
        Schema::table('experience_availabilities', function (Blueprint $table) {
            $table->foreign('experience_id')
                  ->references('id')
                  ->on('experience')
                  ->cascadeOnDelete();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::table('experience_availabilities', function (Blueprint $table) {
            $table->dropForeign(['experience_id']);
            $table->dropUnique(['experience_id', 'date', 'time']);
            $table->dropColumn('time');
            $table->unique(['experience_id', 'date']);
            $table->foreign('experience_id')
                  ->references('id')
                  ->on('experience')
                  ->cascadeOnDelete();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};