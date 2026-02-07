<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
   public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'Nintendo',
                'descripcion' => 'Proveedor oficial de consolas y videojuegos.',
                'sitio_web' => 'https://www.nintendo.com',
                'activo' => true
            ],
            [
                'nombre' => 'Sony Interactive Entertainment',
                'descripcion' => 'Distribución y publicaciones asociadas a PlayStation.',
                'sitio_web' => 'https://www.playstation.com',
                'activo' => true
            ],
            [
                'nombre' => 'Microsoft Gaming',
                'descripcion' => 'Xbox, Game Pass y ecosistema gaming de Microsoft.',
                'sitio_web' => 'https://www.xbox.com',
                'activo' => true
            ],
            [
                'nombre' => 'Valve',
                'descripcion' => 'Plataforma Steam y distribución digital.',
                'sitio_web' => 'https://store.steampowered.com',
                'activo' => true
            ],
            [
                'nombre' => 'Rockstar Games',
                'descripcion' => 'Publisher/desarrolladora (GTA, RDR, etc.).',
                'sitio_web' => 'https://www.rockstargames.com',
                'activo' => true
            ],
            [
                'nombre' => 'Proveedor Demo Inactivo',
                'descripcion' => 'Ejemplo para probar listado filtrado por activo.',
                'sitio_web' => null,
                'activo' => false
            ],
        ];

        foreach ($proveedores as $p) {
            Proveedor::firstOrCreate(
                ['nombre' => $p['nombre']], // criterio para no duplicar
                [
                    'descripcion' => $p['descripcion'],
                    'sitio_web'   => $p['sitio_web'],
                    'activo'      => $p['activo'],
                ]
            );
        }
    }
}
