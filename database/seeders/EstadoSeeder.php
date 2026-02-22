<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['descripcion' => 'Pendiente'],
            ['descripcion' => 'Pagado'],
            ['descripcion' => 'Preparando'],
            ['descripcion' => 'Enviado'],
            ['descripcion' => 'Entregado'],
            ['descripcion' => 'Cancelado'],
        ];

        foreach ($estados as $e) {
            DB::table('estados')->updateOrInsert(
                ['descripcion' => $e['descripcion']], // evita duplicados
                $e
            );
        }
    }
}
