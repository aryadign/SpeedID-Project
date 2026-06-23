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

            foreach ($servicesData as $serv) {
                $service = \App\Models\Service::create($serv);
                
                // Create slots for today and tomorrow
                foreach ([now()->toDateString(), now()->addDay()->toDateString()] as $date) {
                    \App\Models\ServiceSlot::create([
                        'service_id' => $service->id,
                        'date' => $date,
                        'start_time' => '08:00',
                        'end_time' => '10:00',
                        'quota' => 10,
                    ]);
                    \App\Models\ServiceSlot::create([
                        'service_id' => $service->id,
                        'date' => $date,
                        'start_time' => '10:00',
                        'end_time' => '12:00',
                        'quota' => 10,
                    ]);
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
    }
}

