<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        $admin = User::updateOrCreate(
            ['email' => 'admin@speedid.test'],
            ['name' => 'Admin SpeedID', 'phone_number' => '081234567890', 'password' => Hash::make('password')]
        );
        $admin->assignRole('Admin');

        $user = User::updateOrCreate(
            ['email' => 'user@speedid.test'],
            ['name' => 'User SpeedID', 'phone_number' => '081234567891', 'password' => Hash::make('password')]
        );
        $user->assignRole('User');

        if (!\App\Models\Province::exists()) {
            \App\Models\Province::insert([
                ['name' => 'DKI Jakarta'],
                ['name' => 'Jawa Barat'],
                ['name' => 'Jawa Timur'],
            ]);

            \App\Models\District::insert([
                ['name' => 'Jakarta Pusat', 'province_id' => 1],
                ['name' => 'Jakarta Selatan', 'province_id' => 1],
                ['name' => 'Bandung Kota', 'province_id' => 2],
                ['name' => 'Surabaya Kota', 'province_id' => 3],
            ]);

            \App\Models\Subdistrict::insert([
                ['name' => 'Gambir', 'district_id' => 1],
                ['name' => 'Menteng', 'district_id' => 1],
                ['name' => 'Kebayoran Baru', 'district_id' => 2],
                ['name' => 'Coblong', 'district_id' => 3],
                ['name' => 'Tegalsari', 'district_id' => 4],
            ]);

            \App\Models\Institution::insert([
                ['name' => 'RSUD Tarakan', 'description' => 'Rumah Sakit Umum Daerah', 'address' => 'Jakarta Pusat', 'subdistrict_id' => 1, 'is_active' => true],
                ['name' => 'Puskesmas Kecamatan Gambir', 'description' => 'Puskesmas Kecamatan', 'address' => 'Gambir, Jakarta Pusat', 'subdistrict_id' => 1, 'is_active' => true],
                ['name' => 'Dukcapil Jakarta Pusat', 'description' => 'Dinas Kependudukan dan Pencatatan Sipil', 'address' => 'Jakarta Pusat', 'subdistrict_id' => 1, 'is_active' => true],
                ['name' => 'Samsat Jakarta Selatan', 'description' => 'Sistem Administrasi Manunggal Satu Atap', 'address' => 'Kebayoran Baru', 'subdistrict_id' => 3, 'is_active' => true],
            ]);
        }

        if (!\App\Models\ReportCategory::exists()) {
            \App\Models\ReportCategory::insert([
                ['name' => 'Jalan Rusak'],
                ['name' => 'Sampah'],
                ['name' => 'Lampu Jalan'],
                ['name' => 'Banjir'],
                ['name' => 'Fasilitas Umum'],
                ['name' => 'Kriminalitas'],
                ['name' => 'Lainnya'],
            ]);
        }

        if (!\App\Models\NewsCategory::exists()) {
            \App\Models\NewsCategory::insert([
                ['name' => 'Berita', 'slug' => 'berita'],
                ['name' => 'Pengumuman', 'slug' => 'pengumuman'],
                ['name' => 'Event', 'slug' => 'event'],
                ['name' => 'Cuaca', 'slug' => 'cuaca'],
                ['name' => 'Darurat', 'slug' => 'darurat'],
            ]);
        }

        // Seed Services, Slots, and sample Queue Ticket
        $rsud = \App\Models\Institution::where('name', 'RSUD Tarakan')->first();
        if ($rsud && !\App\Models\Service::exists()) {
            $servicesData = [
                ['institution_id' => $rsud->id, 'name' => 'Poli Umum', 'description' => 'Pemeriksaan kesehatan umum', 'duration' => 15, 'daily_quota' => 30, 'is_active' => true],
                ['institution_id' => $rsud->id, 'name' => 'Poli Gigi', 'description' => 'Pemeriksaan dan perawatan gigi', 'duration' => 20, 'daily_quota' => 20, 'is_active' => true],
            ];
            
            $puskesmas = \App\Models\Institution::where('name', 'Puskesmas Kecamatan Gambir')->first();
            if ($puskesmas) {
                $servicesData[] = ['institution_id' => $puskesmas->id, 'name' => 'Poli KIA', 'description' => 'Kesehatan Ibu dan Anak', 'duration' => 15, 'daily_quota' => 30, 'is_active' => true];
            }

            $dukcapil = \App\Models\Institution::where('name', 'Dukcapil Jakarta Pusat')->first();
            if ($dukcapil) {
                $servicesData[] = ['institution_id' => $dukcapil->id, 'name' => 'Pembuatan KTP', 'description' => 'Perekaman dan pencetakan KTP-el', 'duration' => 10, 'daily_quota' => 50, 'is_active' => true];
            }

            $samsat = \App\Models\Institution::where('name', 'Samsat Jakarta Selatan')->first();
            if ($samsat) {
                $servicesData[] = ['institution_id' => $samsat->id, 'name' => 'Pembayaran Pajak Kendaraan', 'description' => 'Pembayaran PKB tahunan', 'duration' => 10, 'daily_quota' => 50, 'is_active' => true];
            }

            foreach ($servicesData as $serv) {
                \App\Models\Service::create($serv);
            }
        }

        // Always ensure slots for today and tomorrow exist for all services
        $queueService = app(\App\Services\QueueService::class);
        foreach (\App\Models\Service::all() as $service) {
            foreach ([now()->toDateString(), now()->addDay()->toDateString()] as $date) {
                if (!$service->serviceSlots()->whereDate('date', $date)->exists()) {
                    $slotsData = $queueService->getDefaultSlotsForService($service, $date);
                    $service->serviceSlots()->createMany($slotsData);
                }
            }
        }

        // Create an active queue ticket for user
        $user = \App\Models\User::where('email', 'user@speedid.test')->first();
        if ($user && !\App\Models\QueueTicket::where('user_id', $user->id)->exists()) {
            $slot = \App\Models\ServiceSlot::whereHas('service', function ($q) {
                $q->where('name', 'Poli Umum');
            })->whereDate('date', now()->toDateString())->first();
            
            if ($slot) {
                app(\App\Services\QueueService::class)->createBooking($user, $slot);
            }
        }

        // Seed news posts
        $admin = \App\Models\User::where('email', 'admin@speedid.test')->first();
        if ($admin && !\App\Models\NewsPost::exists()) {
            $newsData = [
                [
                    'user_id' => $admin->id,
                    'category_id' => 1, // Berita
                    'title' => 'RSUD Tarakan Tingkatkan Layanan Antrean Online dengan SpeedQ',
                    'slug' => 'rsud-tarakan-tingkatkan-layanan-antrean-online-dengan-speedq',
                    'content' => 'Dalam rangka meningkatkan kenyamanan masyarakat, RSUD Tarakan kini resmi terintegrasi dengan SpeedQ untuk pendaftaran antrean online. Pasien kini dapat mengambil nomor antrean dari rumah melalui aplikasi SpeedID dan memantau status antrean secara real-time. Layanan ini mencakup Poli Umum, Poli Gigi, dan Poli KIA dengan estimasi waktu tunggu yang lebih presisi.',
                    'subdistrict_id' => 1,
                    'is_emergency' => false,
                    'status' => 'published',
                    'published_at' => now(),
                ],
                [
                    'user_id' => $admin->id,
                    'category_id' => 2, // Pengumuman
                    'title' => 'Pemeliharaan Sistem Pembayaran Online Kota Jakarta',
                    'slug' => 'pemeliharaan-sistem-pembayaran-online-kota-jakarta',
                    'content' => 'Diberitahukan kepada seluruh warga Jakarta, akan dilakukan pemeliharaan sistem pembayaran online pada hari Sabtu, 27 Juni 2026 mulai pukul 23.00 WIB hingga Minggu, 28 Juni 2026 pukul 05.00 WIB. Selama masa pemeliharaan, transaksi pembayaran pajak daerah dan retribusi melalui portal resmi akan dinonaktifkan sementara.',
                    'subdistrict_id' => 1,
                    'is_emergency' => false,
                    'status' => 'published',
                    'published_at' => now(),
                ],
                [
                    'user_id' => $admin->id,
                    'category_id' => 3, // Event
                    'title' => 'Festival Kuliner Nusantara di Kebayoran Baru Akhir Pekan Ini',
                    'slug' => 'festival-kuliner-nusantara-di-kebayoran-baru-akhir-pekan-ini',
                    'content' => 'Kecamatan Kebayoran Baru akan menyelenggarakan Festival Kuliner Nusantara pada 27-28 Juni 2026 di kawasan Blok M. Acara ini akan menghadirkan lebih dari 50 tenant kuliner khas daerah dari seluruh Indonesia, pertunjukan seni musik tradisional, serta talkshow kebudayaan. Dapatkan tiket gratis masuk melalui pendaftaran di aplikasi SpeedID.',
                    'subdistrict_id' => 3,
                    'is_emergency' => false,
                    'status' => 'published',
                    'published_at' => now(),
                ],
                [
                    'user_id' => $admin->id,
                    'category_id' => 4, // Cuaca
                    'title' => 'Peringatan Dini Cuaca Ekstrem: Hujan Lebat Disertai Angin Kencang',
                    'slug' => 'peringatan-dini-cuaca-ekstrem-hujan-lebat-disertai-angin-kencang',
                    'content' => 'Badan Meteorologi, Klimatologi, dan Geofisika (BMKG) mengeluarkan peringatan dini potensi hujan lebat disertai kilat dan angin kencang di wilayah DKI Jakarta dan Jawa Barat untuk tiga hari ke depan. Masyarakat diimbau untuk selalu waspada, menjauhi pohon besar saat terjadi angin kencang, dan memantau saluran drainase di lingkungan masing-masing.',
                    'subdistrict_id' => 2,
                    'is_emergency' => false,
                    'status' => 'published',
                    'published_at' => now(),
                ],
                [
                    'user_id' => $admin->id,
                    'category_id' => 5, // Darurat
                    'title' => 'Penutupan Sementara Jalur Protokol akibat Genangan Air',
                    'slug' => 'penutupan-sementara-jalur-protokol-akibat-genangan-air',
                    'content' => 'Petugas gabungan melakukan penutupan sementara di beberapa ruas jalan protokol arah Jakarta Pusat menyusul adanya genangan air akibat curah hujan tinggi sejak dini hari. Jalur dialihkan melalui jalan alternatif. Tim oranye Dinas Sumber Daya Air sedang berupaya melakukan penyedotan air agar lalu lintas segera normal kembali.',
                    'subdistrict_id' => 1,
                    'is_emergency' => true,
                    'status' => 'published',
                    'published_at' => now(),
                ],
            ];

            foreach ($newsData as $data) {
                \App\Models\NewsPost::create($data);
            }
        }
    }
}

