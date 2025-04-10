@php
    use App\Models\Project;
    $baseUrl = asset('/') . 'frontend/';
    $activeTab = request('tab', 'all'); // Default to "all"
@endphp
@extends('frontend.layouts.app')

@section('css')
    @include('frontend.include.google-maps')
@endsection

@section('content')
    <section class="checkProperty">
        <div class="container">
            <div class="checkPropertyBox">
                <div class="main_property">
                    <div class="left_propertyBox">
                       
                        <div class="topFilterBar">

                            <div class="searchBar searchKeyup">
                                <input type="search" class="clickList" name="filter_search" id="filter_search" value="" placeholder="Locality, Project, or Builder">
                                @include('frontend.include.common-html-mobile-web', ['type' => 'search-key'])
                               
                                <a href="javascript:void(0)" id="clear_search" class="iconClick">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>                                
                            </div>
                            <div class="filterBox">
                                <a href="javascript:void(0)" id="search_btn_desktop" class="btn btnIcon desktop-search"><i class="bi bi-funnel"></i></a>                                
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
                                                            value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}>
                                                        <label for=""
                                                            class="keyword-label">{{ $type }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- sub types -->
                                    <div class="comViewBox second">
                                        <p class="d-none show-sub-type">It's a...</p>
                                        <div class="feetSelectBox" id="property-sub-type">

                                        </div>
                                    </div>

                                    <!-- BHK -->
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

                                    <!-- Amenities -->
                                    <div class="comViewBox four">
                                        <p>Amenities</p>
                                        <div class="feetSelectBox">
                                            @if ($amenities->count() > 0)
                                                @foreach ($amenities->slice(0, 7) as $key => $amenity)
                                                    <div class="clickTo amenity-item" 
                                                    data-property-type="{{ $amenity->amenity_type }}">
                                                        <label class="checkbox">
                                                            <input class="checkbox__input" type="checkbox"
                                                                id="amenities_{{ $amenity->id }}" name="amenities[]"
                                                                value="{{ $amenity->id }}" />
                                                            <span class="checkbox__label">{{ $amenity->amenity_name }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                                @foreach ($amenities->slice(7) as $key => $amenity)
                                                    <div class="clickTo hidden-amenity d-none amenity-item"
                                                    data-property-type="{{ $amenity->amenity_type }}">
                                                        <label class="checkbox">
                                                            <input class="checkbox__input" type="checkbox"
                                                                id="amenities_{{ $amenity->id }}" name="amenities[]"
                                                                value="{{ $amenity->id }}" />
                                                            <span class="checkbox__label">{{ $amenity->amenity_name }}</span>
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
                                                aria-valuemin="1" aria-valuemax="100" data-display="{{ formatBudget($minMaxPrice['min_price']) }}">
                                            <input type="range" min="{{ $minMaxPrice['min_price'] ?? '' }}"
                                                max="{{ $minMaxPrice['max_price'] ?? '' }}"
                                                value="{{ $minMaxPrice['max_price'] ?? '' }}" id="slider-max"
                                                aria-valuemin="1" aria-valuemax="100"  data-display="{{ formatBudget($minMaxPrice['max_price']) }}">
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

                                    <div class="comViewBox resstBtn">
                                        <button class="btn linkBtn" id="resetFilter">Reset</button>
                                        <button class="btn linkBtn" id="applyFilter">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div id="project-list" class="right_propertyBox">
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
                                            @if($projects->count() > 0)
                                                @foreach ($projects as $project)
                                                    <x-property-card :project="$project" :amenities="$amenities" />
                                                @endforeach
                                            @else
                                                <p class="not-found">No property found</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                        aria-labelledby="pills-profile-tab">
                                        <div class="row">
                                            @if($appraisal->count() > 0)
                                                @foreach ($appraisal as $project)
                                                    <x-property-card :project="$project" :amenities="$amenities" />
                                                @endforeach
                                            @else
                                                <p class="not-found">No property found</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-match" role="tabpanel"
                                        aria-labelledby="pills-match-tab">
                                        <div class="row">
                                            @if($bestMatch->count() > 0)
                                                @foreach ($bestMatch as $project)
                                                    <x-property-card :project="$project" :amenities="$amenities" />
                                                @endforeach
                                            @else
                                                <p class="not-found">No property found</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div id="project-list" class="right_propertyBox">
                        <div class="rightListSec">
                            <div class="toptabSec">
                                <div class="tabflex">
                                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $activeTab == 'all' ? 'active' : '' }}" 
                                                id="pills-home-tab" data-bs-toggle="pill" 
                                                data-bs-target="#pills-home" type="button" role="tab"
                                                aria-controls="pills-home" aria-selected="{{ $activeTab == 'all' ? 'true' : 'false' }}">
                                                <i class="fa-solid fa-layer-group"></i> All
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $activeTab == 'appraisal' ? 'active' : '' }}" 
                                                id="pills-profile-tab" data-bs-toggle="pill" 
                                                data-bs-target="#pills-profile" type="button" role="tab"
                                                aria-controls="pills-profile" aria-selected="{{ $activeTab == 'appraisal' ? 'true' : 'false' }}">
                                                <i class="fa-solid fa-thumbs-up"></i> Appraisal
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $activeTab == 'best-match' ? 'active' : '' }}" 
                                                id="pills-match-tab" data-bs-toggle="pill" 
                                                data-bs-target="#pills-match" type="button" role="tab"
                                                aria-controls="pills-match" aria-selected="{{ $activeTab == 'best-match' ? 'true' : 'false' }}">
                                                <i class="fa-solid fa-puzzle-piece"></i> Best Match
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="heartBox">
                                        <a href="{{ route('property.shortlisted') }}">
                                            <i class="fa-solid fa-heart"></i>
                                            <span>{{ number_format($shortlistedCount) }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade {{ $activeTab == 'all' ? 'show active' : '' }}" id="pills-home" role="tabpanel">
                                    <div class="row rg-20">
                                        @if($projects->count() > 0)
                                            @foreach ($projects as $project)
                                                <x-property-card :project="$project" :amenities="$amenities" />
                                            @endforeach
                                        @else
                                            <p class="not-found">No property found</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane fade {{ $activeTab == 'appraisal' ? 'show active' : '' }}" id="pills-profile" role="tabpanel">
                                    <div class="row rg-20">
                                        @if($appraisal->count() > 0)
                                            @foreach ($appraisal as $project)
                                                <x-property-card :project="$project" :amenities="$amenities" />
                                            @endforeach
                                        @else
                                            <p class="not-found">No property found</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane fade {{ $activeTab == 'best-match' ? 'show active' : '' }}" id="pills-match" role="tabpanel">
                                    <div class="row rg-20">
                                        @if($bestMatch->count() > 0)
                                            @foreach ($bestMatch as $project)
                                                <x-property-card :project="$project" :amenities="$amenities" />
                                            @endforeach
                                        @else
                                            <p class="not-found">No property found</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- compare project section -->
    @include('frontend.include.compare')
    <!-- compare project section -->

    <!-- propertyModal -->
    @include('frontend.include.property_detail_modal')
    <!-- propertyModal -->

    <!-- Share_property Modal -->
    @include('frontend.include.share_property_modal')
    <!-- Share_property Modal -->
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
        var getPropertyDetailsUrl = "{{ route('property.details', ['_slug_']) }}";
        var getComparePropertyUrl = "{{ route('property.compare') }}";
        var comparePropertytUrl = "{{ route('property.comparepage') }}";
        var baseUrl = "{{ $baseUrl }}";
        var projectImageUrl = "{{ asset('/') }}storage/project_images/";
        // var amenities = @json($amenities);
        var amenities = @json($allAmenities);
        var deviceType = "{{ getDeviceType() }}";
        
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

        var ageOfConstruction = @json(Project::$ageOfConstruction);
        var propertyTypeData = @json(Project::$propertyType);
        var getSettingFromDb = "{{ getSettingFromDb('support_mobile') }}";
        var priceUnit = @json($priceUnit);
    </script>
    <script src="{{ frontendPageJsLink('result-filter.js') }}"></script>
    <script src="{{ frontendPageJsLink('compare.js') }}"></script>
    @if (getDeviceType() == 'desktop')
        <script src="{{ frontendPageJsLink('map_view_filter_page.js') }}"></script>
    @endif
    <script src="{{ frontendPageJsLink('loadmore-result-filter.js') }}"></script>
    <script src="{{ frontendPageJsLink('property-card.js') }}"></script>
@endsection
