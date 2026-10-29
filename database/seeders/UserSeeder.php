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
            ['email' => 'admin@gmail.com'],
            [
                'role_id'      => 1,
                'name'      => 'Admin',
                'password'  => bcrypt('admin123'),
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
            ['email'        => 'admgudang@gmail.com'],
            [
                'role_id'   => 3,
                'name'      => 'Admin Gudang',
                'password'  => bcrypt('admgudang'),
            ]
        );

        User::where('email', 'admin@gmail.com')->first()->assignRole('Admin');
        User::where('email', 'kasir@gmail.com')->first()->assignRole('Kasir');
        User::where('email', 'admgudang@gmail.com')->first()->assignRole('Admin Gudang');
    }
}
