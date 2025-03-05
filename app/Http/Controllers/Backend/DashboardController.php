<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Builder;
use App\Models\City;
use App\Models\Location;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total_users = User::count();
        $total_projects = Project::count();
        $total_builders = Builder::count();
        $total_cities = City::count();
        $total_locations = Location::count();
        return view('backend.dashboard.index', compact('total_users', 'total_projects', 'total_builders', 'total_cities', 'total_locations'));
    }

    public function ckEditorimageUpload(Request $request)
    {
        $folder = $request->query('folder', 'uploads'); // Default folder if not provided
        return response()->json(ckEditoruploadImage($request, $folder));

    }

}
