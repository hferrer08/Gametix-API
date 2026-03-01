<?php

namespace Database\Seeders;

use App\Models\Resena;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResenaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Resena::create([
            'id_producto' => 1,
            'id_usuario' => 1,
            'puntuacion' => 5,
            'comentario' => 'Excelente producto, muy recomendable.',
            'fecha' => now(),
            'activo' => true,
        ]);

        Resena::create([
            'id_producto' => 2,
            'id_usuario' => 2,
            'puntuacion' => 4,
            'comentario' => 'Buen producto, pero el envío fue lento.',
            'fecha' => now(),
            'activo' => true,
        ]);

            Resena::create([
                'id_producto' => 3,
                'id_usuario' => 1,
                'puntuacion' => 2,
                'comentario' => 'El producto si cumplió mis expectativas.',
                'fecha' => now(),
                'activo' => true,
            ]);
    }
}
