<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use App\Models\Subdistrict;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Detach foreign keys
        DB::table('institutions')->update(['subdistrict_id' => null]);
        DB::table('news_posts')->update(['subdistrict_id' => null]);
        DB::table('users')->update(['subdistrict_id' => null]);

        // 2. Delete old region data (reverse order due to FK)
        DB::table('subdistricts')->delete();
        DB::table('districts')->delete();
        DB::table('provinces')->delete();

        // 3. Reset auto-increment
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE provinces AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE districts AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE subdistricts AUTO_INCREMENT = 1');
        }

        // 4. Seed Bali province
        Province::create(['name' => 'Bali']);

        // 5. Seed districts
        $districtNames = ['Badung', 'Bangli', 'Buleleng', 'Gianyar', 'Jembrana', 'Karangasem', 'Klungkung', 'Kota Denpasar'];
        foreach ($districtNames as $name) {
            District::create(['name' => $name, 'province_id' => 1]);
        }

        // 6. Seed subdistricts
        $subdistrictMap = [
            ['name' => 'Kuta', 'district' => 'Badung'],
            ['name' => 'Mengwi', 'district' => 'Badung'],
            ['name' => 'Abiansemal', 'district' => 'Badung'],
            ['name' => 'Kuta Selatan', 'district' => 'Badung'],
            ['name' => 'Bangli', 'district' => 'Bangli'],
            ['name' => 'Kintamani', 'district' => 'Bangli'],
            ['name' => 'Buleleng', 'district' => 'Buleleng'],
            ['name' => 'Seririt', 'district' => 'Buleleng'],
            ['name' => 'Sukasada', 'district' => 'Buleleng'],
            ['name' => 'Gianyar', 'district' => 'Gianyar'],
            ['name' => 'Ubud', 'district' => 'Gianyar'],
            ['name' => 'Sukawati', 'district' => 'Gianyar'],
            ['name' => 'Negara', 'district' => 'Jembrana'],
            ['name' => 'Melaya', 'district' => 'Jembrana'],
            ['name' => 'Karangasem', 'district' => 'Karangasem'],
            ['name' => 'Abang', 'district' => 'Karangasem'],
            ['name' => 'Manggis', 'district' => 'Karangasem'],
            ['name' => 'Klungkung', 'district' => 'Klungkung'],
            ['name' => 'Nusa Penida', 'district' => 'Klungkung'],
            ['name' => 'Denpasar Selatan', 'district' => 'Kota Denpasar'],
            ['name' => 'Denpasar Barat', 'district' => 'Kota Denpasar'],
            ['name' => 'Denpasar Utara', 'district' => 'Kota Denpasar'],
        ];

        $districts = District::pluck('id', 'name');
        $sub = [];
        foreach ($subdistrictMap as $item) {
            $sd = Subdistrict::create([
                'name' => $item['name'],
                'district_id' => $districts[$item['district']],
            ]);
            $sub[$item['name']] = $sd->id;
        }

        // 7. Update institutions
        DB::table('institutions')->where('name', 'RSUD Tarakan')->update([
            'name' => 'RSUD Mangusada',
            'description' => 'Rumah Sakit Umum Daerah Badung',
            'address' => 'Mengwi, Badung',
            'subdistrict_id' => $sub['Mengwi'],
        ]);

        DB::table('institutions')->where('name', 'Puskesmas Kecamatan Gambir')->update([
            'name' => 'Puskesmas Kuta',
            'description' => 'Puskesmas Kecamatan Kuta',
            'address' => 'Kuta, Badung',
            'subdistrict_id' => $sub['Kuta'],
        ]);

        DB::table('institutions')->where('name', 'Dukcapil Jakarta Pusat')->update([
            'name' => 'Dukcapil Badung',
            'description' => 'Dinas Kependudukan dan Pencatatan Sipil Kabupaten Badung',
            'address' => 'Mengwi, Badung',
            'subdistrict_id' => $sub['Mengwi'],
        ]);

        DB::table('institutions')->where('name', 'Samsat Jakarta Selatan')->update([
            'name' => 'Samsat Denpasar',
            'description' => 'Sistem Administrasi Manunggal Satu Atap Kota Denpasar',
            'address' => 'Denpasar Selatan',
            'subdistrict_id' => $sub['Denpasar Selatan'],
        ]);

        // 8. Update news posts
        $news1 = DB::table('news_posts')->where('title', 'like', '%RSUD Tarakan%')->first();
        if ($news1) {
            DB::table('news_posts')->where('id', $news1->id)->update([
                'title' => 'RSUD Mangusada Tingkatkan Layanan Antrean Online dengan SpeedQ',
                'slug' => 'rsud-mangusada-tingkatkan-layanan-antrean-online-dengan-speedq',
                'content' => 'Dalam rangka meningkatkan kenyamanan masyarakat, RSUD Mangusada di Mengwi, Badung kini resmi terintegrasi dengan SpeedQ untuk pendaftaran antrean online. Pasien kini dapat mengambil nomor antrean dari rumah melalui aplikasi SpeedID dan memantau status antrean secara real-time. Layanan ini mencakup Poli Umum, Poli Gigi, dan Poli KIA dengan estimasi waktu tunggu yang lebih presisi.',
                'subdistrict_id' => $sub['Mengwi'],
            ]);
        }

        $news2 = DB::table('news_posts')->where('title', 'like', '%Pemeliharaan Sistem Pembayaran%')->first();
        if ($news2) {
            DB::table('news_posts')->where('id', $news2->id)->update([
                'title' => 'Pemeliharaan Sistem Pembayaran Online Kota Denpasar',
                'slug' => 'pemeliharaan-sistem-pembayaran-online-kota-denpasar',
                'content' => 'Diberitahukan kepada seluruh warga Denpasar, akan dilakukan pemeliharaan sistem pembayaran online pada hari Sabtu, 27 Juni 2026 mulai pukul 23.00 WITA hingga Minggu, 28 Juni 2026 pukul 05.00 WITA. Selama masa pemeliharaan, transaksi pembayaran pajak daerah dan retribusi melalui portal resmi akan dinonaktifkan sementara.',
                'subdistrict_id' => $sub['Denpasar Selatan'],
            ]);
        }

        $news3 = DB::table('news_posts')->where('title', 'like', '%Festival%Kebayoran%')->first();
        if ($news3) {
            DB::table('news_posts')->where('id', $news3->id)->update([
                'title' => 'Festival Seni dan Budaya Ubud Akhir Pekan Ini',
                'slug' => 'festival-seni-dan-budaya-ubud-akhir-pekan-ini',
                'content' => 'Kecamatan Ubud, Gianyar akan menyelenggarakan Festival Seni dan Budaya pada 27-28 Juni 2026 di kawasan pusat Ubud. Acara ini akan menampilkan lebih dari 50 seniman lokal dengan pertunjukan tari tradisional, pameran lukisan, ukiran kayu, serta bazar kerajinan khas Bali. Dapatkan tiket gratis masuk melalui pendaftaran di aplikasi SpeedID.',
                'subdistrict_id' => $sub['Ubud'],
            ]);
        }

        $news4 = DB::table('news_posts')->where('title', 'like', '%Peringatan Dini Cuaca%')->first();
        if ($news4) {
            DB::table('news_posts')->where('id', $news4->id)->update([
                'title' => 'Peringatan Dini Cuaca Ekstrem: Hujan Lebat di Wilayah Bali Selatan',
                'slug' => 'peringatan-dini-cuaca-ekstrem-hujan-lebat-bali-selatan',
                'content' => 'Badan Meteorologi, Klimatologi, dan Geofisika (BMKG) mengeluarkan peringatan dini potensi hujan lebat disertai kilat dan angin kencang di wilayah Bali Selatan meliputi Badung, Denpasar, dan Gianyar untuk tiga hari ke depan. Masyarakat diimbau untuk selalu waspada, menjauhi pohon besar saat terjadi angin kencang, dan memantau saluran drainase di lingkungan masing-masing.',
                'subdistrict_id' => $sub['Kuta Selatan'],
            ]);
        }

        $news5 = DB::table('news_posts')->where('title', 'like', '%Penutupan%Jalur%')->first();
        if ($news5) {
            DB::table('news_posts')->where('id', $news5->id)->update([
                'title' => 'Penutupan Sementara Jalur Wisata Kuta akibat Genangan Air',
                'slug' => 'penutupan-sementara-jalur-wisata-kuta-akibat-genangan-air',
                'content' => 'Petugas gabungan melakukan penutupan sementara di beberapa ruas jalan di kawasan wisata Kuta menyusul adanya genangan air akibat curah hujan tinggi sejak dini hari. Jalur dialihkan melalui jalan alternatif. Tim Dinas Pekerjaan Umum sedang berupaya melakukan penyedotan air agar lalu lintas segera normal kembali.',
                'subdistrict_id' => $sub['Kuta'],
            ]);
        }

        $this->command->info('Wilayah berhasil diganti ke data Bali!');
    }
}
