<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->json('soul_type_affinities')->nullable()->after('expertise');
        });
    }

    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->dropColumn('soul_type_affinities');
        });
    }
};
