<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
        ['email' => 'test@example.com'], // criterio único
        [
            'name' => 'Test User',
            'password' => bcrypt('password'), // o Hash::make('password')
        ]
    );

        $this->call([
            CategorySeeder::class,
            CompaniaSeeder::class,
            ProductSeeder::class,
            ProveedorSeeder::class,
            EstadoSeeder::class,
        ]);
    }
}
