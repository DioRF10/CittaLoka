<?php

namespace Database\Seeders;

use App\Models\ExperienceAvailability;
use App\Models\ExperiencePhoto;
use App\Models\Experience;
use App\Models\Host;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Kategori ───────────────────────────────────────────────────
        $categories = [
            ['nama' => ['id' => 'Kerajinan', 'en' => 'Craft'], 'slug' => 'craft'],
            ['nama' => ['id' => 'Memasak', 'en' => 'Cooking'], 'slug' => 'cooking'],
            ['nama' => ['id' => 'Pertanian', 'en' => 'Farming'], 'slug' => 'farming'],
            ['nama' => ['id' => 'Tari', 'en' => 'Dance'], 'slug' => 'dance'],
            ['nama' => ['id' => 'Musik', 'en' => 'Music'], 'slug' => 'music'],
            ['nama' => ['id' => 'Spiritual', 'en' => 'Spiritual'], 'slug' => 'spiritual'],
        ];

        foreach ($categories as $cat) {
            Kategori::firstOrCreate(
                ['slug' => $cat['slug']],
                ['nama' => $cat['nama']]
            );
        }

        // ── 2. Host dummy ─────────────────────────────────────────────────
        $hostUser = User::firstOrCreate(
            ['email' => 'wayan@cittaloka.test'],
            [
                'name' => 'I Wayan Sudarma',
                'username' => 'pak_wayan',
                'password' => Hash::make('password'),
                'role' => 'host',
                'locale' => 'id',
                'email_verified_at' => now(),
                'onboarding_completed_at' => now(),
            ]
        );

        $host = Host::firstOrCreate(
            ['user_id' => $hostUser->id],
            [
                'bio' => 'Saya adalah pengrajin perak generasi ketiga yang telah belajar sejak usia 12 tahun dari ayah saya di Desa Celuk, Gianyar.',
                'village' => 'Desa Celuk',
                'ktp_status' => 'verified',
                'is_verified' => true,
                'is_active' => true,
                'bank_name' => 'BCA',
                'bank_account_name' => 'I Wayan Sudarma',
                'bank_account_number' => '1234567890',
            ]
        );

        $hostUser2 = User::firstOrCreate(
            ['email' => 'made@cittaloka.test'],
            [
                'name' => 'Ni Made Ayu Lestari',
                'username' => 'bu_made',
                'password' => Hash::make('password'),
                'role' => 'host',
                'locale' => 'id',
                'email_verified_at' => now(),
                'onboarding_completed_at' => now(),
            ]
        );

        $host2 = Host::firstOrCreate(
            ['user_id' => $hostUser2->id],
            [
                'bio' => 'Saya adalah juru masak tradisional Bali yang mewarisi resep leluhur dari ibu dan nenek saya.',
                'village' => 'Desa Ubud',
                'ktp_status' => 'verified',
                'is_verified' => true,
                'is_active' => true,
                'bank_name' => 'BRI',
                'bank_account_name' => 'Ni Made Ayu Lestari',
                'bank_account_number' => '0987654321',
            ]
        );

        // ── 3. Experience ─────────────────────────────────────────────────
        $craftCat = Kategori::where('slug', 'craft')->first();
        $cookingCat = Kategori::where('slug', 'cooking')->first();
        $spiritualCat = Kategori::where('slug', 'spiritual')->first();
        $danceCat = Kategori::where('slug', 'dance')->first();

        $experiences = [
            [
                'host_id' => $host->id,
                'category_id' => $craftCat->id,
                'slug' => 'silver-craft-celuk-bali',
                'judul' => ['id' => 'Belajar Membuat Perhiasan Perak di Desa Celuk', 'en' => 'Learn Silver Jewelry Making in Celuk Village'],
                'deskripsi' => ['id' => 'Pelajari seni membuat perhiasan perak tradisional Bali langsung dari pengrajin generasi ketiga di Desa Celuk, pusat kerajinan perak Bali. Kamu akan belajar teknik dasar membuat cincin atau gelang perak dari awal hingga jadi, dan membawa pulang hasil karyamu sendiri.', 'en' => 'Learn the art of traditional Balinese silver jewelry making directly from a third-generation craftsman in Celuk Village. You will learn basic techniques of making a silver ring or bracelet from scratch and take home your own creation.'],
                'what_you_do' => [
                    ['icon' => '☕', 'title' => 'Introduction & Coffee', 'desc' => 'Welcome ceremony and discussion of Balinese silver history over local coffee and traditional snacks.'],
                    ['icon' => '✏️', 'title' => 'Design Selection', 'desc' => 'Choose your design pattern — traditional Balinese motifs or modern abstract shapes.'],
                    ['icon' => '🔨', 'title' => 'Guided Crafting', 'desc' => 'Hands-on instruction using traditional silversmithing tools. Learn hammering, shaping, and detailing techniques.'],
                    ['icon' => '✨', 'title' => 'Finishing & Polish', 'desc' => 'Sand and polish your creation to take home as a unique souvenir of your time in Bali.'],
                ],
                'included' => ['All silversmithing tools and materials', 'A piece of high-quality silver', 'Traditional Balinese coffee and snacks', 'Your finished jewelry to take home'],
                'not_included' => ['Transportation to/from the workshop', 'Full lunch (snacks only provided)', 'Personal gratuities for the artisan'],
                'harga' => 450000,
                'durasi_menit' => 180,
                'kapasitas_min' => 1,
                'kapasitas_max' => 4,
                'lokasi_lat' => -8.5567,
                'lokasi_lng' => 115.2894,
                'lokasi_nama' => 'Desa Celuk, Gianyar',
                'alamat_lengkap' => 'Jl. Raya Celuk No. 12, Desa Celuk, Kecamatan Sukawati, Gianyar, Bali',
                'meeting_point' => 'Depan workshop perak Pak Wayan — cari papan kayu bertuliskan "Sudarma Silver"',
                'kabupaten' => 'Gianyar',
                'bahasa' => ['id', 'en'],
                'dress_code' => ['id' => 'Pakaian santai yang tidak keberatan kotor sedikit', 'en' => 'Casual clothes that you do not mind getting slightly dirty'],
                'is_indoor' => true,
                'is_featured' => true,
                'status' => 'active',
                'rating_avg' => 4.9,
                'total_reviews' => 24,
            ],
            [
                'host_id' => $host2->id,
                'category_id' => $cookingCat->id,
                'slug' => 'balinese-cooking-class-ubud',
                'judul' => ['id' => 'Kelas Memasak Masakan Tradisional Bali di Ubud', 'en' => 'Traditional Balinese Cooking Class in Ubud'],
                'deskripsi' => ['id' => 'Belajar memasak hidangan tradisional Bali yang autentik bersama Bu Made di dapur tradisional keluarganya di Ubud. Mulai dari ke pasar pagi, memilih bahan segar, hingga memasak Nasi Campur Bali, Sate Lilit, dan Lawar.', 'en' => 'Learn to cook authentic traditional Balinese dishes with Bu Made in her family traditional kitchen in Ubud. From the morning market to cooking Nasi Campur Bali, Sate Lilit, and Lawar.'],
                'what_you_do' => [
                    ['icon' => '🛒', 'title' => 'Morning Market Visit', 'desc' => 'Visit the local Ubud market to select fresh ingredients and spices with Bu Made.'],
                    ['icon' => '🌿', 'title' => 'Spice Preparation', 'desc' => 'Learn to prepare the base spice mix (base genep) that is the foundation of Balinese cuisine.'],
                    ['icon' => '🍳', 'title' => 'Cooking Session', 'desc' => 'Cook 3-4 traditional Balinese dishes including Nasi Campur, Sate Lilit, and Lawar.'],
                    ['icon' => '🍽', 'title' => 'Enjoy Your Meal', 'desc' => 'Sit down and enjoy the delicious meal you just cooked together.'],
                ],
                'included' => ['Market tour with Bu Made', 'All ingredients and cooking equipment', 'Recipe booklet to take home', 'Full meal of what you cook'],
                'not_included' => ['Transportation to/from Ubud market', 'Alcoholic beverages', 'Personal gratuities'],
                'harga' => 380000,
                'durasi_menit' => 240,
                'kapasitas_min' => 2,
                'kapasitas_max' => 6,
                'lokasi_lat' => -8.5069,
                'lokasi_lng' => 115.2625,
                'lokasi_nama' => 'Ubud, Gianyar',
                'alamat_lengkap' => 'Jl. Monkey Forest No. 7, Ubud, Gianyar, Bali',
                'meeting_point' => 'Pasar Ubud — pintu masuk utama, Bu Made akan memakai kebaya hijau',
                'kabupaten' => 'Gianyar',
                'bahasa' => ['id', 'en'],
                'dress_code' => ['id' => 'Pakaian santai, bawa celemek jika ada', 'en' => 'Casual clothes, bring an apron if you have one'],
                'is_indoor' => true,
                'is_featured' => true,
                'status' => 'active',
                'rating_avg' => 4.8,
                'total_reviews' => 31,
            ],
            [
                'host_id' => $host->id,
                'category_id' => $spiritualCat->id,
                'slug' => 'upacara-pagi-pura-tirta-empul',
                'judul' => ['id' => 'Ikuti Ritual Pagi & Melukat di Tirta Empul', 'en' => 'Join Morning Ritual & Melukat at Tirta Empul'],
                'deskripsi' => ['id' => 'Alami pengalaman spiritual yang mendalam dengan ikut serta dalam ritual pagi hari dan melukat (penyucian diri) di Pura Tirta Empul bersama pemandu lokal yang akan menjelaskan makna setiap prosesi.', 'en' => 'Experience a profound spiritual journey by participating in the morning ritual and melukat (self-purification) at Tirta Empul Temple with a local guide.'],
                'what_you_do' => [
                    ['icon' => '🌅', 'title' => 'Morning Arrival', 'desc' => 'Arrive early to witness the temple coming alive before the tourist crowds arrive.'],
                    ['icon' => '🙏', 'title' => 'Canang Offering', 'desc' => 'Learn to make and present a traditional canang sari offering with guidance from your host.'],
                    ['icon' => '💧', 'title' => 'Melukat Ceremony', 'desc' => 'Participate in the sacred melukat purification ritual in the holy spring waters.'],
                    ['icon' => '📖', 'title' => 'Cultural Discussion', 'desc' => 'Sit with your guide to discuss the spiritual significance of what you just experienced.'],
                ],
                'included' => ['Sarong and sash rental', 'Canang offering materials', 'Guided explanation of all ceremonies', 'Holy water blessing'],
                'not_included' => ['Transportation to Tirta Empul', 'Temple entrance fee (Rp 50.000)', 'Personal offerings beyond what is provided'],
                'harga' => 300000,
                'durasi_menit' => 150,
                'kapasitas_min' => 1,
                'kapasitas_max' => 8,
                'lokasi_lat' => -8.4153,
                'lokasi_lng' => 115.3122,
                'lokasi_nama' => 'Pura Tirta Empul, Tampaksiring',
                'alamat_lengkap' => 'Jl. Tirta, Manukaya, Tampaksiring, Gianyar, Bali',
                'meeting_point' => 'Pintu masuk utama Pura Tirta Empul — datang pukul 06.30 WITA',
                'kabupaten' => 'Gianyar',
                'bahasa' => ['id', 'en'],
                'dress_code' => ['id' => 'Wajib memakai sarung dan selendang (tersedia di lokasi)', 'en' => 'Must wear sarong and sash (available on site)'],
                'is_indoor' => false,
                'is_featured' => false,
                'status' => 'active',
                'rating_avg' => 4.7,
                'total_reviews' => 18,
            ],
            [
                'host_id' => $host2->id,
                'category_id' => $danceCat->id,
                'slug' => 'belajar-tari-kecak-ubud',
                'judul' => ['id' => 'Belajar Dasar Tari Kecak & Tari Bali', 'en' => 'Learn Kecak Dance Basics & Balinese Dance'],
                'deskripsi' => ['id' => 'Pelajari gerakan dasar Tari Kecak dan Tari Bali tradisional bersama penari profesional di Ubud. Kamu akan belajar gerakan tangan (mudra), ekspresi wajah, dan koordinasi tubuh dalam suasana yang menyenangkan.', 'en' => 'Learn the basic movements of Kecak Dance and traditional Balinese dance with a professional dancer in Ubud.'],
                'what_you_do' => [
                    ['icon' => '🎭', 'title' => 'Introduction to Kecak', 'desc' => 'Learn the history and spiritual significance of Kecak dance in Balinese culture.'],
                    ['icon' => '🤲', 'title' => 'Hand Movements (Mudra)', 'desc' => 'Practice the intricate hand gestures (mudra) that tell stories in Balinese dance.'],
                    ['icon' => '👁', 'title' => 'Facial Expressions', 'desc' => 'Learn the expressive eye and face movements unique to Balinese dance tradition.'],
                    ['icon' => '💃', 'title' => 'Full Choreography', 'desc' => 'Put it all together in a short choreography you can perform for photos and videos.'],
                ],
                'included' => ['Dance costume for photo session', 'Professional instruction', 'Video recording of your performance', 'Traditional Balinese refreshments'],
                'not_included' => ['Transportation to/from the studio', 'Additional costume purchases', 'Personal gratuities'],
                'harga' => 320000,
                'durasi_menit' => 120,
                'kapasitas_min' => 1,
                'kapasitas_max' => 10,
                'lokasi_lat' => -8.5196,
                'lokasi_lng' => 115.2587,
                'lokasi_nama' => 'Studio Tari Ubud',
                'alamat_lengkap' => 'Jl. Raya Ubud No. 22, Ubud, Gianyar, Bali',
                'meeting_point' => 'Studio tari — cari bangunan dengan ornamen ukiran Bali di depannya',
                'kabupaten' => 'Gianyar',
                'bahasa' => ['id', 'en'],
                'dress_code' => ['id' => 'Pakaian yang nyaman untuk bergerak bebas', 'en' => 'Comfortable clothing for free movement'],
                'is_indoor' => true,
                'is_featured' => true,
                'status' => 'active',
                'rating_avg' => 4.6,
                'total_reviews' => 15,
            ],
        ];

        foreach ($experiences as $expData) {
            $exp = Experience::firstOrCreate(
                ['slug' => $expData['slug']],
                array_merge($expData, [
                    'judul' => json_encode($expData['judul']),
                    'deskripsi' => json_encode($expData['deskripsi']),
                    'what_you_do' => json_encode($expData['what_you_do']),
                    'included' => json_encode($expData['included']),
                    'not_included' => json_encode($expData['not_included']),
                    'bahasa' => json_encode($expData['bahasa']),
                    'dress_code' => json_encode($expData['dress_code']),
                ])
            );

            // Update kolom baru untuk data yang sudah ada
            $exp->update([
                'what_you_do' => json_encode($expData['what_you_do']),
                'included' => json_encode($expData['included']),
                'not_included' => json_encode($expData['not_included']),
            ]);

            // Foto
            if ($exp->photos()->count() === 0) {
                $photos = $this->getPhotos($expData['slug']);
                foreach ($photos as $i => $url) {
                    ExperiencePhoto::create([
                        'experience_id' => $exp->id,
                        'url' => $url,
                        'is_cover' => $i === 0,
                        'sort_order' => $i,
                    ]);
                }
            }

            // Availability 30 hari ke depan
            if ($exp->availabilities()->count() === 0) {
                for ($i = 1; $i <= 30; $i++) {
                    if (now()->addDays($i)->dayOfWeek === 0)
                        continue;
                    ExperienceAvailability::create([
                        'experience_id' => $exp->id,
                        'date' => now()->addDays($i)->toDateString(),
                        'max_slot' => $expData['kapasitas_max'],
                        'booked_slot' => rand(0, 1),
                        'is_blocked' => false,
                    ]);
                }
            }
        }

        $this->command->info('✅ Experience seeder selesai! ' . count($experiences) . ' experience dibuat.');
    }

    private function getPhotos(string $slug): array
    {
        $photos = [
            'silver-craft-celuk-bali' => [
                'https://images.unsplash.com/photo-1611735341450-74d61e660ad2?w=800',
                'https://images.unsplash.com/photo-1599054893838-7d4a43e63bdb?w=800',
                'https://images.unsplash.com/photo-1573408301185-9519f94815b5?w=800',
            ],
            'balinese-cooking-class-ubud' => [
                'https://images.unsplash.com/photo-1541614101331-1a5a3a194e92?w=800',
                'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=800',
                'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800',
            ],
            'upacara-pagi-pura-tirta-empul' => [
                'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800',
                'https://images.unsplash.com/photo-1604928141064-207cea6f571f?w=800',
                'https://images.unsplash.com/photo-1570789210967-2cac24afeb00?w=800',
            ],
            'belajar-tari-kecak-ubud' => [
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?w=800',
                'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?w=800',
            ],
        ];

        return $photos[$slug] ?? ['https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800'];
    }
}