<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $imagenes = [
            'EA Sports FC 26 (PS5)' => 'https://rimage.ripley.cl/home.ripley/Attachment/WOP/1/2000408286592/full_image-2000408286592',
            'Call of Duty: Modern Warfare III' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Call_of_Duty_MWIII.jpg',
            'Minecraft (Digital)' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Minecraft_Key-art.png',
            'PlayStation 5 Slim' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Playstation_5_mod%C3%A8le_slim_%28%C3%A9dition_standard_avec_lecteur_de_disque_amovible.png',
            'Xbox Series X' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Xbox_Series_%28X%29.jpg',
            'Nintendo Switch OLED' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Nintendo_Switch_-_OLED.JPG',
            'Control DualSense (Blanco)' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Playstation_Dualsense_controller.jpg',
            'Headset Gamer (Estéreo)' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Xbox-360-Headset-White.jpg',
            'Pro Controller (Switch)' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Nintendo-Switch-Pro-Controller-BR.jpg',
        ];

        foreach ($imagenes as $name => $url) {
            Product::where('name', $name)->update(['imagen_url' => $url]);
        }
    }
}