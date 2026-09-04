<?php

namespace Database\Seeders;

use App\Models\FormParameter;
use App\Models\FormTemplate;
use App\Models\Mesin;
use App\Models\User;
use Illuminate\Database\Seeder;

class FormTemplateSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            return;
        }

        // 1. Template for Diesel Generator Mitsubishi 1
        $gen1 = Mesin::where('code', 'GEN-MITS-01')->first();
        if ($gen1) {
            $template = FormTemplate::firstOrCreate(
                ['machine_id' => $gen1->id, 'version' => 1],
                ['created_by' => $admin->id]
            );

            $genParams = [
                ['name' => 'Suhu Ruangan Mesin', 'requirement' => '< 35 °C', 'sort_order' => 1],
                ['name' => 'Level Oli Mesin', 'requirement' => 'Antara Min & Max', 'sort_order' => 2],
                ['name' => 'Tekanan Oli', 'requirement' => '3.0 - 5.0 Bar', 'sort_order' => 3],
                ['name' => 'Level Air Radiator', 'requirement' => 'Penuh / Normal', 'sort_order' => 4],
                ['name' => 'Kondisi & Ketegangan V-Belt', 'requirement' => 'Kencang, tidak retak', 'sort_order' => 5],
                ['name' => 'Tegangan Accu Starter', 'requirement' => '> 24 Volt', 'sort_order' => 6],
                ['name' => 'Suhu Air Pendingin (Coolant)', 'requirement' => '< 85 °C', 'sort_order' => 7],
                ['name' => 'Hour Meter / Jam Kerja', 'requirement' => 'Catat kumulatif jam', 'sort_order' => 8],
            ];

            foreach ($genParams as $param) {
                FormParameter::firstOrCreate(
                    ['form_template_id' => $template->id, 'name' => $param['name']],
                    $param
                );
            }
        }

        // 2. Template for Chiller 01
        $chiller = Mesin::where('code', 'CHL-01')->first();
        if ($chiller) {
            $template = FormTemplate::firstOrCreate(
                ['machine_id' => $chiller->id, 'version' => 1],
                ['created_by' => $admin->id]
            );

            $chillerParams = [
                ['name' => 'Suhu Chilled Water In', 'requirement' => '10 - 14 °C', 'sort_order' => 1],
                ['name' => 'Suhu Chilled Water Out', 'requirement' => '6 - 8 °C', 'sort_order' => 2],
                ['name' => 'Tekanan Suction Refrigerant', 'requirement' => '3.5 - 4.5 Bar', 'sort_order' => 3],
                ['name' => 'Tekanan Discharge Refrigerant', 'requirement' => '13.0 - 16.0 Bar', 'sort_order' => 4],
                ['name' => 'Arus Kompresor (Ampere)', 'requirement' => '< 120 Ampere', 'sort_order' => 5],
            ];

            foreach ($chillerParams as $param) {
                FormParameter::firstOrCreate(
                    ['form_template_id' => $template->id, 'name' => $param['name']],
                    $param
                );
            }
        }

        // 3. Template for Compressor 01
        $comp = Mesin::where('code', 'COMP-01')->first();
        if ($comp) {
            $template = FormTemplate::firstOrCreate(
                ['machine_id' => $comp->id, 'version' => 1],
                ['created_by' => $admin->id]
            );

            $compParams = [
                ['name' => 'Tekanan Angin Keluar', 'requirement' => '7.0 - 8.5 Bar', 'sort_order' => 1],
                ['name' => 'Suhu Udara Kompresi', 'requirement' => '< 95 °C', 'sort_order' => 2],
                ['name' => 'Level Oli Kompresor', 'requirement' => 'Normal di Sight Glass', 'sort_order' => 3],
                ['name' => 'Indikator Differential Filter', 'requirement' => 'Zona Hijau', 'sort_order' => 4],
            ];

            foreach ($compParams as $param) {
                FormParameter::firstOrCreate(
                    ['form_template_id' => $template->id, 'name' => $param['name']],
                    $param
                );
            }
        }
    }
}
