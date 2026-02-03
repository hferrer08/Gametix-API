<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            [
                'descripcion' => 'Juegos (Digital y Fisico)',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'descripcion' => 'Consolas y Hardware',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'descripcion' => 'Accesorios y Perifericos',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
