@php
    $baseUrl = asset('frontend/');
@endphp
@extends('frontend.layouts.app')

@section('css')
    <!-- Google Maps -->
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    <script>
        (g => {
            var h, a, k, p = "The Google Maps JavaScript API",
                c = "google",
                l = "importLibrary",
                q = "__ib__",
                m = document,
                b = window;
            b = b[c] || (b[c] = {});
            var d = b.maps || (b.maps = {}),
                r = new Set,
                e = new URLSearchParams,
                u = () => h || (h = new Promise(async (f, n) => {
                    await (a = m.createElement("script"));
                    e.set("libraries", [...r] + "");
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                    e.set("callback", c + ".maps." + q);
                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                    d[q] = f;
                    a.onerror = () => h = n(Error(p + " could not load."));
                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                    m.head.append(a)
                }));
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
                d[l](f, ...n))
        })
        ({
            key: "{{ config('constants.google_maps_api_key') }}",
            v: "weekly"
        });
    </script>
    <link rel="stylesheet" href="{{ asset('frontend/assest/css/extra.css') }}">
@endsection

@section('content')
    <section class="checkProperty">
        <div class="container">
            <div class="checkPropertyBox">
                <div class="main_property">
                    <div class="left_propertyBox">
                        <div class="topFilterBar">
                            <div class="searchBar">
                                <input type="search" name="filter_search" id="filter_search" value="{{ request('search') }}"
                                    placeholder="Locality, Landmark, Project, or Builder">
                                <a href="javascript:void(0)" id="clear_search"><i
                                        class="fa-solid fa-magnifying-glass"></i></a>
                            </div>
                            <div class="filterBox">
                                <a href="javascript:void(0)"><i class="bi bi-funnel"></i></a>
                            </div>
                        </div>
                        <div class="proertyMapBox">
                            <div id="map" class="map">

                            </div>
                            <div class="porpertyFilter">
                                <div class="filterTitle">
                                    <h5>Filter Property</h5>
                                </div>
                                <div class="filterContent">
                                    <div class="comViewBox first">
                                        <p>You are looking to...</p>
                                        <div class="lookingProperty">
                                            <div class="feetSelectBox">
                                                @foreach ($propertyTypes as $key => $type)
                                                    <div class="clickTo">
                                                        <input type="radio" name="property_type" class="keyword-checkbox"
                                                            value="{{ $key }}">
                                                        <label for=""
                                                            class="keyword-label">{{ $type }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="comViewBox second">
                                        <p>It's a...</p>
                                        <div class="feetSelectBox" id="property-sub-type">

                                        </div>
                                    </div>
                                    <div class="comViewBox third d-none" id="bhk-filter">
                                        <p>BHK</p>
                                        <div class="feetSelectBox">
                                            @foreach ($bhks as $key => $bhk)
                                                <div class="clickTo">
                                                    <label class="checkbox">
                                                        <input class="checkbox__input" type="checkbox" name="bhk[]"
                                                            value="{{ $key }}" />
                                                        <span class="checkbox__label">{{ $bhk }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="comViewBox four">
                                        <p>Amenities</p>
                                        <div class="feetSelectBox">
                                            @if ($amenities->count() > 0)
                                                @foreach ($amenities->slice(0, 7) as $key => $amenity)
                                                    <div class="clickTo">
                                                        <label class="checkbox">
                                                            <input class="checkbox__input" type="checkbox"
                                                                id="amenities_{{ $key }}" name="amenities[]"
                                                                value="{{ $key }}" />
                                                            <span class="checkbox__label">{{ $amenity }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                                @foreach ($amenities->slice(7) as $key => $amenity)
                                                    <div class="clickTo hidden-amenity d-none">
                                                        <label class="checkbox">
                                                            <input class="checkbox__input" type="checkbox"
                                                                id="amenities_{{ $key }}" name="amenities[]"
                                                                value="{{ $key }}" />
                                                            <span class="checkbox__label">{{ $amenity }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                                @if ($amenities->count() > 7)
                                                    <div class="clickTo">
                                                        <a href="javascript:void(0)" id="moreAmenities">+ more</a>
                                                    </div>
                                                    <div class="clickTo d-none" id="lessAmenities">
                                                        <a href="javascript:void(0)" id="lessAmenities">- less</a>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="comViewBox five">
                                        <p>Budget</p>
                                        <div class="range-slider">
                                            <input type="range" min="{{ $minMaxPrice['min_price'] ?? '' }}"
                                                max="{{ $minMaxPrice['max_price'] ?? '' }}"
                                                value="{{ $minMaxPrice['min_price'] ?? '' }}" id="slider-min"
                                                aria-valuemin="1" aria-valuemax="100">
                                            <input type="range" min="{{ $minMaxPrice['min_price'] ?? '' }}"
                                                max="{{ $minMaxPrice['max_price'] ?? '' }}"
                                                value="{{ $minMaxPrice['max_price'] ?? '' }}" id="slider-max"
                                                aria-valuemin="1" aria-valuemax="100">
                                        </div>
                                        <div class="selectArea">
                                            <div class="dropBox" id="slider-min-value">
                                                1
                                            </div>
                                            <div class="dropBox text-end" id="slider-max-value">
                                                100
                                            </div>
                                        </div>
                                    </div>
                                    <div class="comViewBox">
                                        <a class="btn linkBtn btnFull" id="applyFilter">Apply</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="project-list" class="right_propertyBox">
                        <div class="rightListSec">
                            <div class="toptabSec">
                                <div class="tabflex">
                                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-home" type="button" role="tab"
                                                aria-controls="pills-home" aria-selected="true"><i
                                                    class="fa-solid fa-layer-group"></i> All</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-profile" type="button" role="tab"
                                                aria-controls="pills-profile" aria-selected="false"><i
                                                    class="fa-solid fa-thumbs-up"></i> Appraisal</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-match-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-match" type="button" role="tab"
                                                aria-controls="pills-match" aria-selected="false"><i
                                                    class="fa-solid fa-puzzle-piece"></i> Best Match</button>
                                        </li>
                                    </ul>
                                    <div class="heartBox">
                                        <a href="{{ route('property.shortlisted') }}">
                                            <i class="fa-solid fa-heart"></i>
                                            <span>{{ number_format($shortlistedCount) }}</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                        aria-labelledby="pills-home-tab">
                                        <div class="row">
                                            @foreach ($projects as $project)
                                                <div class="col-md-6">
                                                    <div class="propertyCard" data-id="{{ $project->id }}">
                                                        <div class="imgBox">
                                                            <img src="{{ asset('/') }}frontend/assest/images/property-img.png"
                                                                alt="property-img">
                                                            <div class="imgheader">
                                                                <span>Best for Investment</span>
                                                                <i data-id="{{ $project->id }}"
                                                                    class="{{ $project->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart heartIconFill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="priceBox">
                                                            <div class="price">
                                                                <h5>₹{{ $project->price_from }}L-{{ $project->price_to }}Cr
                                                                </h5>
                                                            </div>
                                                            <div class="boxLogo">
                                                                <img src="{{ asset('/') }}frontend/assest/images/x-btn.png"
                                                                    alt="x-btn">
                                                                <span>{{ $project->exio_suggest_percentage }}%</span>
                                                            </div>
                                                        </div>
                                                        <div class="propertyName">
                                                            <h5>{{ $project->project_name }}</h5>
                                                        </div>
                                                        <div class="locationProperty">
                                                            <div class="homeBox comBox">
                                                                <img src="{{ asset('/') }}frontend/assest/images/Home.png"
                                                                    alt="Home">
                                                                <p>{{ $project->custom_property_type }} |
                                                                    {{ isset($project->floor_plans)? $project->floor_plans->map(function ($plan) {return $plan->carpet_area . ' Sqft';})->join(', '): '' }}
                                                                    | {{ $project->location->location_name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="addressBox">
                                                            <img src="{{ asset('/') }}frontend/assest/images/Home.png"
                                                                alt="Home">
                                                            <p>
                                                                @php
                                                                    $amenities_a = explode(',', $project->amenities);
                                                                    $amenityNames = [];
                                                                    foreach ($amenities_a as $amenityId) {
                                                                        if (isset($amenities[$amenityId])) {
                                                                            $amenityNames[] = $amenities[$amenityId];
                                                                        }
                                                                    }
                                                                    $amenities_a = implode(
                                                                        ', ',
                                                                        array_slice($amenityNames, 0, 4),
                                                                    );
                                                                @endphp
                                                                {{ $amenities_a }}
                                                            </p>
                                                            <a href="javascript:void(0)">more</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                        aria-labelledby="pills-profile-tab">
                                        <div class="row">
                                            @foreach ($appraisal as $project)
                                                <div class="col-md-6">
                                                    <div class="propertyCard" data-id="{{ $project->id }}">
                                                        <div class="imgBox">
                                                            <img src="{{ asset('/') }}frontend/assest/images/property-img.png"
                                                                alt="property-img">
                                                            <div class="imgheader">
                                                                <span>Best for Investment</span>
                                                                <i data-id="{{ $project->id }}"
                                                                    class="{{ $project->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart heartIconFill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="priceBox">
                                                            <div class="price">
                                                                <h5>₹{{ $project->price_from }}L-{{ $project->price_to }}Cr
                                                                </h5>
                                                            </div>
                                                            <div class="boxLogo">
                                                                <img src="{{ asset('/') }}frontend/assest/images/x-btn.png"
                                                                    alt="x-btn">
                                                                <span>{{ $project->exio_suggest_percentage }}%</span>
                                                            </div>
                                                        </div>
                                                        <div class="propertyName">
                                                            <h5>{{ $project->project_name }}</h5>
                                                        </div>
                                                        <div class="locationProperty">
                                                            <div class="homeBox comBox">
                                                                <img src="{{ asset('/') }}frontend/assest/images/Home.png"
                                                                    alt="Home">
                                                                <p>{{ $project->custom_property_type }} |
                                                                    {{ isset($project->floor_plans)? $project->floor_plans->map(function ($plan) {return $plan->carpet_area . ' Sqft';})->join(', '): '' }}
                                                                    | {{ $project->location->location_name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="addressBox">
                                                            <img src="{{ asset('/') }}frontend/assest/images/Home.png"
                                                                alt="Home">
                                                            <p>
                                                                @php
                                                                    $amenities_a = explode(',', $project->amenities);
                                                                    $amenityNames = [];
                                                                    foreach ($amenities_a as $amenityId) {
                                                                        if (isset($amenities[$amenityId])) {
                                                                            $amenityNames[] = $amenities[$amenityId];
                                                                        }
                                                                    }
                                                                    $amenities_a = implode(
                                                                        ', ',
                                                                        array_slice($amenityNames, 0, 4),
                                                                    );
                                                                @endphp
                                                                {{ $amenities_a }}
                                                            </p>
                                                            <a href="javascript:void(0)">more</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-match" role="tabpanel"
                                        aria-labelledby="pills-match-tab">
                                        <div class="row">
                                            @foreach ($bestMatch as $project)
                                                <div class="col-md-6">
                                                    <div class="propertyCard" data-id="{{ $project->id }}">
                                                        <div class="imgBox">
                                                            <img src="{{ asset('/') }}frontend/assest/images/property-img.png"
                                                                alt="property-img">
                                                            <div class="imgheader">
                                                                <span>Best for Investment</span>
                                                                <i data-id="{{ $project->id }}"
                                                                    class="{{ $project->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart heartIconFill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="priceBox">
                                                            <div class="price">
                                                                <h5>₹{{ $project->price_from }}L-{{ $project->price_to }}Cr
                                                                </h5>
                                                            </div>
                                                            <div class="boxLogo">
                                                                <img src="{{ asset('/') }}frontend/assest/images/x-btn.png"
                                                                    alt="x-btn">
                                                                <span>{{ $project->exio_suggest_percentage }}%</span>
                                                            </div>
                                                        </div>
                                                        <div class="propertyName">
                                                            <h5>{{ $project->project_name }}</h5>
                                                        </div>
                                                        <div class="locationProperty">
                                                            <div class="homeBox comBox">
                                                                <img src="{{ asset('/') }}frontend/assest/images/Home.png"
                                                                    alt="Home">
                                                                <p>{{ $project->custom_property_type }} |
                                                                    {{ isset($project->floor_plans)? $project->floor_plans->map(function ($plan) {return $plan->carpet_area . ' Sqft';})->join(', '): '' }}
                                                                    | {{ $project->location->location_name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="addressBox">
                                                            <img src="{{ asset('/') }}frontend/assest/images/Home.png"
                                                                alt="Home">
                                                            <p>
                                                                @php
                                                                    $amenities_a = explode(',', $project->amenities);
                                                                    $amenityNames = [];
                                                                    foreach ($amenities_a as $amenityId) {
                                                                        if (isset($amenities[$amenityId])) {
                                                                            $amenityNames[] = $amenities[$amenityId];
                                                                        }
                                                                    }
                                                                    $amenities_a = implode(
                                                                        ', ',
                                                                        array_slice($amenityNames, 0, 4),
                                                                    );
                                                                @endphp
                                                                {{ $amenities_a }}
                                                            </p>
                                                            <a href="javascript:void(0)">more</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade propertyModal" id="propertyModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="modalgallery">
                                <div class="top-img comImg">
                                    <video class="show_video_url" autoplay muted loop>
                                        <source src="" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <div class="multyimg show_gallery_images">
                                    <div class="box comImg">
                                        <img src="{{ asset('/') }}frontend/assest/images/boxImg1.png" alt="boxImg1">
                                    </div>
                                    <div class="box comImg">
                                        <img src="{{ asset('/') }}frontend/assest/images/boxImg2.png" alt="boxImg2">
                                    </div>
                                    <div class="box comImg">
                                        <img src="{{ asset('/') }}frontend/assest/images/boxImg1.png" alt="boxImg1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="modalTextBox">
                                <div class="priceAndshare">
                                    <div class="price">
                                        <h5 class="show_price_from_to"></h5>
                                        <h5 class="show_project_name"></h5>
                                    </div>
                                    <ul>

                                        <li><a href="javascript:void(0)"><i data-id="" id="heartIconFill"
                                                    class="fa-regular fa-heart heartIconFill"></i>Save</a>
                                        </li>
                                        <li><a href="javascript:void(0)" data-bs-toggle="modal"
                                                data-bs-target="#share_property" class="share_property"><i
                                                    class="fa-solid fa-arrow-up-from-bracket"></i>Share</a></li>
                                        <li><button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button></li>
                                    </ul>
                                </div>
                                <div class="locationProperty">
                                    <div class="homeBox comBox">
                                        <img src="{{ asset('/') }}frontend/assest/images/Home.png" alt="Home">
                                        <p class="show_custom_property_type"></p>
                                    </div>
                                    <div class="location comBox">
                                        <img src="{{ asset('/') }}frontend/assest/images/Location.png" alt="Location">
                                        <p class="show_location"></p>
                                    </div>
                                </div>
                                <div class="discriptBox">
                                    <p><strong>Description: </strong><span class="show_description"></span></p>
                                </div>
                                <div class="overViewBox" id="overViewBox">
                                    <div class="overBox">
                                        <span>Total Floors</span>
                                        <h6 class="show_total_floors"></h6>
                                    </div>
                                    <div class="overBox">
                                        <span>Total Tower</span>
                                        <h6 class="show_total_tower"></h6>
                                    </div>
                                    <div class="overBox">
                                        <span>Age of Construction</span>
                                        <h6 class="show_age_of_construction"></h6>
                                    </div>
                                    <div class="overBox">
                                        <span>Property Type</span>
                                        <h6 class="show_property_type"></h6>
                                    </div>

                                </div>
                                <div class="btn-container">
                                    <a class="btn btnWp" id="whatsapplink" target="_blank"
                                        data-whatsapp-number="{{ getSettingFromDb('support_mobile') }}"><img
                                            src="{{ asset('/') }}frontend/assest/images/wpicon.png"
                                            alt="wpicon">Quick
                                        Connect</a>
                                    <a href="" class="btn linkBtn moredetails">More
                                        Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Share_property Modal -->
    <div class="modal fade share_property" id="share_property" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="titlebox">
                        <p>Share Property</p>
                    </div>
                    <div class="iconBox">
                        <ul>
                            <li>
                                <a href="javascript:void(0)" id="whatsapp-link" class="social_media_share"><i
                                        class="fa-brands fa-whatsapp"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" id="facebook-link" class="social_media_share"><i
                                        class="fa-brands fa-facebook-f"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" id="twitter-link" class="social_media_share"><i
                                        class="fa-brands fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" id="linkedin-link" class="social_media_share"><i
                                        class="fa-brands fa-linkedin"></i></a>
                            </li>
                            {{-- <li>
                                    <a href="javascript:void(0)" id="email-link" class="social_media_share"><i class="fa-brands fa-email"></i></a>
                                </li> --}}
                        </ul>
                    </div>
                    <div class="input-group">
                        <input type="text" id="copy-link" class="form-control" aria-describedby="basic-addon2"
                            disabled>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button"
                                onClick="copyToClipboard()">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.include.compare')
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $(".heartIconFill").click(function() {
                $(this).toggleClass("fa-regular fa-solid");
            });
        });
        var projects = {!! json_encode($projects) !!};
        var map_pin = "{{ asset('frontend/assest/images/map-pin.png') }}";
        var priceUnit = @json($priceUnit);
        var assetUrl = "{{ asset('') }}";
        var propertySubTypes = @json($property_sub_types);
        var singleProjectUrl = "{{ route('property.getSingleProjectData') }}";
        var propertyDetailsUrl = "{{ route('property.details', ['slug' => ':slug']) }}";
        var getComparePropertyUrl = "{{ route('property.compare') }}";
        var comparePropertytUrl = "{{ route('property.comparepage') }}";
        var baseUrl = "{{ asset('/') }}frontend/";
    </script>
    <script>
        $(document).ready(function() {
            var allProjectsUrl = "{{ route('property.getProjectData') }}";
            var allAppraisalUrl = "{{ route('property.getAppraisalData') }}";
            var allBestMatchUrl = "{{ route('property.getBestMatchData') }}";

            var page = 1;
            var lastPage = false;
            var isLoading = false;

            var appraisalPage = 1;
            var appraisalLastPage = false;
            var isAppraisalLoading = false;

            var bestMatchPage = 1;
            var bestMatchLastPage = false;
            var isBestMatchLoading = false;

            function renderProject(project) {
                let authId = @json(auth()->id());
                //check if the project is already in the wishlist
                var isWishlisted = project.wishlisted_by_users
                    .some(function(user) {
                        return user.id === authId;
                    });
                var html = '<div class="col-md-6">';
                html += '<div class="propertyCard" data-id="' + project.id +
                    '">';
                html += '<div class="imgBox">';
                html += '<img src="' + assetUrl + 'frontend/assest/images/property-img.png" alt="property-img">';
                html +=
                    '<div class="imgheader"><span>Best for Investment</span><i data-id="' + project.id +
                    '" class="' + (isWishlisted ? 'fa-solid' : 'fa-regular') +
                    ' fa-heart heartIconFill"></i></div>';
                html += '</div>';
                html += '<div class="priceBox">';
                html += '<div class="price"><h5>₹' + project.price_from + 'L-' + project.price_to + 'Cr</h5></div>';
                html += '<div class="boxLogo"><img src="' + assetUrl +
                    'frontend/assest/images/x-btn.png" alt="x-btn"><span>' + (project.exio_suggest_percentage ||
                        0) + '%</span></div>';
                html += '</div>';
                html += '<div class="propertyName"><h5>' + project.project_name + '</h5></div>';
                html += '<div class="locationProperty">';
                html += '<div class="homeBox comBox">';
                html += '<img src="' + assetUrl + 'frontend/assest/images/Home.png" alt="Home">';
                var floorPlans = "";
                if (project.floor_plans && project.floor_plans.length) {
                    floorPlans = project.floor_plans.map(function(plan) {
                        return plan.carpet_area + " Sqft";
                    }).join(", ");
                }
                html += '<p>' + (project.custom_property_type || '') + ' | ' + floorPlans + ' | ' + (project
                    .location ? project.location.location_name : '') + '</p>';
                html += '</div>';
                html += '</div>';
                html += '<div class="addressBox">';
                html += '<img src="' + assetUrl + 'frontend/assest/images/Home.png" alt="Home">';
                html += '<p>Lift, Gym, Park, Club House Lift, Gym, Park, Club House</p>';
                html += '<a href="javascript:void(0)">more</a>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                return html;
            }

            function loadMoreProjects() {
                if (isLoading || lastPage) return;
                isLoading = true;
                page++;

                var city = $("#city_header").val();
                var search = $("#filter_search").val();
                let propertyType = $('[name="property_type"]:checked').val();
                let subTypes_o = $('[name="property_sub_types[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                let bhk = $('[name="bhk[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                let amenities = $('[name="amenities[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                let minPrice = $('#slider-min').val();
                let maxPrice = $('#slider-max').val();

                $.ajax({
                    url: allProjectsUrl,
                    type: "GET",
                    data: {
                        page: page,
                        city: city,
                        search: search,
                        property_type: propertyType,
                        property_sub_types: subTypes_o,
                        bhk: bhk,
                        amenities: amenities,
                        minPrice: minPrice,
                        maxPrice: maxPrice
                    },
                    success: function(response) {
                        if (response.status && response.data.data.length) {
                            var newProjects = response.data.data;
                            if (page == 1) {
                                projects = [];
                            }
                            projects = projects.concat(newProjects);
                            @if (getDeviceType() == 'desktop')
                                initMap();
                            @endif
                            newProjects.forEach(function(proj) {
                                $('#pills-home .row').append(renderProject(proj));
                            });
                            if (response.data.current_page >= response.data.last_page) {
                                lastPage = true;
                            }
                        } else {
                            lastPage = true;
                        }
                    },
                    error: function() {},
                    complete: function() {
                        isLoading = false;
                    }
                });
            }

            function loadMoreAppraisal() {
                if (isAppraisalLoading || appraisalLastPage) return;
                isAppraisalLoading = true;
                appraisalPage++;
                $.ajax({
                    url: allAppraisalUrl,
                    type: "GET",
                    data: {
                        page: appraisalPage
                    },
                    success: function(response) {
                        if (response.status && response.data.data.length) {
                            var newProjects = response.data.data;
                            newProjects.forEach(function(proj) {
                                $('#pills-profile .row').append(renderProject(proj));
                            });
                            if (response.data.current_page >= response.data.last_page) {
                                appraisalLastPage = true;
                            }
                        } else {
                            appraisalLastPage = true;
                        }
                    },
                    error: function() {},
                    complete: function() {
                        isAppraisalLoading = false;
                    }
                });
            }

            function loadMoreBestMatch() {
                if (isBestMatchLoading || bestMatchLastPage) return;
                isBestMatchLoading = true;
                bestMatchPage++;
                $.ajax({
                    url: allBestMatchUrl,
                    type: "GET",
                    data: {
                        page: bestMatchPage
                    },
                    success: function(response) {
                        if (response.status && response.data.data.length) {
                            var newProjects = response.data.data;
                            newProjects.forEach(function(proj) {
                                $('#pills-match .row').append(renderProject(proj));
                            });
                            if (response.data.current_page >= response.data.last_page) {
                                bestMatchLastPage = true;
                            }
                        } else {
                            bestMatchLastPage = true;
                        }
                    },
                    error: function() {},
                    complete: function() {
                        isBestMatchLoading = false;
                    }
                });
            }

            @if (getDeviceType() == 'desktop')
                $('.tab-content').on('scroll', function() {
                    var container = $(this);
                    if ($('#pills-home').hasClass('active')) {
                        if (container.scrollTop() + container.innerHeight() >= container[0].scrollHeight -
                            50) {
                            loadMoreProjects();
                        }
                    } else if ($('#pills-profile').hasClass('active')) {
                        if (container.scrollTop() + container.innerHeight() >= container[0].scrollHeight -
                            50) {
                            loadMoreAppraisal();
                        }
                    } else if ($('#pills-match').hasClass('active')) {
                        if (container.scrollTop() + container.innerHeight() >= container[0].scrollHeight -
                            50) {
                            loadMoreBestMatch();
                        }
                    }
                });
            @endif

            @if (getDeviceType() == 'mobile')
                $(window).on('scroll', function() {
                    if ($('#pills-home').hasClass('active')) {
                        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 900) {
                            loadMoreProjects();
                        }
                    } else if ($('#pills-profile').hasClass('active')) {
                        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 900) {
                            loadMoreAppraisal();
                        }
                    } else if ($('#pills-match').hasClass('active')) {
                        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 900) {
                            loadMoreBestMatch();
                        }
                    }
                });
            @endif

            $('#applyFilter').click(function() {
                page = 0;
                lastPage = false;
                $('#pills-home .row').empty();
                loadMoreProjects();
            });

            $('.city_click_header').click(function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                $('#city_header').val(id);
                $('#city_header_name').text(name);
                $('a.cityClick i').toggleClass('rotate');

                page = 0;
                lastPage = false;
                $('#pills-home .row').empty();
                loadMoreProjects();
            });

            $('#clear_search').click(function() {
                page = 0;
                lastPage = false;
                $('#pills-home .row').empty();
                loadMoreProjects();
            });
        });
    </script>
    <script src="{{ $baseUrl }}/assest/js/pages/result-filter.js"></script>
    @if (getDeviceType() == 'desktop')
        <script src="{{ $baseUrl }}/assest/js/pages/map_view_filter_page.js"></script>
    @endif

    <script src="{{ $baseUrl }}/assest/js/pages/compare.js"></script>
@endsection
