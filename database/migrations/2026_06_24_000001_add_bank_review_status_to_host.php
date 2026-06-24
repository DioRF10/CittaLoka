<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('host', function (Blueprint $table) {
            if (!Schema::hasColumn('host', 'bank_review_status')) {
                $table->enum('bank_review_status', ['not_verified', 'verified', 'needs_review'])
                      ->default('not_verified')
                      ->after('bank_verified_at');
            }
            if (!Schema::hasColumn('host', 'bank_review_note')) {
                $table->text('bank_review_note')->nullable()->after('bank_review_status');
            }
            if (!Schema::hasColumn('host', 'bank_reviewed_by')) {
                $table->unsignedBigInteger('bank_reviewed_by')->nullable()->after('bank_review_note');
            }
            if (!Schema::hasColumn('host', 'bank_reviewed_at')) {
                $table->timestamp('bank_reviewed_at')->nullable()->after('bank_reviewed_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->dropColumn([
                'bank_review_status',
                'bank_review_note',
                'bank_reviewed_by',
                'bank_reviewed_at',
            ]);
        });
    }
};