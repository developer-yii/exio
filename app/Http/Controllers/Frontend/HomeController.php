<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\City;
use App\Models\Faq;
use App\Models\Location;
use App\Models\News;
use App\Models\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $cities = City::where('status', 1)->get();
        $faqs = Faq::where('status', 1)->orderBy('order_index', 'asc')->get();
        $top_properties = Project::with([
                'builder',
                'city' => function ($query) {
                    $query->select('id', 'city_name');
                },
                'location' => function ($query) {
                    $query->select('id', 'location_name');
                }
            ])
            ->where('city_id', 1)
            ->where('status', 1)
            ->orderBy('exio_suggest_percentage', 'desc')
            ->paginate(6);

        // $localities = Locality::where('status', 1)->get();
        // $locations = Location::where('status', 1)->get();
        // $projects = Project::where('status', 1)->get();
        // $builders = Builder::where('status', 1)->get();

        // for search box
        $sLocations = Location::where('status', 1)->get();
        $sProjects = Project::where('status', 1)->get();
        $sBuilders = Builder::where('status', 1)->get();

        $news = News::where('status', 1)->orderBy('created_at', 'desc')->limit(8)->get();

        return view('frontend.home.index', compact('cities', 'faqs', 'top_properties', 'news', 'sLocations', 'sProjects', 'sBuilders'));
    }

    public function getProjects(Request $request)
    {
        $per_page = request()->per_page ?? 6;
        $projects = Project::with([
            'builder' => function ($query) {
                $query->select('id', 'builder_name');
            },
            'city' => function ($query) {
                $query->select('id', 'city_name');
            },
            'location' => function ($query) {
                $query->select('id', 'location_name');
            }
        ])
        ->where('city_id', $request->city)
        ->isActive()
        ->orderBy('exio_suggest_percentage', 'desc')
        ->paginate($per_page);

        foreach($projects as $property){
            // $property->possession_date = getFormatedDate($property->possession_by, 'M, Y');
            $property->cover_image = $property->getCoverImageUrl();

            $property->price = formatPriceRange($property->price_from, $property->price_from_unit, $property->price_to, $property->price_to_unit);

            // $property->truncatedPropertyType = truncateText($property->custom_property_type, 15);
            $property->location_city = $property->location->location_name .",". $property->city->city_name;
            // $property->truncatedLocation = truncateText($property->location_city, 15);

        }


        return response()->json([
            'status' => true,
            'message' => 'Data fetched successfully',
            'data' => $projects
        ]);
    }
}
