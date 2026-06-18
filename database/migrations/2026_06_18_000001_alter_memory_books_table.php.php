<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memory_books', function (Blueprint $table) {
            $table->string('judul')->nullable()->after('booking_id');
            $table->string('quote_highlight')->nullable()->after('host_message');
            $table->text('pesan_penutup')->nullable()->after('quote_highlight');
            $table->json('highlight_items')->nullable()->after('pesan_penutup');
            $table->timestamp('host_notified_at')->nullable()->after('sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('memory_books', function (Blueprint $table) {
            $table->dropColumn([
                'judul',
                'quote_highlight',
                'pesan_penutup',
                'highlight_items',
                'host_notified_at',
            ]);
        });
    }
};
