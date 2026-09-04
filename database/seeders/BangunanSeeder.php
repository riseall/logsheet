<?php

namespace Database\Seeders;

use App\Models\Bangunan;
use Illuminate\Database\Seeder;

class BangunanSeeder extends Seeder
{
    public function run()
    {
        $buildings = [
            [
                'name' => 'Gedung Produksi 1',
                'code' => 'GP1',
                'location' => 'Zona Utama - Lantai 1',
            ],
            [
                'name' => 'Gedung Utility & Power',
                'code' => 'GUP',
                'location' => 'Area Utility Barat',
            ],
            [
                'name' => 'Gedung Farmasi & Lab',
                'code' => 'GFL',
                'location' => 'Zona Bersih - Lantai 2',
            ],
        ];

        foreach ($buildings as $building) {
            Bangunan::firstOrCreate(
                ['code' => $building['code']],
                $building
            );
        }
    }
}
