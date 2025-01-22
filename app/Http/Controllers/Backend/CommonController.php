<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\City;

class CommonController extends Controller
{
    public function getAllCities()
    {
        $cities = City::where('status', City::ACTIVE)
            ->orderBy('city_name', 'ASC')
            ->pluck('city_name', 'id');

        $result = $cities->isNotEmpty()
            ? ['status' => true, 'data' => $cities]
            : ['status' => false, 'data' => []];

        return response()->json($result);
    }
}
