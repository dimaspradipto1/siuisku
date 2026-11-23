<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
 * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => 'superadmin@uis.ac.id',
                'roles' => 'super admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@uis.ac.id',
                'roles' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Dr. ANDI AULIYA RAMADHANY, SE., M.Akt',
                'username' => 'auliya',
                'email' => 'andi.auliya@uis.ac.id',
                'roles' => 'staff',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Dr. Muhammad Ropianto, S.Kom., M.Kom',
                'username' => 'ropianto',
                'email' => 'ropianto@uis.ac.id',
                'roles' => 'Ka.Lembaga Pengembangan Teknologi Informasi',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Dimas Pradipto',
                'username' => 'dimas',
                'email' => 'dimas@uis.ac.id',
                'roles' => 'staff',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
