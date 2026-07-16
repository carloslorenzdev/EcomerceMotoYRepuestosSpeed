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

        // Seed demo user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'cliente',
        ]);

        // Seed admin user
        User::factory()->create([
            'name' => 'Administrador MotosSpeed',
            'email' => 'admin@motosyrepuestosspeed.cl',
            'password' => bcrypt('admin12345'),
            'role' => 'admin',
        ]);

        // Seed Categories
        $categories = [
            'Repuestos Bajaj' => 'repuestos-bajaj',
            'Accesorios' => 'accesorios',
            'Lubricantes' => 'lubricantes',
            'Neumáticos' => 'neumaticos',
        ];

        $categoryModels = [];
        foreach ($categories as $name => $slug) {
            $categoryModels[$slug] = \App\Models\Category::create([
                'name' => $name,
                'slug' => $slug,
                'description' => "Catálogo de {$name} para motocicletas.",
            ]);
        }

        // Seed Products
        $products = [
            [
                'relbase_id' => 'rb_prod_1',
                'sku' => 'BAJ-001',
                'name' => 'Kit de Transmisión Pulsar NS 200 Original',
                'slug' => 'kit-de-transmision-pulsar-ns-200-original',
                'description' => 'Kit de transmisión original de Bajaj para la Pulsar NS 200. Incluye piñón, catalina y cadena reforzada.',
                'price' => 38990.00,
                'compare_at_price' => 45000.00,
                'stock' => 15,
                'image_url' => ['/bajaj-ns200.jpg'],
                'category_id' => $categoryModels['repuestos-bajaj']->id,
                'is_featured' => true,
            ],
            [
                'relbase_id' => 'rb_prod_2',
                'sku' => 'BAJ-002',
                'name' => 'Filtro de Aceite Original Bajaj Pulsar/Dominar',
                'slug' => 'filtro-de-aceite-original-bajaj-pulsar-dominar',
                'description' => 'Filtro de aceite oficial de Bajaj compatible con Pulsar NS 200, Dominar 400 y Discover 125.',
                'price' => 4500.00,
                'compare_at_price' => 6000.00,
                'stock' => 50,
                'image_url' => null,
                'category_id' => $categoryModels['repuestos-bajaj']->id,
                'is_featured' => true,
            ],
            [
                'relbase_id' => 'rb_prod_3',
                'sku' => 'MOT-7100',
                'name' => 'Aceite Motul 7100 10W40 4T 1L',
                'slug' => 'aceite-motul-7100-10w40-4t-1l',
                'description' => 'Lubricante 100% sintético de alto rendimiento para motores de 4 tiempos. Tecnología Ester.',
                'price' => 14990.00,
                'compare_at_price' => null,
                'stock' => 24,
                'image_url' => ['/products/motul-5100-15w50.jpg'],
                'category_id' => $categoryModels['lubricantes']->id,
                'is_featured' => true,
            ],
            [
                'relbase_id' => 'rb_prod_4',
                'sku' => 'MT-HELMET',
                'name' => 'Casco Integral MT Targo Pro Black',
                'slug' => 'casco-integral-mt-targo-pro-black',
                'description' => 'Casco integral homologado con certificación DOT y ECE. Excelente ventilación y calota aerodinámica.',
                'price' => 69990.00,
                'compare_at_price' => 79990.00,
                'stock' => 8,
                'image_url' => ['/products/casco-agv-k1-negro-mate-07cdea2d.webp'],
                'category_id' => $categoryModels['accesorios']->id,
                'is_featured' => false,
            ],
            [
                'relbase_id' => 'rb_prod_5',
                'sku' => 'PIR-DIABLO',
                'name' => 'Neumático Trasero 130/70-17 Pirelli Diablo Rosso III',
                'slug' => 'neumatico-trasero-130-70-17-pirelli-diablo-rosso-iii',
                'description' => 'Neumático deportivo de altas prestaciones para asfalto seco y mojado. Medida ideal para Pulsar NS 200.',
                'price' => 85000.00,
                'compare_at_price' => 95000.00,
                'stock' => 5,
                'image_url' => ['/bajaj-dominar.jpg'],
                'category_id' => $categoryModels['neumaticos']->id,
                'is_featured' => true,
            ],
        ];

        foreach ($products as $prod) {
            \App\Models\Product::create($prod);
        }

        // Seed Services
        $services = [
            [
                'name' => 'Mantención Preventiva por Pauta',
                'slug' => 'mantencion-preventiva-por-pauta',
                'description' => 'Servicio completo que incluye cambio de aceite, limpieza de filtros, regulación de frenos, lubricación de cadena y diagnóstico general de seguridad según pauta oficial.',
                'price' => 45000.00,
                'duration' => 90,
                'image_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Scanner y Diagnóstico Computarizado',
                'slug' => 'scanner-y-diagnostico-computarizado',
                'description' => 'Lectura y diagnóstico completo de códigos de error de la ECU, calibración de sensores de inyección electrónica con equipo especializado.',
                'price' => 25000.00,
                'duration' => 45,
                'image_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Rescate y Traslado en Camioneta',
                'slug' => 'rescate-y-traslado-en-camioneta',
                'description' => 'Servicio rápido de transporte de motos en panne dentro de la comuna de Rancagua y alrededores directo a nuestro taller.',
                'price' => null,
                'duration' => null,
                'image_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Cambio de Pastillas de Freno',
                'slug' => 'cambio-de-pastillas-de-freno',
                'description' => 'Desinstalación de pastillas gastadas, limpieza de cáliper, lubricación de pasadores e instalación de pastillas nuevas (originales o cerámicas).',
                'price' => 12000.00,
                'duration' => 30,
                'image_url' => null,
                'is_active' => true,
            ],
        ];

        foreach ($services as $serv) {
            \App\Models\Service::create($serv);
        }
    }
}
