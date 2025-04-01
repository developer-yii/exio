<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CmsPages;
use App\Models\News;
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

    public function news(Request $request){

        $perPageNews = 9;
        $page = $request->input('page', 1);

        $allNews = News::where('status', 1)->orderBy('id', 'desc'); 
        $totalNews = (clone $allNews)->count();

        $news = $allNews->paginate($perPageNews);

        if ($request->ajax()) {
            return response()->json([
                'news' => view('frontend.news.partial_news_list', compact('news'))->render(),
                'hasMore' => $news->hasMorePages()
            ]);
        }

        return view('frontend.news.index', compact('news', 'perPageNews', 'totalNews'));
    }

    public function newsDetails(Request $request, $id){

        $news = News::findOrFail($id);
        $news->increment('views');
        $popularNews = News::where('id', "!=", $id)->orderBy('views', 'desc')->take(5)->get();
        return view('frontend.news.details', compact('news', 'popularNews'));
    }

}
