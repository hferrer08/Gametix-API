<?php

namespace Database\Seeders;

use App\Models\ListaDeseo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ListaDeseosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ListaDeseo::create([
            'id_usuario' => 1, // Asumiendo que el usuario con ID 1 existe
            'nombre' => 'Lista de Deseos de Admin',
            'descripcion' => 'Lista de deseos para el usuario admin',
            'activo' => true,
        ]);
            ListaDeseo::create([
                'id_usuario' => 2, // Asumiendo que el usuario con ID 2 existe
                'nombre' => 'Lista de Deseos de User',
                'descripcion' => 'Lista de deseos para el usuario user',
                'activo' => true,
            ]);

    }
}
