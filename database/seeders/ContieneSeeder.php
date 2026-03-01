<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContieneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'id_lista'    => 1,
                'id_producto' => 1,
                'fecha_agregado' => now(),
            ],
            [
                'id_lista'    => 1,
                'id_producto' => 2,
                'fecha_agregado' => now(),
            ],
            [
                'id_lista'    => 1,
                'id_producto' => 3,
                'fecha_agregado' => now(),
            ],
            [
                'id_lista'    => 2,
                'id_producto' => 3,
                'fecha_agregado' => now(),
            ],
            [
                'id_lista'    => 2,
                'id_producto' => 1,
                'fecha_agregado' => now(),
            ],
            [
                'id_lista'    => 2,
                'id_producto' => 5,
                'fecha_agregado' => now(),
            ],
        ];
            DB::table('contiene')->insert($data);
    }
}
