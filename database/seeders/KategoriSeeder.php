<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        Kategori::firstOrCreate(['code' => 'HVAC'], ['name' => 'HVAC']);
        Kategori::firstOrCreate(['code' => 'ME'], ['name' => 'Mechanical Electrical']);
        Kategori::firstOrCreate(['code' => 'CU'], ['name' => 'Compressed & Utility']);
    }
}
