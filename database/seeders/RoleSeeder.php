<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::withTrashed()->firstOrCreate(
            ['name' => 'admin'],
            ['label' => 'Administrador', 'activo' => true]
        );

        Role::withTrashed()->firstOrCreate(
            ['name' => 'user'],
            ['label' => 'Usuario', 'activo' => true]
        );
    }
}
