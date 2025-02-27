<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getCities(Request $request) {
        $cities = City::select('id', 'city_name')->get();
        return response()->json($cities);
    }
}
