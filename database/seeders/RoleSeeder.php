<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name'          => 'Super Admin',
            'guard_name'    => 'web',
        ]);

        Role::firstOrCreate([
            'name'          => 'Kasir',
            'guard_name'    => 'web',
        ]);

        Role::firstOrCreate([
            'name'          => 'Admin',
            'guard_name'    => 'web',
        ]);
    }
}
