<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixCategorySeeder extends Seeder
{
    public function run(): void
    {
        $keepIds = DB::table('report_categories')
            ->selectRaw('MIN(id) as id')
            ->groupBy('name')
            ->pluck('id');

        $deleted = DB::table('report_categories')
            ->whereNotIn('id', $keepIds)
            ->delete();

        $this->command->info("Duplikat dihapus: {$deleted} baris");
        $this->command->info('Sisa kategori: ' . DB::table('report_categories')->count());
    }
}
