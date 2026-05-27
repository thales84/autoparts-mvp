<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCompatibility;
use App\Models\Setting;
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

        // Vehicle makes — spécialiste Mercedes-Benz uniquement
        $mercedes = VehicleMake::firstOrCreate(
            ['slug' => 'mercedes-benz'],
            ['name' => 'Mercedes-Benz']
        );

        Setting::set('single_make_id', $mercedes->id);

        // Vehicle models — avec year_start (première mise en vente de la génération la plus ancienne courante)
        $mercedesModels = [
            ['name' => 'Classe C',  'year_start' => 2000],  // W203
            ['name' => 'Classe E',  'year_start' => 2002],  // W211
            ['name' => 'Vito',      'year_start' => 2003],  // W639
            ['name' => 'Classe A',  'year_start' => 2004],  // W169
            ['name' => 'Classe B',  'year_start' => 2005],  // W245
            ['name' => 'Classe S',  'year_start' => 2005],  // W221
            ['name' => 'Sprinter',  'year_start' => 2006],  // NCV3 W906
            ['name' => 'GLE',       'year_start' => 2012],  // W166
            ['name' => 'GLA',       'year_start' => 2013],  // X156
            ['name' => 'GLS',       'year_start' => 2013],  // X166
            ['name' => 'GLC',       'year_start' => 2015],  // X253
        ];

        foreach ($mercedesModels as $item) {
            $slug = Str::slug($item['name']);
            VehicleModel::firstOrCreate(
                ['vehicle_make_id' => $mercedes->id, 'slug' => $slug],
                ['name' => $item['name'], 'year_start' => $item['year_start']]
            );
            // Met à jour year_start si le modèle existait déjà sans cette donnée
            VehicleModel::where('vehicle_make_id', $mercedes->id)
                ->where('slug', $slug)
                ->whereNull('year_start')
                ->update(['year_start' => $item['year_start']]);
        }

        // Sample products — Mercedes-Benz uniquement
        $catMoteur    = Category::where('slug', 'moteur')->first();
        $catFreinage  = Category::where('slug', 'freinage')->first();
        $catSusp      = Category::where('slug', 'suspension')->first();
        $catElec      = Category::where('slug', 'electrique')->first();
        $catTrans     = Category::where('slug', 'transmission')->first();

        $classeC = VehicleModel::where('slug', 'classe-c')->first();
        $classeE = VehicleModel::where('slug', 'classe-e')->first();
        $gle     = VehicleModel::where('slug', 'gle')->first();
        $sprinter = VehicleModel::where('slug', 'sprinter')->first();

        $samples = [
            [
                'data' => [
                    'category_id'    => $catSusp?->id,
                    'sku'            => 'SUS-001',
                    'oem_reference'  => 'A2043200930',
                    'name'           => 'Amortisseur avant gauche Mercedes Classe C W204',
                    'slug'           => 'amortisseur-avant-gauche-mercedes-c-w204',
                    'description'    => 'Amortisseur avant gauche d\'occasion pour Mercedes-Benz Classe C W204 (2007-2014). Reconditionné, test de rebond OK.',
                    'condition'      => 'refurbished',
                    'price'          => 69.00,
                    'currency'       => 'EUR',
                    'stock_quantity' => 1,
                    'status'         => 'active',
                ],
                'make'  => $mercedes,
                'model' => $classeC,
                'years' => [2007, 2014, 'W204'],
            ],
            [
                'data' => [
                    'category_id'    => $catMoteur?->id,
                    'sku'            => 'MTR-001',
                    'oem_reference'  => 'A6510101520',
                    'name'           => 'Culasse Mercedes Classe E 220 CDI W212',
                    'slug'           => 'culasse-mercedes-classe-e-220-cdi-w212',
                    'description'    => 'Culasse d\'occasion pour Mercedes-Benz Classe E 220 CDI (W212, 2009-2016). Rectifiée, sans fissure, joints fournis.',
                    'condition'      => 'refurbished',
                    'price'          => 195.00,
                    'currency'       => 'EUR',
                    'stock_quantity' => 1,
                    'status'         => 'active',
                ],
                'make'  => $mercedes,
                'model' => $classeE,
                'years' => [2009, 2016, 'W212 OM651'],
            ],
            [
                'data' => [
                    'category_id'    => $catFreinage?->id,
                    'sku'            => 'FRN-001',
                    'oem_reference'  => 'A1664200083',
                    'name'           => 'Étrier de frein avant droit Mercedes GLE W166',
                    'slug'           => 'etrier-frein-avant-droit-mercedes-gle-w166',
                    'description'    => 'Étrier de frein avant droit d\'occasion pour Mercedes-Benz GLE W166 (2015-2019). Piston non grippé, reconditionné avec kit joints neuf.',
                    'condition'      => 'refurbished',
                    'price'          => 55.00,
                    'currency'       => 'EUR',
                    'stock_quantity' => 2,
                    'status'         => 'active',
                ],
                'make'  => $mercedes,
                'model' => $gle,
                'years' => [2015, 2019, 'W166'],
            ],
            [
                'data' => [
                    'category_id'    => $catElec?->id,
                    'sku'            => 'ELC-001',
                    'oem_reference'  => 'A0061540002',
                    'name'           => 'Alternateur Mercedes Sprinter 2.2 CDI',
                    'slug'           => 'alternateur-mercedes-sprinter-2-2-cdi',
                    'description'    => 'Alternateur d\'occasion pour Mercedes-Benz Sprinter 2.2 CDI (2006-2018). Charge testée à 14V / 150A. Poulie et régulateur OK.',
                    'condition'      => 'used_good',
                    'price'          => 85.00,
                    'currency'       => 'EUR',
                    'stock_quantity' => 2,
                    'status'         => 'active',
                ],
                'make'  => $mercedes,
                'model' => $sprinter,
                'years' => [2006, 2018, 'NCV3 OM651'],
            ],
            [
                'data' => [
                    'category_id'    => $catTrans?->id,
                    'sku'            => 'TRS-001',
                    'oem_reference'  => 'A2042600700',
                    'name'           => 'Boîte de vitesses automatique Mercedes Classe C W204',
                    'slug'           => 'boite-vitesses-auto-mercedes-classe-c-w204',
                    'description'    => 'Boîte de vitesses automatique 7G-Tronic d\'occasion pour Mercedes-Benz Classe C W204 (2008-2014). Passage des rapports propre, sans bruit.',
                    'condition'      => 'used_good',
                    'price'          => 480.00,
                    'currency'       => 'EUR',
                    'stock_quantity' => 1,
                    'status'         => 'active',
                ],
                'make'  => $mercedes,
                'model' => $classeC,
                'years' => [2008, 2014, 'W204 7G-Tronic'],
            ],
        ];

        foreach ($samples as $item) {
            $product = Product::firstOrCreate(['sku' => $item['data']['sku']], $item['data']);

            if ($item['make'] && $item['model']) {
                [$yearFrom, $yearTo, $notes] = $item['years'];
                ProductCompatibility::firstOrCreate(
                    [
                        'product_id'      => $product->id,
                        'vehicle_make_id' => $item['make']->id,
                        'vehicle_model_id' => $item['model']->id,
                    ],
                    ['year_from' => $yearFrom, 'year_to' => $yearTo, 'notes' => $notes]
                );
            }
        }
    }
}
