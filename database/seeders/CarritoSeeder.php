<?php

namespace Database\Seeders;

use App\Models\Carrito;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Carrito::create([
            'id_usuario' => 1,
            'fecha_creacion' => now(),
        ]);

        Carrito::create([
            'id_usuario' => 2,
            'fecha_creacion' => now(),
        ]);
    }
}
