@php
    $baseUrl = asset('frontend') . '/';
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
                <div class="mobileFilterHero">
                    <div class="cityDropDown">
                        <ul>
                            <li>
                                <a class="cityClick" href="javascript:void(0)">Ahmedabad <i
                                        class="fa-solid fa-chevron-down"></i></a>
                                <ul class="citySelect">
                                    <li><a href="javascript:void(0)">Ahmedabad</a></li>
                                    <li><a href="javascript:void(0)">Bharuch</a></li>
                                    <li><a href="javascript:void(0)">Surat</a></li>
                                    <li><a href="javascript:void(0)">Vadodara</a></li>
                                    <li><a href="javascript:void(0)">Mehsana</a></li>
                                    <li><a href="javascript:void(0)">Rajkot</a></li>
                                    <li><a href="javascript:void(0)">Jamnagar</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="seacrhNewBox">
                        <input type="search" placeholder="Search Locality">
                        <div class="searchIcon">
                            <a href="check_property_video.html" class="btn btnIcon"><i class="bi bi-search"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner section -->

    <!-- banner filter -->
    <div class="filterSec filterHide">
        <div class="container">
            <div class="bannerFilterBox">
                <div class="cityFilter">
                    <p>Choose City</p>
                    <select id="autocomplete" name="city" class="form-control" style="width: 100%;">
                        <option value="" class="d-none">Choose City</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" name="country" id="country" placeholder="Ahmedabad" /> --}}
                </div>
                <div class="landMarkFilter">
                    <div class="searchBox">
                        <p>Search</p>
                        <input type="search" placeholder="Locality, Landmark, Project, or Builder|">
                    </div>
                    <div class="searchIcon">
                        <a href="check_match_property.html" class="btn btnIcon"><i class="bi bi-search"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner filter -->

    <!-- discuss section -->
    <section class="discuss">
        <div class="container">
            <div class="discussBox">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="textBox">
                            <h4>Let’s discuss about <br> Real Estate community</h4>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                                been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                                galley of type and scrambled it to make a type specimen book.</p>
                            <a class="linkBtn" href="javascript:void(0)">Start Discuss</a>
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
    <section class="bestProperty webViewSection">
        <div class="container">
            <div class="sectionTitleBox">
                <h3>Best Properties</h3>
            </div>
            <div class="bestPropertyBox">
                <div class="row">
                    @if ($top_properties->count() > 0)
                        @foreach ($top_properties as $property)
                            <div class="col-xl-4 col-md-6">
                                <a href="{{route('property.details', [$property->slug])}}">
                                    <div class="propertySec">
                                        <div class="imgBox">
                                            @if ($property->cover_image)
                                                <img src="{{ asset('/storage/project_images/' . $property->cover_image) }}"
                                                    alt="property-img" loading="lazy">
                                            @else
                                                <img src="{{ $baseUrl }}assest/images/property-img.png" alt="property-img"
                                                    loading="lazy">
                                            @endif
                                        </div>
                                        <div class="propertyName">
                                            <h5>{{ $property->project_name }}</h5>
                                        </div>
                                        <div class="locationProperty">
                                            <div class="homeBox comBox">
                                                <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home">
                                                <p>{{ $property->custom_property_type ?? '' }}</p>
                                            </div>
                                            <div class="location comBox">
                                                <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location">
                                                <p>{{ $property->location->location_name . ', ' . $property->city->city_name }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="suggestBox">
                                            <div class="leftBtn">
                                                {{-- <a href="javascript:void(0)"> --}}
                                                    <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn">
                                                {{-- </a> --}}
                                            </div>
                                            <div class="rightBar">
                                                <h5>{{ $property->exio_suggest_percentage }}%</h5>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: {{ $property->exio_suggest_percentage }}%" role="progressbar" aria-valuenow="{{ $property->exio_suggest_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="exploreMore">
                    <a class="btn btnExplore" href="javascript:void(0)">Explore More</a>
                </div>
            </div>
        </div>
    </section>

    <section class="bestProperty mobileViewSection">
        <div class="container">
            <div class="sectionTitleBox">
                <h3>Best Properties</h3>
            </div>
            <div class="bestPropertyBox">
                <div class="owl-carousel owl-theme">
                    @if ($top_properties->count() > 0)
                        @foreach ($top_properties as $property)
                            <div class="item">
                                <a href="{{route('property.details', [$property->slug])}}">
                                    <div class="propertySec">
                                        <div class="imgBox">
                                            @if ($property->cover_image)
                                                <img src="{{ asset('/storage/project_images/' . $property->cover_image) }}" alt="property-img" loading="lazy">
                                            @else
                                                <img src="{{ $baseUrl }}assest/images/property-img.png" alt="property-img" loading="lazy">
                                            @endif
                                        </div>
                                        <div class="propertyName">
                                            <h5>{{ $property->project_name }}</h5>
                                        </div>
                                        <div class="locationProperty">
                                            <div class="homeBox comBox">
                                                <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home">
                                                <p>{{ $property->custom_property_type ?? '' }}</p>
                                            </div>
                                            <div class="location comBox">
                                                <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location">
                                                <p>{{ $property->location->location_name . ', ' . $property->city->city_name }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="suggestBox">
                                            <div class="leftBtn">
                                                <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn">
                                            </div>
                                            <div class="rightBar">
                                                <h5>{{ $property->exio_suggest_percentage }}%</h5>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $property->exio_suggest_percentage }}%"
                                                        aria-valuenow="{{ $property->exio_suggest_percentage }}"
                                                        aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="exploreMore">
                    <a class="btn btnExplore" href="javascript:void(0)">Explore More</a>
                </div>
            </div>
        </div>
    </section>
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
                            <a class="btn btnExplore" href="javascript:void(0)">Explore More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about section -->

    <!-- lates news section -->
    <section class="latesNews">
        <div class="container">
            <div class="sectionTitleBox">
                <h3>Latest News in Exio</h3>
            </div>
            <div class="latesNewsBox">
                <div class="owl-carousel owl-theme">
                    @if ($news->count() > 0)
                        @foreach ($news as $item)
                            <div class="item">
                                <div class="imgbox">
                                    @if ($item->image)
                                        <img src="{{ asset('/storage/news/image/' . $item->image) }}" alt="sliderimg"
                                            loading="lazy">
                                    @else
                                        <img src="{{ $baseUrl }}assest/images/sliderimg.png" alt="sliderimg"
                                            loading="lazy">
                                    @endif
                                </div>
                                <div class="sliderText">
                                    <span
                                        class="date">{{ $item->created_at ? $item->created_at->format('d F Y') : '' }}</span>
                                    <h5>{{ $item->title ?? '' }}</h5>
                                    <p>{{ Str::limit($item->description ?? '', 70, '...') }}</p>
                                    <ul>
                                        <li><i class="bi bi-person"></i> by <span>{{ $item->added_by ?? '' }}</span></li>
                                        <li><i class="bi bi-eye"></i> {{ $item->views ?? 0 }} Views</li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div>No News Found</div>
                    @endif
                </div>
            </div>
        </div>
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
                                        data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqsExample">
                                    <div class="accordion-body">
                                        <p>{{ $faq->answer }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What are the charges for property registration?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#faqsExample">
                                <div class="accordion-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen book.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Do women buyers get a discount in registration charges?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                data-bs-parent="#faqsExample">
                                <div class="accordion-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen book.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    How much time does it take for the documents to get registered?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                data-bs-parent="#faqsExample">
                                <div class="accordion-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen book.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Should the seller/buyer be present at the time of registration?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                data-bs-parent="#faqsExample">
                                <div class="accordion-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen book.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- faq section -->
@endsection
@section('js')

@endsection
