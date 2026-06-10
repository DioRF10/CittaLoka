<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('experience', function (Blueprint $table) {
            $table->json('what_you_do')->nullable()->after('deskripsi');
            $table->json('included')->nullable()->after('what_you_do');
            $table->json('not_included')->nullable()->after('included');
        });
    }

    public function down(): void
    {
        Schema::table('experience', function (Blueprint $table) {
            $table->dropColumn(['what_you_do', 'included', 'not_included']);
        });
    }
};