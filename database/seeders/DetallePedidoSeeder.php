<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetallePedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            ['id_pedido' => 1, 'id_producto' => 1, 'cantidad' => 2, 'precio_unitario' => 50.00],
            ['id_pedido' => 1, 'id_producto' => 2, 'cantidad' => 1, 'precio_unitario' => 50.00],
            ['id_pedido' => 2, 'id_producto' => 3, 'cantidad' => 5, 'precio_unitario' => 100.00],
            ['id_pedido' => 2, 'id_producto' => 4, 'cantidad' => 4, 'precio_unitario' => 100.00],
            ['id_pedido' => 3, 'id_producto' => 5, 'cantidad' => 4, 'precio_unitario' => 100.00],
        ];

        DB::table('detalle_pedido')->insert($data);
    }
}
