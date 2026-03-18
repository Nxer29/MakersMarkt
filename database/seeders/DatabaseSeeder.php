<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ vaste rollen (lowercase, zodat middleware role:admin werkt)
        Role::findOrCreate('admin');
        Role::findOrCreate('moderator');

        // ✅ admin user (updateOrCreate zodat je geen duplicates krijgt)
        $admin = User::updateOrCreate(
            ['email' => 'hallo123@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('secret123'), // kies zelf
                'email_verified_at' => now(),          // zodat 'verified' middleware niet blokkeert
            ]
        );

        // ✅ role toekennen (Spatie)
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
