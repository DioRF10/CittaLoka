<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('host', function (Blueprint $table) {
            // Cek dulu kolom bank_name sudah ada (sesuai handover doc), kita tambah yang belum ada
            if (!Schema::hasColumn('host', 'bank_account_holder')) {
                $table->string('bank_account_holder')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('host', 'bank_account_last4')) {
                $table->string('bank_account_last4', 4)->nullable()->after('bank_account_holder');
            }
            if (!Schema::hasColumn('host', 'xendit_account_token')) {
                $table->text('xendit_account_token')->nullable()->after('bank_account_last4');
            }
            if (!Schema::hasColumn('host', 'bank_verified_at')) {
                $table->timestamp('bank_verified_at')->nullable()->after('xendit_account_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('host', function (Blueprint $table) {
            $table->dropColumn([
                'bank_account_holder',
                'bank_account_last4',
                'xendit_account_token',
                'bank_verified_at',
            ]);
        });
    }
};