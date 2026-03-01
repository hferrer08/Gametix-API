<?php

namespace Database\Seeders;

use App\Models\MovimientoStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovimientoStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        MovimientoStock::create([
            'id_producto' => 1,
            'tipo_movimiento' => 'entrada',
            'cantidad' => 100,
            'fecha' => now(),
            'id_usuario' => 1,
        ]);

        MovimientoStock::create([
            'id_producto' => 2,
            'tipo_movimiento' => 'entrada',
            'cantidad' => 100,
            'fecha' => now(),
            'id_usuario' => 1,
        ]);

        MovimientoStock::create([
            'id_producto' => 3,
            'tipo_movimiento' => 'entrada',
            'cantidad' => 100,
            'fecha' => now(),
            'id_usuario' => 1,
        ]);

            MovimientoStock::create([
                'id_producto' => 1,
                'tipo_movimiento' => 'salida',
                'cantidad' => 10,
                'fecha' => now(),
                'id_usuario' => 1,
            ]);

    }
}
