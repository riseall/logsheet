<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Admin Logsheet',
                'email' => 'admin@demo.test',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Supervisor Produksi',
                'email' => 'spv@demo.test',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
            ],
            [
                'name' => 'Manager Operasional',
                'email' => 'manager@demo.test',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ],
            [
                'name' => 'Teknisi Lapangan',
                'email' => 'teknisi@demo.test',
                'password' => Hash::make('password'),
                'role' => 'teknisi',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
