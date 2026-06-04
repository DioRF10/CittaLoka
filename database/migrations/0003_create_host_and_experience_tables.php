<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── host ─────────────────────────────────────────────────────────
        Schema::create('host', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('village', 100)->nullable();
            $table->string('video_url')->nullable();
            $table->string('ktp_path')->nullable();
            $table->enum('ktp_status', ['unverified', 'pending', 'verified', 'rejected'])->default('unverified');
            $table->text('ktp_rejection_note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_name', 100)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ── heritage_tree ────────────────────────────────────────────────
        Schema::create('heritage_tree', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('host')->cascadeOnDelete();
            $table->string('teacher_name');
            $table->text('skill_description')->nullable();
            $table->integer('learned_from_year')->nullable();
            $table->smallInteger('generation_number')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['host_id', 'sort_order']);
        });

        // ── kategori ─────────────────────────────────────────────────────
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->json('nama');       // {"id": "Seni", "en": "Arts"}
            $table->string('slug', 100)->unique();
            $table->timestamps();
        });

        // ── experience ───────────────────────────────────────────────────
        Schema::create('experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('host')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('kategori');
            $table->string('slug', 150)->unique();
            $table->json('judul');
            $table->json('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->integer('durasi_menit');
            $table->integer('kapasitas_min')->default(1);
            $table->integer('kapasitas_max');
            $table->decimal('lokasi_lat', 10, 8);
            $table->decimal('lokasi_lng', 11, 8);
            $table->string('lokasi_nama');
            $table->text('alamat_lengkap');
            $table->text('meeting_point');
            $table->string('kabupaten', 100);
            $table->json('bahasa')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->json('dress_code')->nullable();
            $table->boolean('is_indoor')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_seasonal')->default(false);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->enum('status', ['draft', 'pending_review', 'active', 'inactive', 'rejected', 'archived'])->default('draft');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        // ── experience_photos ────────────────────────────────────────────
        Schema::create('experience_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained('experience')->cascadeOnDelete();
            $table->string('url');
            $table->boolean('is_cover')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        // ── experience_availabilities ────────────────────────────────────
        Schema::create('experience_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained('experience')->cascadeOnDelete();
            $table->date('date');
            $table->integer('max_slot');
            $table->integer('booked_slot')->default(0);
            $table->boolean('is_blocked')->default(false);
            $table->timestamps();

            $table->unique(['experience_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experience_availabilities');
        Schema::dropIfExists('experience_photos');
        Schema::dropIfExists('experience');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('heritage_tree');
        Schema::dropIfExists('host');
    }
};
