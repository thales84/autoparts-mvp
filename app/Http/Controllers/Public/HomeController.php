<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $singleMake = VehicleMake::find(Setting::get('single_make_id'));

        $models = $singleMake
            ? VehicleModel::where('vehicle_make_id', $singleMake->id)
                ->orderBy('year_start')
                ->orderBy('name')
                ->get()
            : collect();

        return view('public.home', compact('singleMake', 'models'));
    }
}
