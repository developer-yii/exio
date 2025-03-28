@php
    $baseUrl = asset('frontend') . '/';
    $metaTitle = "Exio | News List";
    $metaDesc = "Exio | News List";
@endphp
@extends('frontend.layouts.app')

@section('title', 'News')
@section('og_title', $metaTitle)
@section('og_description', $metaDesc)
@section('og_url', url()->current())

@section('content')
    <!-- blog section start -->
    <section class="blog_main_section">
        <div class="container">
            <div class="blog_inner_box">                
                <div class="row" id="newsContainer">
                    @include('frontend.news.partial_news_list', ['news' => $news])
                    <!-- @foreach($news as $item)                        
                        <div class="col-lg-4 col-md-6">
                            <a href="javascript:void(0)">
                                <div class="blog_item">
                                    <div class="blog_img">
                                        <img src="{{ $item->getNewsImgUrl() }}" alt="property-img">
                                        <div class="addedBy">
                                            <p>Added: 
                                                <span>
                                                    {{ getFormatedDate($item->updated_at, 'd M Y') }}
                                                
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="blog_text">
                                        <h5 class="one-line-text" title="{{ $item->title }}">{{ $item->title }}</h5>
                                        <h6 class="one-line-text" title="{{ $item->description }}">{{ $item->description }}</h6>
                                        <p class="discript four-line-text"> {!! $item->content !!}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach -->
                </div>               

                @if ($totalNews > $perPageNews)
                    <div class="loadMore">
                        <a class="btn btnExplore" id="loadMoreBtn" href="javascript:void(0)">Load More</a>
                    </div>
                @endif

                <!-- <div class="loadMore">
                    <a class="btn btnExplore" href="javascript:void(0)">Load More</a>
                </div> -->
            </div>
        </div>
     </section>
    <!-- blog section end -->
@endsection
@section('js')
    <script>
        var page = 2; // Start from page 2 since initial load is page 1
        var loadaMoreUrl = "{{ route('news') }}";
    </script>
    <script src="{{ frontendPageJsLink('news.js') }}"></script>    
@endsection


