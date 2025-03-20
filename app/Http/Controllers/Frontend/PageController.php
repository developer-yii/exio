<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CmsPages;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function termsCondition(){
        $page = CmsPages::where('page_name', 'terms-condition')->first();
        return view('frontend.pages.terms-condition', compact('page'));
    }

    public function privacyPolicy(){
        $page = CmsPages::where('page_name', 'privacy-policy')->first();
        return view('frontend.pages.privacy-policy', compact('page'));
    }

    public function aboutUs(){
        // $page = CmsPages::where('page_name', 'about-us')->first();
        // return view('frontend.pages.about-us', compact('page'));
        return view('frontend.pages.about-us');
    }

}
