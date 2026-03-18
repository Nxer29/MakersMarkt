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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'koper']);
        Role::firstOrCreate(['name' => 'maker']);

        $admin = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('secret'),
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('secret'),
            ]
        );

        $admin->assignRole('Admin');
        $user->assignRole(['koper', 'maker']);
    }
}
