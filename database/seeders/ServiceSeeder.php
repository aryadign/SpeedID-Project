<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceSlot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Hapus duplikat institusi (ID 5-9 tidak punya relasi karena services/slots/tickets kosong)
        $count = DB::table('institutions')->count();
        if ($count > 4) {
            DB::table('institutions')->whereIn('id', range(5, $count))->delete();
            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE institutions AUTO_INCREMENT = 5');
            }
        }

        // 2. Seed services
        $servicesData = [
            ['name' => 'Poli Umum', 'description' => 'Pemeriksaan kesehatan umum', 'duration' => 15, 'daily_quota' => 30],
            ['name' => 'Poli Gigi', 'description' => 'Pemeriksaan dan perawatan gigi', 'duration' => 20, 'daily_quota' => 20],
        ];

        $puskesmasId = DB::table('institutions')->where('name', 'Puskesmas Kuta')->value('id');
        $dukcapilId = DB::table('institutions')->where('name', 'Dukcapil Badung')->value('id');
        $samsatId = DB::table('institutions')->where('name', 'Samsat Denpasar')->value('id');

        if (!Service::exists()) {
            // RSUD Mangusada — Poli Umum, Poli Gigi
            $rsud = DB::table('institutions')->where('name', 'RSUD Mangusada')->first();
            if ($rsud) {
                foreach ($servicesData as $svc) {
                    Service::create(array_merge($svc, ['institution_id' => $rsud->id, 'is_active' => true]));
                }
            }

            // Puskesmas Kuta — Poli KIA
            if ($puskesmasId) {
                Service::create([
                    'institution_id' => $puskesmasId,
                    'name' => 'Poli KIA',
                    'description' => 'Kesehatan Ibu dan Anak',
                    'duration' => 15,
                    'daily_quota' => 30,
                    'is_active' => true,
                ]);
            }

            // Dukcapil Badung — Pembuatan KTP
            if ($dukcapilId) {
                Service::create([
                    'institution_id' => $dukcapilId,
                    'name' => 'Pembuatan KTP',
                    'description' => 'Perekaman dan pencetakan KTP-el',
                    'duration' => 10,
                    'daily_quota' => 50,
                    'is_active' => true,
                ]);
            }

            // Samsat Denpasar — Pembayaran Pajak Kendaraan
            if ($samsatId) {
                Service::create([
                    'institution_id' => $samsatId,
                    'name' => 'Pembayaran Pajak Kendaraan',
                    'description' => 'Pembayaran PKB tahunan',
                    'duration' => 10,
                    'daily_quota' => 50,
                    'is_active' => true,
                ]);
            }
        }

        // 3. Seed service slots for today and tomorrow
        $queueService = app(\App\Services\QueueService::class);
        foreach (Service::all() as $service) {
            foreach ([now()->toDateString(), now()->addDay()->toDateString()] as $date) {
                if (!$service->serviceSlots()->whereDate('date', $date)->exists()) {
                    $slotsData = $queueService->getDefaultSlotsForService($service, $date);
                    $service->serviceSlots()->createMany($slotsData);
                }
            }
        }

        $this->command->info('Services dan slots berhasil dibuat!');
    }
}
