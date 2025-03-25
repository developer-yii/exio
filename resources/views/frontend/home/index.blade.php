@php
    $baseUrl = asset('frontend') . '/';
    $defaultCity = $cities->firstWhere('id', 1)->city_name;
@endphp
@extends('frontend.layouts.app')

@section('title', 'Home Page')

@section('content')
    <!-- Banner section -->
    <section class="heroBanner">
        <div class="banner-part">
            <div class="container">
                <div class="bannerText">
                    <h1>Fed Up With The Hunt For <br>Your Ideal Property?</h1>
                    <h5>Exio delivers the best property matches, effortlessly.</h5>
                </div>
                @if (getDeviceType() == 'mobile')
                    <div class="mobileFilterHero">
                        <div class="cityDropDown">
                            <ul>
                                <li>
                                    <input type="hidden" name="city_home" id="city_home" value="1" />
                                    <a class="cityClick cityClickHome" href="javascript:void(0)">
                                        <span id="city_name_home">{{ $defaultCity }}</span>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </a>
                                    <ul class="citySelect citySelectHome">
                                        @foreach ($cities as $city)
                                            <li>
                                                <a href="javascript:void(0)" data-id="{{ $city->id }}"
                                                    data-name="{{ $city->city_name }}" class="city_click">
                                                    {{ $city->city_name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="seacrhNewBox searchKeyup">
                            <input type="search" class="clickListMobile" placeholder="Search Locality, Project, or Builder" autocomplete="off">
                            @include('frontend.include.common-html-mobile-web', ['type' => 'search-key'])
                            <div class="searchIcon">
                                <a href="javascript:void(0)" id="search_btn_mobile" class="btn btnIcon">
                                    <i class="bi bi-search"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- Banner section -->

    @if (getDeviceType() == 'desktop')
        <!-- banner filter -->
        <div class="filterSec filterHide">
            <div class="container">
                <div class="bannerFilterBox">
                    <div class="cityFilter">
                        <p>Choose City</p>
                        <input type="hidden" name="city_home" id="city_home" value="1" />
                        <a href="javascript:void(0)" class="cityClick cityClickHome">
                            <span id="city_name_home">{{ $defaultCity }}</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </a>
                        <ul class="citySelect citySelectHome">
                            @foreach ($cities as $city)
                                <li>
                                    <a href="javascript:void(0)" data-id="{{ $city->id }}" data-name="{{ $city->city_name }}" class="city_click">
                                        {{ $city->city_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="landMarkFilter">
                        <div class="searchBox searchKeyup">
                            <p>Search</p>
                            <input type="search" class="clickList" placeholder="Locality, Project, or Builder" autocomplete="off">
                            @include('frontend.include.common-html-mobile-web', ['type' => 'search-key'])
                        </div>
                        <div class="searchIcon">
                            <a href="javascript:void(0)" id="search_btn_desktop" class="btn btnIcon desktop-search"><i
                                    class="bi bi-search"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- banner filter -->
    @endif

    <!-- discuss section -->
    <section class="discuss">
        <div class="container">
            <div class="discussBox">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="textBox">
                            <h4>Let's discuss about <br> Real Estate community</h4>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                                been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                                galley of type and scrambled it to make a type specimen book.</p>
                            <a class="linkBtn" href="{{ route('forum') }}">Start Discuss</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="imageBox">
                            <img src="{{ $baseUrl }}assest/images/discuss.png" alt="discuss">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- discuss section -->

    <!-- best-property section -->
    @if (getDeviceType() == 'desktop' && $top_properties->count() > 0)
        <section class="bestProperty webViewSection">
            <div class="container">
                @include('frontend.include.common-html-mobile-web', ['type' => 'best-property-title'])
                <div class="bestPropertyBox">
                    <div class="row propertyList">
                    </div>
                    @if ($top_properties->hasPages())
                        <div class="exploreMore" style="display: none">
                            <a class="btn btnExplore" id="exploreMoreDesktop" href="javascript:void(0)">Explore More</a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @if (getDeviceType() == 'mobile' && $top_properties->count() > 0)
        <section class="bestProperty mobileViewSection">
            <div class="container">
                @include('frontend.include.common-html-mobile-web', ['type' => 'best-property-title'])
                <div class="bestPropertyBox">
                    <div class="owl-carousel owl-theme propertyList">
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- best-property section -->

    <!-- find your ideal section -->
    <section class="findIdeal">
        <div class="container">
            <div class="sectionTitleBox">
                <h3>EXIO finds your ideal property match.</h3>
            </div>
            <div class="findIdealBox">
                <div class="row">
                    <div class="col-xl-4 col-md-6">
                        <div class="idealBoxCom">
                            <div class="imgBox">
                                <img src="{{ $baseUrl }}assest/images/pana.png" alt="pana">
                            </div>
                            <div class="textBox">
                                <h5>Check Rating</h5>
                                <p>Find your place with an immersive photo experience and the most listings, including
                                    things you won't find anywhere else.</p>
                                <a class="btn btnExplore" href="javascript:void(0)">Explore More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="idealBoxCom">
                            <div class="imgBox">
                                <img src="{{ $baseUrl }}assest/images/pana2.png" alt="pana2">
                            </div>
                            <div class="textBox">
                                <h5>Match with your Ratings</h5>
                                <p>Find your place with an immersive photo experience and the most listings, including
                                    things you won't find anywhere else.</p>
                                <a class="btn btnExplore" href="javascript:void(0)">More Options</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="idealBoxCom">
                            <div class="imgBox">
                                <img src="{{ $baseUrl }}assest/images/pana3.png" alt="pana3">
                            </div>
                            <div class="textBox">
                                <h5>Trending Projects</h5>
                                <p>Find your place with an immersive photo experience and the most listings, including
                                    things you won't find anywhere else.</p>
                                <a class="btn btnExplore" href="javascript:void(0)">Find Rentals</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- find your ideal section -->

    <!-- about section -->
    <section class="about">
        <div class="container">
            <div class="aboutBox">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-5">
                        <div class="aboutImg">
                            <img src="{{ $baseUrl }}assest/images/about-img.jpg" alt="about-img">
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7">
                        <div class="aboutText">
                            <h3>About Exio</h3>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                                been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                                galley of type and scrambled it to make a type specimen book type and scrambled it to make a
                                type specimen book Lorem Ipsum is simply dummy text of the printing and typesetting
                                industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when
                                an unknown printer took a galley of type and scrambled it to make a type specimen book type
                                and scrambled it to make a type specimen book.</p>
                            <div class="aboutInfo">
                                <div class="boxbox">
                                    <h4>2000+</h4>
                                    <p>Happy Customers</p>
                                </div>
                                <div class="boxbox">
                                    <h4>800+</h4>
                                    <p>Property Available</p>
                                </div>
                                <div class="boxbox">
                                    <h4>5+</h4>
                                    <p>New Cities Every Month</p>
                                </div>
                                <div class="boxbox">
                                    <h4>8+</h4>
                                    <p>Years of Successful Experience</p>
                                </div>
                            </div>
                            <a class="btn btnExplore" href="{{ route('about-us') }}">Explore More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about section -->

    <!-- lates news section -->
    <section class="latesNews">
        @if ($news->count() > 0)
            <div class="container">
                <div class="sectionTitleBox">
                    <h3>Latest News in Exio</h3>
                </div>
                <div class="latesNewsBox">
                    <div class="owl-carousel owl-theme">
                        @foreach ($news as $item)
                            <div class="item">
                                <div class="imgbox">
                                    @if ($item->image)
                                        <img src="{{ asset('/storage/news/image/' . $item->image) }}" alt="sliderimg" loading="lazy">
                                    @else
                                        <img src="{{ $baseUrl }}assest/images/sliderimg.png" alt="sliderimg" loading="lazy">
                                    @endif
                                </div>
                                <div class="sliderText">
                                    <span class="date">{{ $item->created_at ? $item->created_at->format('F d Y') : '' }}</span>
                                    <h5 class="two-line-text">{{ $item->title ?? '' }}</h5>
                                    {{-- <p>{{ Str::limit($item->description ?? '', 70, '...') }}</p> --}}
                                    <p class="five-line-text">{{ $item->description }}</p>
                                    <ul>
                                        <li><i class="bi bi-person"></i> by <span>{{ $item->added_by ?? '' }}</span></li>
                                        <li><i class="bi bi-eye"></i> {{ $item->views ?? 0 }} Views</li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </section>
    <!-- lates news section -->

    <!-- faq section -->
    <section class="question">
        <div class="container">
            <div class="sectionTitleBox">
                <h3>Frequently Asked Questions</h3>
            </div>
            <div class="questionBox">
                <div class="faqSection">
                    <div class="accordion" id="faqsExample">
                        @foreach ($faqs as $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse_{{ $faq->id }}" aria-expanded="false"
                                        aria-controls="collapse_{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapse_{{ $faq->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqsExample">
                                    <div class="accordion-body">
                                        <p>{{ $faq->answer }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- faq section -->
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            @if (getDeviceType() == 'desktop')
                $(".clickList").on("keyup", function() {
                    handleSearch($(this), '.searchBox');
                });
            @endif

            @if (getDeviceType() == 'mobile')
                $(".clickListMobile").on("keyup", function() {
                    handleSearch($(this), '.seacrhNewBox');
                });
            @endif

            function handleSearch($input, parentClass) {
                var value = $input.val().toLowerCase();
                var $searchKey = $input.closest(parentClass).find('.search-key');

                if (value === "") {
                    $searchKey.addClass('d-none');
                    $searchKey.find('li, h6').show();
                } else {
                    $searchKey.removeClass('d-none');
                    $searchKey.find('li').each(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                    $searchKey.find('h6').each(function() {
                        var $heading = $(this);
                        var $lis = $heading.nextUntil('h6', 'li');
                        if ($lis.filter(':visible').length === 0) {
                            $heading.hide();
                        } else {
                            $heading.show();
                        }
                    });
                    if ($searchKey.find('li:visible').length === 0) {
                        $searchKey.addClass('d-none');
                    }
                }
            }

            $('.search-key a').click(function() {
                const type = $(this).data('type');
                const id = $(this).data('id');
                const name = $(this).data('name');
                const isMobile = $(this).closest('.mobileFilterHero').length > 0;

                if (isMobile) {
                    $('.clickListMobile').val(name);
                } else {
                    $('.clickList').val(name);
                }

                $(this).closest('.search-key').addClass('d-none');
            });

            $('.city_click').click(function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                $('#city_home').val(id);
                $('#city_name_home').text(name);
                $('a.cityClick i').toggleClass('rotate');
            });

            $('.clickList, .clickListMobile').on('input', function() {
                if ($(this).val() === '') {
                    $('.search-key').addClass('d-none');
                }
            });

            $('#search_btn_desktop').click(function(event) {
                event.preventDefault();
                const city = $('#city_home').val();
                const searchValue = $('.clickList').val();
                window.location.href = "{{ route('property.result.filter') }}?city=" + city + "&search=" +
                    searchValue;
            });

            $('#search_btn_mobile').click(function(event) {
                event.preventDefault();
                const city = $('#city_home').val();
                const searchValue = $('.clickListMobile').val();
                window.location.href = "{{ route('property.result.filter') }}?city=" + city + "&search=" +
                    searchValue;
            });
        });
    </script>
    <script>
        var baseUrl = "{{ $baseUrl }}";

        $(document).ready(function() {
            $('.latesNews .owl-carousel').owlCarousel({
                loop: false,
                margin: 20,
                nav: true,
                navText: [
                    '<img src="' + baseUrl + 'assest/images/left-ar.png" alt="left-ar">',
                    '<img src="' + baseUrl + 'assest/images/right-ar.png" alt="right-ar">'
                ],
                dots: false,
                responsive: {
                    0: {
                        items: 1
                    },
                    575: {
                        items: 2
                    },
                    769: {
                        items: 3
                    },
                    1200: {
                        items: 4
                    }
                }
            });
        });
    </script>
    {{-- @if ($top_properties->count() > 0 && $top_properties->hasPages())
        <script>
            $(document).ready(function() {
                // let currentPage = 1;
                // let lastPageReached = false;


            });
        </script>
    @endif --}}

<script>
    var page = 1;
    var lastPage = false;
    var deviceType = "{{ getDeviceType() }}";
    var lastPageReached = false;

    var currentPage = 1;
    var isLoading = false;    
    var getPropertyDetailsUrl = "{{ route('property.details', ["_slug_"]) }}";
</script>
<script src="{{ frontendPageJsLink('home.js') }}"></script>
@endsection
