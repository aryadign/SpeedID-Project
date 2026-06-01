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
    }
}

