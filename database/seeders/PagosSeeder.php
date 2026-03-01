<?php

namespace Database\Seeders;

use App\Models\Pago;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PagosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Pago::create([
            'id_pedido' => 1,
            'metodo_pago' => 'tarjeta',
            'monto' => 100.00,
            'fecha_pago' => now(),
            'estado_pago' => 'pagado',
        ]);

        Pago::create([
            'id_pedido' => 2,
            'metodo_pago' => 'paypal',
            'monto' => 50.00,
            'fecha_pago' => now(),
            'estado_pago' => 'pendiente',
        ]);
    }
}
