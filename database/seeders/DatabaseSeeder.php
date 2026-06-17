<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('valencia'),
            ]
        );

        // Crear usuario cliente de prueba
        User::updateOrCreate(
            ['email' => 'cliente@gmail.com'],
            [
                'name' => 'Cliente Prueba',
                'password' => Hash::make('cliente123'),
            ]
        );

        $this->call(ProductoSeeder::class);
    }
}