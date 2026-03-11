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
        $this->call(RolePermissionSeeder::class);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Geef een standaard rol, bv koper
        $user->syncRoles(['koper']);

        $koper = User::factory()->create(['name' => 'Koper', 'email' => 'koper@example.com']);
        $koper->syncRoles(['koper']);

        $maker = User::factory()->create(['name' => 'Maker', 'email' => 'maker@example.com']);
        $maker->syncRoles(['maker']);

        $mod = User::factory()->create(['name' => 'Moderator', 'email' => 'moderator@example.com']);
        $mod->syncRoles(['moderator']);
    }
}
