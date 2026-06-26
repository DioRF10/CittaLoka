<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'refund_percentage')) {
                $table->unsignedTinyInteger('refund_percentage')->nullable()->after('cancel_reason');
            }
            if (!Schema::hasColumn('bookings', 'refund_amount')) {
                $table->bigInteger('refund_amount')->nullable()->after('refund_percentage');
            }
            if (!Schema::hasColumn('bookings', 'refund_status')) {
                $table->enum('refund_status', ['not_applicable', 'pending', 'processing', 'success', 'failed'])
                      ->default('not_applicable')->after('refund_amount');
            }
            if (!Schema::hasColumn('bookings', 'refunded_at')) {
                $table->timestamp('refunded_at')->nullable()->after('refund_status');
            }
            if (!Schema::hasColumn('bookings', 'refund_note')) {
                $table->text('refund_note')->nullable()->after('refunded_at');
            }
            if (!Schema::hasColumn('bookings', 'cancelled_by')) {
                $table->enum('cancelled_by', ['traveler', 'host', 'admin', 'system'])->nullable()->after('refund_note');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'refund_percentage',
                'refund_amount',
                'refund_status',
                'refunded_at',
                'refund_note',
                'cancelled_by',
            ]);
        });
    }
};