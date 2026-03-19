<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'koper']);
        Role::create(['name' => 'maker']);

        $Admin = User::create([
            'name' => 'admin',
            'email' => 'test@example.com',
            'password' => bcrypt('secret'),
        ]);




        $Admin->assignRole('admin');

    }
}
