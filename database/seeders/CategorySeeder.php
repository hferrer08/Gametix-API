<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    { {
            $categories = [
                ['descripcion' => 'Juegos (Digital y Fisico)', 'activo' => true],
                ['descripcion' => 'Consolas y Hardware', 'activo' => true],
                ['descripcion' => 'Accesorios y Perifericos', 'activo' => true],
            ];

            foreach ($categories as $c) {
                Category::firstOrCreate(
                    ['descripcion' => $c['descripcion']], // criterio para no duplicar
                    ['activo' => $c['activo']]
                );
            }
        }
    }
}
