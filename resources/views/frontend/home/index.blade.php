@php
    $baseUrl = asset('frontend') . '/';
@endphp
@extends('frontend.layouts.app')

@section('title', 'Home Page')

@section('css')
    <style>
        .ui-menu .ui-menu-item-wrapper:hover {
            background-color: #EEF6FF;
            color: var(--blue-color);
            border: none;
        }

        .ui-menu .ui-menu-item-wrapper {
            font-size: 15px;
            font-weight: 400;
            font-family: "Inter", serif;
            width: 100%;
            padding: 10px 8px;
            border-radius: 5px;
            border: none;
            transition: 0.2s all;
        }

        .ui-widget.ui-widget-content {
            background-color: var(--white-color);
            padding: 15px 10px;
            margin-top: 10px;
            border-radius: 5px;
            box-shadow: 0px 7px 25px #00000028;
        }

        .selected-items {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .selected-item {
            background: #EEF6FF;
            padding: 4px 12px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remove-item {
            cursor: pointer;
            color: #666;
        }
    </style>
@endsection

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
                            <input type="text" name="city" id="city" class="autocomplete-mobile form-control"
                                placeholder="Ahmedabad" />
                        </div>
                        <div class="seacrhNewBox">
                            <div class="selected-items-mobile"></div>
                            <input type="search" class="clickListMobile" placeholder="Search Locality"
                                onkeyup="searchListM(this);">
                            <div class="search-key d-none">
                                <ul>
                                    @if ($localities->count() > 0)
                                        <h6>Locality</h6>
                                        @foreach ($localities as $locality)
                                            <li style="display: none;">
                                                <a href="javascript:void(0)" data-type="locality"
                                                    data-id="{{ $locality->id }}"
                                                    data-name="{{ $locality->locality_name }}">
                                                    {{ $locality->locality_name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                    <h6>Landmark</h6>
                                    {{-- @if ($landmarks->count() > 0)
                                        @foreach ($landmarks as $landmark)
                                            <li style="display: none;"><a
                                                    href="javascript:void(0)" data-landmark="{{ $landmark->id }}">{{ $landmark->landmark_name }}</a></li>
                                        @endforeach
                                    @endif --}}
                                    <h6>Project</h6>
                                    @if ($projects->count() > 0)
                                        @foreach ($projects as $project)
                                            <li style="display: none;">
                                                <a href="javascript:void(0)" data-type="project"
                                                    data-id="{{ $project->id }}" data-name="{{ $project->project_name }}">
                                                    {{ $project->project_name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                    <h6>Builder</h6>
                                    @if ($builders->count() > 0)
                                        @foreach ($builders as $builder)
                                            <li style="display: none;"><a href="javascript:void(0)" data-type="builder"
                                                    data-id="{{ $builder->id }}"
                                                    data-name="{{ $builder->builder_name }}">{{ $builder->builder_name }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="searchIcon">
                                <a href="" class="btn btnIcon"><i class="bi bi-search"></i></a>
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
                        {{-- <select id="autocomplete" name="city" class="form-control" style="width: 100%;">
                        <option value="" class="d-none">Choose City</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                        @endforeach
                    </select> --}}
                        <input type="text" name="city" id="city" class="autocomplete form-control"
                            placeholder="Ahmedabad" />
                    </div>
                    <div class="landMarkFilter">
                        <div class="searchBox">
                            <p>Search</p>
                            <div class="selected-items"></div>
                            <input type="search" class="clickList" placeholder="Locality, Landmark, Project, or Builder"
                                onkeyup="searchList(this);">
                            <div class="search-key d-none">
                                <ul>
                                    @if ($localities->count() > 0)
                                        <h6>Locality</h6>
                                        @foreach ($localities as $locality)
                                            <li style="display: none;"><a href="javascript:void(0)" data-type="locality"
                                                    data-id="{{ $locality->id }}"
                                                    data-name="{{ $locality->locality_name }}">{{ $locality->locality_name }}</a>
                                        @endforeach
                                    @endif
                                    <h6>Landmark</h6>
                                    {{-- @if ($landmarks->count() > 0)
                                        @foreach ($landmarks as $landmark)
                                            <li style="display: none;"><a
                                                    href="javascript:void(0)" data-landmark="{{ $landmark->id }}">{{ $landmark->landmark_name }}</a></li>
                                        @endforeach
                                    @endif --}}
                                    <h6>Project</h6>
                                    @if ($projects->count() > 0)
                                        @foreach ($projects as $project)
                                            <li style="display: none;"><a href="javascript:void(0)" data-type="project"
                                                    data-id="{{ $project->id }}"
                                                    data-name="{{ $project->project_name }}">{{ $project->project_name }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                    <h6>Builder</h6>
                                    @if ($builders->count() > 0)
                                        @foreach ($builders as $builder)
                                            <li style="display: none;"><a href="javascript:void(0)" data-type="builder"
                                                    data-id="{{ $builder->id }}"
                                                    data-name="{{ $builder->builder_name }}">{{ $builder->builder_name }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="searchIcon">
                            <a href="" class="btn btnIcon"><i class="bi bi-search"></i></a>
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
    @if (getDeviceType() == 'desktop')
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
                                    <a href="{{ route('property.details', [$property->slug]) }}">
                                        <div class="propertySec">
                                            <div class="imgBox">
                                                @if ($property->cover_image)
                                                    <img src="{{ asset('/storage/project_images/' . $property->cover_image) }}"
                                                        alt="property-img" loading="lazy">
                                                @else
                                                    <img src="{{ $baseUrl }}assest/images/property-img.png"
                                                        alt="property-img" loading="lazy">
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
                                                    <img src="{{ $baseUrl }}assest/images/Location.png"
                                                        alt="Location">
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
                                                        <div class="progress-bar"
                                                            style="width: {{ $property->exio_suggest_percentage }}%"
                                                            role="progressbar"
                                                            aria-valuenow="{{ $property->exio_suggest_percentage }}"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
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
    @endif

    @if (getDeviceType() == 'mobile')
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
                                    <a href="{{ route('property.details', [$property->slug]) }}">
                                        <div class="propertySec">
                                            <div class="imgBox">
                                                @if ($property->cover_image)
                                                    <img src="{{ asset('/storage/project_images/' . $property->cover_image) }}"
                                                        alt="property-img" loading="lazy">
                                                @else
                                                    <img src="{{ $baseUrl }}assest/images/property-img.png"
                                                        alt="property-img" loading="lazy">
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
                                                    <img src="{{ $baseUrl }}assest/images/Location.png"
                                                        alt="Location">
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
            let cities = @json($cities);
            let filteredCities = cities.map(city => ({
                label: city.city_name,
                value: city.id
            }));

            @if (getDeviceType() == 'desktop')
                $('.autocomplete').autocomplete({
                    source: filteredCities,
                    minLength: 0,
                    select: function(event, ui) {
                        event.preventDefault();
                        $(this).val(ui.item.label);
                        $('#city').val(ui.item.label);
                    }
                });

                $('.autocomplete').on('focus', function() {
                    $('.autocomplete').autocomplete('search', '');
                });

                $(".clickList").on("keyup", function() {
                    handleSearch($(this), '.searchBox');
                });
            @endif

            @if (getDeviceType() == 'mobile')
                $('.autocomplete-mobile').autocomplete({
                    source: filteredCities,
                    minLength: 0,
                    select: function(event, ui) {
                        event.preventDefault();
                        $(this).val(ui.item.label);
                        $('#city').val(ui.item.label);
                    }
                });

                $('.autocomplete-mobile').on('focus', function() {
                    $('.autocomplete-mobile').autocomplete('search', '');
                });

                $(".clickListMobile").on("keyup", function() {
                    handleSearch($(this), '.seacrhNewBox');
                });
            @endif

            function handleSearch($input, parentClass) {
                var value = $input.val().toLowerCase();
                if (value === "") {
                    $input.closest(parentClass).find('.search-key').addClass('d-none');
                } else {
                    $input.closest(parentClass).find('.search-key').removeClass('d-none');
                    $input.closest(parentClass).find("li").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                }
            }

            $('.search-key a').click(function() {
                const type = $(this).data('type');
                const id = $(this).data('id');
                const name = $(this).data('name');
                const isMobile = $(this).closest('.mobileFilterHero').length > 0;

                if (isMobile) {
                    addSelectedItem(type, id, name, '.selected-items-mobile', '.clickListMobile');
                } else {
                    addSelectedItem(type, id, name, '.selected-items', '.clickList');
                }

                $(this).closest('.search-key').addClass('d-none');
            });

            function addSelectedItem(type, id, name, containerClass, inputClass) {
                if ($(`${containerClass} [data-selected-id="${id}"][data-selected-type="${type}"]`).length > 0) {
                    return;
                }

                const item = `
                    <div class="selected-item" data-selected-type="${type}" data-selected-id="${id}">
                        <span>${name}</span>
                        <span class="remove-item">×</span>
                    </div>
                `;

                $(containerClass).append(item);
                $(inputClass).val('');
            }

            $(document).on('click', '.remove-item', function() {
                $(this).parent('.selected-item').remove();
            });
        });
    </script>
@endsection
