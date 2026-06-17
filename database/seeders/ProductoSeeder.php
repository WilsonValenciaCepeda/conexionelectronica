<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Procesador Intel Core i7-13700K',
                'descripcion' => '16 núcleos, 24 hilos, frecuencia base 3.4 GHz, turbo 5.4 GHz',
                'precio' => 450.00,
                'stock' => 10,
                'categoria' => 'Procesadores',
            ],
            [
                'nombre' => 'RAM Corsair Vengeance 32GB DDR5',
                'descripcion' => 'Kit de 2 módulos de 16GB, velocidad 5600MHz, disipador de aluminio',
                'precio' => 150.00,
                'stock' => 15,
                'categoria' => 'Memorias RAM',
            ],
            [
                'nombre' => 'SSD Samsung 980 PRO 1TB NVMe',
                'descripcion' => 'PCIe 4.0, velocidades de lectura 7000 MB/s, escritura 5100 MB/s',
                'precio' => 120.00,
                'stock' => 20,
                'categoria' => 'Almacenamiento',
            ],
            [
                'nombre' => 'NVIDIA RTX 4070 12GB GDDR6',
                'descripcion' => 'Ray Tracing, DLSS 3, 12GB VRAM, 5888 núcleos CUDA',
                'precio' => 650.00,
                'stock' => 5,
                'categoria' => 'Tarjetas Gráficas',
            ],
            [
                'nombre' => 'Fuente de Poder 850W 80+ Gold',
                'descripcion' => 'Certificación 80+ Gold, modular, ventilador silencioso de 140mm',
                'precio' => 180.00,
                'stock' => 8,
                'categoria' => 'Fuentes de Poder',
            ],
            [
                'nombre' => 'Placa Base ASUS ROG Z790',
                'descripcion' => 'Socket LGA1700, soporte DDR5, PCIe 5.0, WiFi 6E, Bluetooth 5.3',
                'precio' => 320.00,
                'stock' => 7,
                'categoria' => 'Placas Base',
            ],
            [
                'nombre' => 'Mouse Gamer Logitech G Pro',
                'descripcion' => 'Inalámbrico, sensor HERO 25K, peso 80g, 5 botones programables',
                'precio' => 100.00,
                'stock' => 12,
                'categoria' => 'Periféricos',
            ],
            [
                'nombre' => 'Teclado Mecánico Corsair K70',
                'descripcion' => 'Switches Cherry MX, RGB personalizable, reposamuñecas magnético',
                'precio' => 140.00,
                'stock' => 10,
                'categoria' => 'Periféricos',
            ],
            [
                'nombre' => 'Disipador CPU Noctua NH-D15',
                'descripcion' => 'Doble torre, 2 ventiladores de 140mm, compatible con Intel y AMD',
                'precio' => 110.00,
                'stock' => 6,
                'categoria' => 'Refrigeración',
            ],
            [
                'nombre' => 'Monitor ASUS TUF 27" 165Hz',
                'descripcion' => 'IPS, 165Hz, 1ms, FreeSync Premium, 2560x1440',
                'precio' => 350.00,
                'stock' => 4,
                'categoria' => 'Periféricos',
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}