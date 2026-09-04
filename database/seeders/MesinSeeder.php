<?php

namespace Database\Seeders;

use App\Models\Bangunan;
use App\Models\Kategori;
use App\Models\Mesin;
use Illuminate\Database\Seeder;

class MesinSeeder extends Seeder
{
    public function run()
    {
        $hvac = Kategori::where('code', 'HVAC')->first();
        $me = Kategori::where('code', 'ME')->first();
        $cu = Kategori::where('code', 'CU')->first();

        $gp1 = Bangunan::where('code', 'GP1')->first();
        $gup = Bangunan::where('code', 'GUP')->first();
        $gfl = Bangunan::where('code', 'GFL')->first();

        if (!$hvac || !$me || !$cu || !$gp1 || !$gup || !$gfl) {
            return;
        }

        $machines = [
            [
                'name' => 'Diesel Generator Mitsubishi 1',
                'code' => 'GEN-MITS-01',
                'category_id' => $me->id,
                'building_id' => $gup->id,
                'status_aktif' => true,
            ],
            [
                'name' => 'Diesel Generator Mitsubishi 2',
                'code' => 'GEN-MITS-02',
                'category_id' => $me->id,
                'building_id' => $gup->id,
                'status_aktif' => true,
            ],
            [
                'name' => 'Chiller Water Cooled 01',
                'code' => 'CHL-01',
                'category_id' => $hvac->id,
                'building_id' => $gp1->id,
                'status_aktif' => true,
            ],
            [
                'name' => 'AHU Cleanroom 01',
                'code' => 'AHU-CR-01',
                'category_id' => $hvac->id,
                'building_id' => $gfl->id,
                'status_aktif' => true,
            ],
            [
                'name' => 'Air Compressor Screw 01',
                'code' => 'COMP-01',
                'category_id' => $cu->id,
                'building_id' => $gup->id,
                'status_aktif' => true,
            ],
        ];

        foreach ($machines as $machineData) {
            Mesin::firstOrCreate(
                ['code' => $machineData['code']],
                $machineData
            );
        }
    }
}
