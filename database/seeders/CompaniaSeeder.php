<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Compania;

class CompaniaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $companias = [
            [
                'nombre' => 'Nintendo',
                'descripcion' => 'Empresa japonesa de videojuegos y entretenimiento.',
                'sitio_web' => 'https://www.nintendo.com',
                'activo' => true,
            ],
            [
                'nombre' => 'Sony Interactive Entertainment',
                'descripcion' => 'División de Sony enfocada en PlayStation y servicios de gaming.',
                'sitio_web' => 'https://www.playstation.com',
                'activo' => true,
            ],
            [
                'nombre' => 'Microsoft Gaming',
                'descripcion' => 'División de Microsoft (Xbox, estudios y servicios).',
                'sitio_web' => 'https://www.xbox.com',
                'activo' => true,
            ],
            [
                'nombre' => 'Valve',
                'descripcion' => 'Desarrolladora y plataforma Steam.',
                'sitio_web' => 'https://www.valvesoftware.com',
                'activo' => true,
            ],
            [
                'nombre' => 'Electronic Arts',
                'descripcion' => 'Publisher de videojuegos (EA).',
                'sitio_web' => 'https://www.ea.com',
                'activo' => true,
            ],
            [
                'nombre' => 'Ubisoft',
                'descripcion' => 'Publisher y desarrolladora de videojuegos.',
                'sitio_web' => 'https://www.ubisoft.com',
                'activo' => true,
            ],
        ];

        foreach ($companias as $c) {
            Compania::firstOrCreate(
                ['nombre' => $c['nombre']],  // criterio único (evita duplicados)
                [
                    'descripcion' => $c['descripcion'],
                    'sitio_web' => $c['sitio_web'],
                    'activo' => $c['activo'],
                ]
            );
        }
    }
}
