<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'koper']);

        $Admin = User::create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'password' => bcrypt('secret'),
        ]);

        $koper = User::create([
            'name' => '',
            'email' => '',
            'password' => bcrypt(''),
        ]);




        $Admin->assignRole('Admin');
        $koper->assignRole('Koper');
    }
}
