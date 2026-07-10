<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->string('video_public_id')->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->dropColumn('video_public_id');
        });
    }
};
