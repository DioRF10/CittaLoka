<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('host', function (Blueprint $table) {
            if (!Schema::hasColumn('host', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('village');
            }
            if (!Schema::hasColumn('host', 'age')) {
                $table->unsignedTinyInteger('age')->nullable()->after('phone_number');
            }
            if (!Schema::hasColumn('host', 'expertise')) {
                $table->json('expertise')->nullable()->after('age'); // ["craft", "cooking"]
            }
            if (!Schema::hasColumn('host', 'story')) {
                $table->text('story')->nullable()->after('expertise'); // cerita ringkas 500 char
            }
            if (!Schema::hasColumn('host', 'language_preference')) {
                $table->enum('language_preference', ['id', 'en', 'mix'])->default('id')->after('story');
            }
            if (!Schema::hasColumn('host', 'ktp_selfie_path')) {
                $table->string('ktp_selfie_path')->nullable()->after('ktp_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'age',
                'expertise',
                'story',
                'language_preference',
                'ktp_selfie_path',
            ]);
        });
    }
};