<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('koper');
        Role::findOrCreate('maker');
        Role::findOrCreate('moderator');
        Role::findOrCreate('admin'); // handig, optioneel
    }
}
