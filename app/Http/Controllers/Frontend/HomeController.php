<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Faq;

class HomeController extends Controller
{
    public function index()
    {
        $cities = City::where('status', 1)->get();
        $faqs = Faq::where('status', 1)->orderBy('order_index', 'asc')->get();
        return view('frontend.home.index', compact('cities', 'faqs'));
    }
}