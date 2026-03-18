<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

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
        Role::create(['name' => 'maker']);

        $Admin = User::create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'password' => bcrypt('secret'),
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole(['koper', 'maker']);
        $Admin->assignRole('Admin');

    }
}
