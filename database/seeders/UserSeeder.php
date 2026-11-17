<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'role_id'      => 1,
                'name'      => 'Super Admin',
                'password'  => bcrypt('superadmin123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'role_id'      => 2,
                'name'      => 'Kasir',
                'password'  => bcrypt('kasir123'),
            ]
        );

        User::updateOrCreate(
            ['email'        => 'admin@gmail.com'],
            [
                'role_id'   => 3,
                'name'      => 'Admin',
                'password'  => bcrypt('admin123'),
            ]
        );

        User::where('email', 'superadmin@gmail.com')->first()->assignRole('Super Admin');
        User::where('email', 'admin@gmail.com')->first()->assignRole('Admin');
        User::where('email', 'kasir@gmail.com')->first()->assignRole('Kasir');
    }
}
