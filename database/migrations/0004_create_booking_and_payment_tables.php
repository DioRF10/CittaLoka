<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── soul_match_results ───────────────────────────────────────────
        Schema::create('soul_match_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('soul_type_id')->constrained('soul_type');
            $table->json('answers');    // Array jawaban 5 pertanyaan
            $table->timestamp('created_at')->useCurrent();
        });

        // ── soul_match_cache ─────────────────────────────────────────────
        Schema::create('soul_match_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('experience_id')->constrained('experience')->cascadeOnDelete();
            $table->decimal('match_score', 5, 2);
            $table->text('match_reason')->nullable();
            $table->timestamp('calculated_at')->useCurrent();

            $table->unique(['user_id', 'experience_id']);
        });

        // ── coupons (dibuat duluan karena bookings FK ke sini) ───────────
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 12, 2);
            $table->decimal('min_order', 12, 2)->default(0);
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->integer('max_usage')->nullable();
            $table->integer('used_count')->default(0);
            $table->timestamp('expired_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── bookings ─────────────────────────────────────────────────────
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 100)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('experience_id')->constrained('experience');
            $table->foreignId('host_id')->constrained('host');
            $table->foreignId('availability_id')->constrained('experience_availabilities');

            // Snapshot — tidak berubah meski experience diedit
            $table->string('experience_title_snapshot');
            $table->string('host_name_snapshot');
            $table->string('location_snapshot');
            $table->decimal('harga_per_orang_snapshot', 12, 2);

            $table->date('tanggal_experience');
            $table->integer('jumlah_peserta');
            $table->boolean('is_private')->default(false);
            $table->decimal('total_harga', 12, 2);
            $table->decimal('platform_fee', 12, 2);   // 10% komisi
            $table->decimal('host_earning', 12, 2);    // 90% untuk host

            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->decimal('discount_amount', 12, 2)->default(0);

            $table->enum('status', ['pending_payment', 'confirmed', 'completed', 'cancelled', 'expired', 'refunded'])->default('pending_payment');
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'failed', 'expired', 'refunded'])->default('unpaid');

            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // ── payment ──────────────────────────────────────────────────────
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->string('midtrans_order_id', 100)->unique();
            $table->string('midtrans_transaction_id', 100)->unique()->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->string('currency', 10)->default('IDR');
            $table->string('payment_type', 50)->nullable();
            $table->string('va_number', 100)->nullable();
            $table->enum('transaction_status', [
                'pending', 'settlement', 'capture', 'deny',
                'cancel', 'expire', 'failure', 'refund', 'partial_refund'
            ]);
            $table->string('fraud_status', 50)->nullable();
            $table->text('snap_token')->nullable();
            $table->string('pdf_url')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('soul_match_cache');
        Schema::dropIfExists('soul_match_results');
    }
};
