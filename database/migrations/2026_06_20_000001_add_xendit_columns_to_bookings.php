<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('xendit_invoice_id')->nullable()->after('payment_status');
            $table->string('xendit_invoice_url')->nullable()->after('xendit_invoice_id');
            $table->string('xendit_payment_method')->nullable()->after('xendit_invoice_url');
            $table->timestamp('payment_expired_at')->nullable()->after('xendit_payment_method');
            $table->timestamp('paid_at')->nullable()->after('payment_expired_at');

            // Disbursement ke host
            $table->string('xendit_disbursement_id')->nullable()->after('paid_at');
            $table->enum('disbursement_status', ['pending', 'processing', 'success', 'failed'])
                  ->default('pending')->after('xendit_disbursement_id');
            $table->timestamp('disbursed_at')->nullable()->after('disbursement_status');
            $table->text('disbursement_failure_reason')->nullable()->after('disbursed_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'xendit_invoice_id',
                'xendit_invoice_url',
                'xendit_payment_method',
                'payment_expired_at',
                'paid_at',
                'xendit_disbursement_id',
                'disbursement_status',
                'disbursed_at',
                'disbursement_failure_reason',
            ]);
        });
    }
};