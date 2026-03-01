<?php

namespace Database\Seeders;

use App\Models\DetalleCarrito;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetalleCarritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DetalleCarrito::create([
            'id_carrito' => 1,
            'id_producto' => 1,
            'cantidad' => 2,
        ]);
            DetalleCarrito::create([
                'id_carrito' => 1,
                'id_producto' => 2,
                'cantidad' => 1,
            ]);

            DetalleCarrito::create([
                'id_carrito' => 2,
                'id_producto' => 3,
                'cantidad' => 3,
            ]);

    }
}
