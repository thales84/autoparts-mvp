<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCompatibility;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            ['name' => 'Moteur',      'slug' => 'moteur'],
            ['name' => 'Freinage',    'slug' => 'freinage'],
            ['name' => 'Suspension',  'slug' => 'suspension'],
            ['name' => 'Carrosserie', 'slug' => 'carrosserie'],
            ['name' => 'Électrique',  'slug' => 'electrique'],
            ['name' => 'Transmission','slug' => 'transmission'],
            ['name' => 'Refroidissement', 'slug' => 'refroidissement'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], array_merge($cat, ['is_active' => true]));
        }

        // Vehicle makes
        $makes = ['Toyota', 'Mercedes-Benz', 'BMW', 'Nissan', 'Peugeot', 'Renault', 'Honda', 'Hyundai'];

        foreach ($makes as $makeName) {
            VehicleMake::firstOrCreate(
                ['slug' => Str::slug($makeName)],
                ['name' => $makeName]
            );
        }

        // Vehicle models
        $models = [
            'Toyota'        => ['Corolla', 'Camry', 'Land Cruiser', 'Hilux', 'Prado'],
            'Mercedes-Benz' => ['Classe C', 'Classe E', 'Classe S', 'GLE', 'Sprinter'],
            'BMW'           => ['Série 3', 'Série 5', 'X5', 'X3'],
            'Nissan'        => ['Patrol', 'Navara', 'X-Trail', 'Almera'],
            'Peugeot'       => ['206', '307', '308', '3008'],
            'Renault'       => ['Clio', 'Megane', 'Logan', 'Duster'],
        ];

        foreach ($models as $makeName => $modelNames) {
            $make = VehicleMake::where('slug', Str::slug($makeName))->first();
            if (! $make) continue;

            foreach ($modelNames as $modelName) {
                $slug = Str::slug($modelName);
                VehicleModel::firstOrCreate(
                    ['vehicle_make_id' => $make->id, 'slug' => $slug],
                    ['name' => $modelName]
                );
            }
        }

        // Sample products
        $motorCategory   = Category::where('slug', 'moteur')->first();
        $freinageCategory = Category::where('slug', 'freinage')->first();
        $suspCategory    = Category::where('slug', 'suspension')->first();

        $toyota = VehicleMake::where('slug', 'toyota')->first();
        $corolla = VehicleModel::where('slug', 'corolla')->first();
        $mercedes = VehicleMake::where('slug', 'mercedes-benz')->first();

        $products = [
            [
                'category_id'    => $motorCategory?->id,
                'sku'            => 'MTR-001',
                'oem_reference'  => '13011-15091',
                'name'           => 'Jeu de segments moteur Toyota Corolla 1.6',
                'slug'           => 'segments-moteur-toyota-corolla-1-6',
                'description'    => 'Jeu de segments moteur d\'occasion en bon état pour Toyota Corolla 1.6L essence. Référence OEM 13011-15091. Testé et vérifié.',
                'condition'      => 'used_good',
                'price'          => 23.00,
                'currency'       => 'EUR',
                'stock_quantity' => 3,
                'status'         => 'active',
            ],
            [
                'category_id'    => $freinageCategory?->id,
                'sku'            => 'FRN-001',
                'oem_reference'  => '04465-02140',
                'name'           => 'Plaquettes de frein avant Toyota Corolla',
                'slug'           => 'plaquettes-frein-avant-toyota-corolla',
                'description'    => 'Plaquettes de frein avant d\'occasion pour Toyota Corolla. Épaisseur restante : 8mm. Bon état général.',
                'condition'      => 'used_good',
                'price'          => 13.00,
                'currency'       => 'EUR',
                'stock_quantity' => 5,
                'status'         => 'active',
            ],
            [
                'category_id'    => $suspCategory?->id,
                'sku'            => 'SUS-001',
                'oem_reference'  => 'A2043200930',
                'name'           => 'Amortisseur avant gauche Mercedes Classe C W204',
                'slug'           => 'amortisseur-avant-gauche-mercedes-c-w204',
                'description'    => 'Amortisseur avant gauche pour Mercedes-Benz Classe C W204 (2007-2014). État correct, reconditionné.',
                'condition'      => 'refurbished',
                'price'          => 69.00,
                'currency'       => 'EUR',
                'stock_quantity' => 1,
                'status'         => 'active',
            ],
            [
                'category_id'    => $motorCategory?->id,
                'sku'            => 'MTR-002',
                'oem_reference'  => null,
                'name'           => 'Alternateur Toyota Land Cruiser 4.5',
                'slug'           => 'alternateur-toyota-land-cruiser-4-5',
                'description'    => 'Alternateur d\'occasion pour Toyota Land Cruiser moteur 4.5L. Débit 80A. À vérifier électriquement avant installation.',
                'condition'      => 'used_fair',
                'price'          => 55.00,
                'currency'       => 'EUR',
                'stock_quantity' => 0,
                'status'         => 'active',
            ],
            [
                'category_id'    => $freinageCategory?->id,
                'sku'            => 'FRN-002',
                'oem_reference'  => null,
                'name'           => 'Disque de frein arrière BMW Série 3 E46',
                'slug'           => 'disque-frein-arriere-bmw-serie-3-e46',
                'description'    => 'Disque de frein arrière pour BMW Série 3 E46 (1998-2005). Épaisseur conforme. Vendu à l\'unité.',
                'condition'      => 'used_good',
                'price'          => 18.00,
                'currency'       => 'EUR',
                'stock_quantity' => 2,
                'status'         => 'active',
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::firstOrCreate(['sku' => $productData['sku']], $productData);

            // Add compatibilities for first 2 products
            if ($product->sku === 'MTR-001' && $toyota && $corolla) {
                ProductCompatibility::firstOrCreate(
                    ['product_id' => $product->id, 'vehicle_make_id' => $toyota->id, 'vehicle_model_id' => $corolla->id],
                    ['year_from' => 2000, 'year_to' => 2013, 'notes' => 'Moteur 1NZ-FE 1.6L']
                );
            }

            if ($product->sku === 'SUS-001' && $mercedes) {
                $classeC = VehicleModel::where('slug', 'classe-c')->first();
                ProductCompatibility::firstOrCreate(
                    ['product_id' => $product->id, 'vehicle_make_id' => $mercedes->id, 'vehicle_model_id' => $classeC?->id],
                    ['year_from' => 2007, 'year_to' => 2014, 'notes' => 'W204']
                );
            }
        }
    }
}
