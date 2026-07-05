<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── reviews ──────────────────────────────────────────────────────
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('experience_id')->constrained('experience');
            $table->foreignId('host_id')->constrained('host');
            $table->tinyInteger('rating');  // 1–5
            $table->text('text')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ── reviews_photo ────────────────────────────────────────────────
        Schema::create('reviews_photo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
            $table->string('url');
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        // ── review_replies ───────────────────────────────────────────────
        Schema::create('review_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->unique()->constrained('reviews')->cascadeOnDelete();
            $table->foreignId('host_id')->constrained('host');
            $table->text('reply');
            $table->timestamp('created_at')->useCurrent();
        });

        // ── memory_books ─────────────────────────────────────────────────
        Schema::create('memory_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->text('host_message')->nullable();
            $table->text('translated_message')->nullable();
            $table->string('tourist_language', 10)->nullable();
            $table->enum('status', ['not_started', 'pending_host', 'sent', 'overdue'])->default('not_started');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // ── memory_book_photos ───────────────────────────────────────────
        Schema::create('memory_book_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_book_id')->constrained('memory_books')->cascadeOnDelete();
            $table->string('url');
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        // ── guest_chapters ───────────────────────────────────────────────
        Schema::create('guest_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('host_id')->constrained('host');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('cover_url')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // ── wishlists ────────────────────────────────────────────────────
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('experience_id')->constrained('experience')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'experience_id']);
        });

        // ── follows ──────────────────────────────────────────────────────
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('host_id')->constrained('host')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'host_id']);
        });

        // ── seasonal_events ──────────────────────────────────────────────
        Schema::create('seasonal_events', function (Blueprint $table) {
            $table->id();
            $table->json('nama');
            $table->string('slug', 150)->unique();
            $table->json('deskripsi')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('area', 100)->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── seasonal_event_experiences (pivot) ───────────────────────────
        Schema::create('seasonal_event_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seasonal_event_id')->constrained('seasonal_events')->cascadeOnDelete();
            $table->foreignId('experience_id')->constrained('experience')->cascadeOnDelete();

            $table->unique(['seasonal_event_id', 'experience_id'], 'see_unique');
        });

        // ── payouts ──────────────────────────────────────────────────────
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('host');
            $table->foreignId('booking_id')->constrained('bookings');
            $table->decimal('jumlah_bruto', 12, 2);
            $table->decimal('komisi_rate', 5, 2)->default(10.00);
            $table->decimal('komisi_platform', 12, 2);
            $table->decimal('jumlah_bersih', 12, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('bank_transfer_ref', 100)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // ── coupon_usages ────────────────────────────────────────────────
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('booking_id')->constrained('bookings');
            $table->timestamp('used_at')->useCurrent();

            $table->unique(['coupon_id', 'user_id']);
        });


        // ── notif ────────────────────────────────────────────────────────
        Schema::create('notif', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 50);
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });

        // ── admin_logs ───────────────────────────────────────────────────
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users');
            $table->string('aksi');
            $table->string('target_type', 100)->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('detail')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
        Schema::dropIfExists('notif');

        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('seasonal_event_experiences');
        Schema::dropIfExists('seasonal_events');
        Schema::dropIfExists('follows');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('guest_chapters');
        Schema::dropIfExists('memory_book_photos');
        Schema::dropIfExists('memory_books');
        Schema::dropIfExists('review_replies');
        Schema::dropIfExists('reviews_photo');
        Schema::dropIfExists('reviews');
    }
};
