<?php

namespace Database\Seeders;

use App\Models\Pedido;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PedidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Pedido::create([
            'fecha' => now(),
            'monto_total' => 100.00,
            'id_estado' => 1,
            'id_usuario' => 1,
            'activo' => true,
        ]);

        Pedido::create([
            'fecha' => now(),
            'monto_total' => 500.00,
            'id_estado' => 2,
            'id_usuario' => 1,
            'activo' => true,
        ]);

        Pedido::create([
            'fecha' => now(),
            'monto_total' => 400.00,
            'id_estado' => 2,
            'id_usuario' => 2,
            'activo' => true,
        ]);
    }
}
