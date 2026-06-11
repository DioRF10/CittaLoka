<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoulTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'kode' => 'the_creator',
                'nama' => json_encode(['id' => 'Sang Pencipta', 'en' => 'The Creator']),
                'deskripsi' => json_encode([
                    'id' => 'Kamu menemukan makna melalui proses menciptakan. Seniman, pengrajin, dan inovator adalah jiwamu.',
                    'en' => 'You find meaning through the act of creating. Artists, craftspeople, and innovators are your kin.',
                ]),
                'warna_hex' => '#E8825A',
                'ikon_url' => '/icons/soul/creator.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'the_seeker',
                'nama' => json_encode(['id' => 'Sang Pencari', 'en' => 'The Seeker']),
                'deskripsi' => json_encode([
                    'id' => 'Kamu mendambakan pemahaman mendalam. Filosofi, tradisi, dan makna adalah kompasmu.',
                    'en' => 'You crave deep understanding. Philosophy, tradition, and meaning are your compass.',
                ]),
                'warna_hex' => '#4A7FA5',
                'ikon_url' => '/icons/soul/seeker.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'the_connector',
                'nama' => json_encode(['id' => 'Sang Penghubung', 'en' => 'The Connector']),
                'deskripsi' => json_encode([
                    'id' => 'Kamu hidup melalui hubungan dengan orang lain. Komunitas dan kehangatan adalah rumahmu.',
                    'en' => 'You live through connections with others. Community and warmth are your home.',
                ]),
                'warna_hex' => '#6BAF8E',
                'ikon_url' => '/icons/soul/connector.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'the_guardian',
                'nama' => json_encode(['id' => 'Sang Penjaga', 'en' => 'The Guardian']),
                'deskripsi' => json_encode([
                    'id' => 'Kamu menghormati akar dan tradisi. Penjaga warisan dan kearifan lokal adalah panggilanmu.',
                    'en' => 'You honor roots and traditions. Preserving heritage and local wisdom is your calling.',
                ]),
                'warna_hex' => '#8B6914',
                'ikon_url' => '/icons/soul/guardian.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'the_wanderer',
                'nama' => json_encode(['id' => 'Sang Penjelajah', 'en' => 'The Wanderer']),
                'deskripsi' => json_encode([
                    'id' => 'Kamu menemukan dirimu di jalan yang belum terpetakan. Kebebasan dan petualangan adalah nafasmu.',
                    'en' => 'You find yourself on uncharted paths. Freedom and adventure are your breath.',
                ]),
                'warna_hex' => '#7B5EA7',
                'ikon_url' => '/icons/soul/wanderer.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'the_dreamer',
                'nama' => json_encode(['id' => 'Sang Pemimpi', 'en' => 'The Dreamer']),
                'deskripsi' => json_encode([
                    'id' => 'Kamu melihat keindahan di mana orang lain tidak melihat. Imajinasi dan keajaiban hidup bersamamu.',
                    'en' => 'You see beauty where others cannot. Imagination and wonder live within you.',
                ]),
                'warna_hex' => '#C4789A',
                'ikon_url' => '/icons/soul/dreamer.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('soul_type')->insert($types);

        $this->command->info('✔ 6 Soul Types berhasil di-seed!');
    }
}