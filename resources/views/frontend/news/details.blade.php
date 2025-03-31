@php
    $baseUrl = asset('frontend') . '/';
    $metaTitle = "Exio | " . $news->title;
    $metaDesc = $news->description;
@endphp
@extends('frontend.layouts.app')

@section('title', $metaTitle)
{{-- @section('meta_details')
    @include('frontend.include.meta', ['title' => $metaTitle, 'description' => $metaDesc])
@endsection --}}
    @section('og_title', $metaTitle)
    @section('og_description', $metaDesc)
    @section('og_image', asset($news->getNewsImgUrl()))
    @section('og_url', url()->current())
@section('content')
     <!-- blog section start -->
     <section class="blog_detail_section">
        <div class="container">
            <div class="menuBread">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                      <li class="breadcrumb-item"><a href="{{ route('news') }}">News</a></li>
                      <li class="breadcrumb-item" aria-current="page">Blog details</li>
                    </ol>
                </nav>
            </div>
            <div class="blog_detail_box">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="details_our">
                            <div class="detail_title">
                                <h4>{{ $news->title }}</h4>
                                <div class="user_box">
                                    <h6>By 
                                        <span>{{ $news->added_by }}</span>
                                        {{ getFormatedDate($news->updated_at, 'd M Y') }}
                                    </h6>
                                    <div class="viewer_box">
                                        <span><i class="fa-solid fa-eye"></i>{{ $news->views ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="blogdetail_img">
                                <img src="{{ $news->getNewsImgUrl() }}" alt="{{ $news->title }}" loading="lazy">
                            </div>
                            <div class="our_info_box">
                                <h5>{{ $news->description }}</h5>
                                <p>{!! $news->content !!}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="right_sticky">
                            <div class="popular_post">
                                <div class="news_box">
                                    <h5>POPULAR POST</h5>
                                </div>
                                <!-- <div class="top_view_box">
                                    <div class="img_box">
                                        <img src="assest/images/boxImg2.png" alt="boxImg2">
                                        <div class="text_box">
                                            <h6>GANESH GLORY BEST PROPERTY</h6>
                                            <p>By <span>John Doe</span> 25 March 2025</p>
                                        </div>
                                    </div>
                                </div> -->
                                @foreach($popularNews as $item)
                                    <a href="{{ route('news-details', [$item->id]) }}">
                                        <div class="other_property">
                                            <div class="left_box">
                                                <img src="{{ $item->getNewsImgUrl() }}" alt="{{ $item->title }}" loading="lazy">
                                            </div>
                                            <div class="right_text">
                                                <h6 class="one-line-text" title="{{ $item->title }}">{{ $item->title }}</h6>
                                                <p>{{ getFormatedDate($item->updated_at, 'd M Y') }} </p>
                                            </div>
                                        </div>
                                        </a>
                                @endforeach
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </section>
    <!-- blog section end -->
@endsection
