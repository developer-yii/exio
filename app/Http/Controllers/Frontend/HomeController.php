<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\City;
use App\Models\Faq;
use App\Models\Locality;
use App\Models\News;
use App\Models\Project;

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
            ->where('status', 1)
            ->orderBy('exio_suggest_percentage', 'desc')
            ->limit(6)
            ->get();

        $localities = Locality::where('status', 1)->get();
        $projects = Project::where('status', 1)->get();
        $builders = Builder::where('status', 1)->get();

        $news = News::where('status', 1)->orderBy('created_at', 'desc')->limit(8)->get();

        return view('frontend.home.index', compact('cities', 'faqs', 'top_properties', 'news', 'localities', 'projects', 'builders'));
    }
}
