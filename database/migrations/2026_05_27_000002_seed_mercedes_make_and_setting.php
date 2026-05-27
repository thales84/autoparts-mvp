<?php

use App\Models\Setting;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Insère Mercedes-Benz si absent
        $mercedes = VehicleMake::firstOrCreate(
            ['slug' => 'mercedes-benz'],
            ['name' => 'Mercedes-Benz']
        );

        // Active le mode marque unique
        Setting::set('single_make_id', $mercedes->id);

        // Insère les modèles avec year_start si absents
        $models = [
            ['name' => 'Classe C',  'year_start' => 2000],
            ['name' => 'Classe E',  'year_start' => 2002],
            ['name' => 'Vito',      'year_start' => 2003],
            ['name' => 'Classe A',  'year_start' => 2004],
            ['name' => 'Classe B',  'year_start' => 2005],
            ['name' => 'Classe S',  'year_start' => 2005],
            ['name' => 'Sprinter',  'year_start' => 2006],
            ['name' => 'GLE',       'year_start' => 2012],
            ['name' => 'GLA',       'year_start' => 2013],
            ['name' => 'GLS',       'year_start' => 2013],
            ['name' => 'GLC',       'year_start' => 2015],
        ];

        foreach ($models as $item) {
            $slug = Str::slug($item['name']);
            $model = VehicleModel::firstOrCreate(
                ['vehicle_make_id' => $mercedes->id, 'slug' => $slug],
                ['name' => $item['name'], 'year_start' => $item['year_start']]
            );

            if (is_null($model->year_start)) {
                $model->update(['year_start' => $item['year_start']]);
            }
        }
    }

    public function down(): void
    {
        Setting::where('key', 'single_make_id')->delete();
    }
};
