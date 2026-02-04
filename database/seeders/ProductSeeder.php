<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Category 1: Juegos
            [
                'name' => 'EA Sports FC 26 (PS5)',
                'description' => 'Juego de fútbol para PS5 (edición estándar).',
                'website' => 'https://www.ea.com',
                'category_id' => 1,
                'id_compania' => 1,
            ],
            [
                'name' => 'Call of Duty: Modern Warfare III',
                'description' => 'Shooter AAA; campaña + multijugador.',
                'website' => 'https://www.callofduty.com',
                'category_id' => 1,
                'id_compania' => 2,
            ],
            [
                'name' => 'Minecraft (Digital)',
                'description' => 'Sandbox creativo; ideal para jugar en familia.',
                'website' => 'https://www.minecraft.net',
                'category_id' => 1,
                'id_compania' => 3,
            ],

            // Category 2: Consolas y Hardware
            [
                'name' => 'PlayStation 5 Slim',
                'description' => 'Consola PS5 versión Slim.',
                'website' => 'https://www.playstation.com',
                'category_id' => 2,
                'id_compania' => 1,
            ],
            [
                'name' => 'Xbox Series X',
                'description' => 'Consola de alta gama de Xbox.',
                'website' => 'https://www.xbox.com',
                'category_id' => 2,
                'id_compania' => 2,
            ],
            [
                'name' => 'Nintendo Switch OLED',
                'description' => 'Consola híbrida con pantalla OLED.',
                'website' => 'https://www.nintendo.com',
                'category_id' => 2,
                'id_compania' => 3,
            ],

            // Category 3: Accesorios y Periféricos
            [
                'name' => 'Control DualSense (Blanco)',
                'description' => 'Control inalámbrico para PS5.',
                'website' => 'https://www.playstation.com',
                'category_id' => 3,
                'id_compania' => 1,
            ],
            [
                'name' => 'Headset Gamer (Estéreo)',
                'description' => 'Audífonos con micrófono para gaming.',
                'website' => 'https://www.xbox.com',
                'category_id' => 3,
                'id_compania' => 2,
            ],
            [
                'name' => 'Pro Controller (Switch)',
                'description' => 'Control Pro para Nintendo Switch.',
                'website' => 'https://www.nintendo.com',
                'category_id' => 3,
                'id_compania' => 3,
            ],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(
                ['name' => $p['name']], // no duplica por nombre
                $p
            );
        }
    }
}
