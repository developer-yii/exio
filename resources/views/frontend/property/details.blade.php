@php
    $baseUrl = asset('frontend') . '/';
    $metaTitle = "Exio | " . $project->project_name;
    $metaDesc = $project->project_name. " by " . $project->builder->builder_name;
@endphp
@extends('frontend.layouts.app')

@section('title', 'Property Details')
{{-- @section('meta_details')
    @include('frontend.include.meta', ['title' => $metaTitle, 'description' => $metaDesc])
@endsection --}}
    @section('og_title', $metaTitle)
    @section('og_description', $metaDesc)
    @section('og_image', asset($project->getCoverImageUrl()))
    @section('og_url', url()->current())

@section('content')
    <!-- details banner -->
    <section class="detail_Sec">
        <div class="container">
            <div class="detail_Box">
                <div class="menuBread">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('property.result.filter') }}">Listing</a></li>
                            <li class="breadcrumb-item" aria-current="page">{{ $project->project_name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="detailmainSec">
                    <div class="imageGallery">
                        <div class="leftVideo">
                            <div class="videoCoverImage">
                                <video no-controls id="myVideo">
                                    <source src="{{ $project->getVideoUrl() }}">
                                </video>
                                <div class="playIcon" id="playIcon">
                                    <a href="javascript:void(0)"><img src="{{ $baseUrl }}assest/images/playBtn.png" alt="playBtn" loading="lazy"></a>
                                </div>
                            </div>

                            @if ($project->projectImages->count() > 4)
                                <div class="moreBnt mobile">
                                    <a href="javascript:void(0)" class="lightBoximg">
                                        {{ $project->projectImages->count() - 4 }}+ more Photos
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="rightImg">
                            @foreach ($project->projectImages->take(4) as $projImg)
                                @if ($loop->last && $project->projectImages->count() > 4)
                                    <div class="boxImg fourBox">
                                        <a href="{{ $projImg->getProjectImageUrl() }}" data-fancybox="gallery">
                                            <img src="{{ $projImg->getProjectImageUrl() }}" alt="Property Images" loading="lazy">
                                        </a>
                                        <div class="moreBnt">
                                            <a href="javascript:void(0)" class="lightBoximg">{{ $project->projectImages->count() - 4 }}+ more
                                                Photos</a>
                                        </div>
                                    </div>
                                @else
                                    <div class="boxImg">
                                        <a href="{{ $projImg->getProjectImageUrl() }}" data-fancybox="gallery">
                                            <img src="{{ $projImg->getProjectImageUrl() }}" alt="Project Image" loading="lazy">
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                            @foreach ($project->projectImages->skip(4) as $projImg)
                                <a href="{{ $projImg->getProjectImageUrl() }}" data-fancybox="gallery" style="display: none;"></a>
                            @endforeach
                        </div>
                    </div>
                    @include('frontend.include.common-html-mobile-web', ['type' => 'project-builder-section'])
                </div>
            </div>
        </div>
    </section>
    <!-- details banner -->

    <!-- propert overview section -->
    <div class="overview">
        @if (getDeviceType() == 'desktop')
            <div class="stickyTabpanel desktop">
                <div class="container">
                    <ul>
                        <li><a class="active" href="#overView">Overview</a></li>
                        <li><a href="#master_plan">Master Plan</a></li>
                        <li><a href="#floor_paln">Floor Plan</a></li>
                        @if(count($amenitiesList) > 0)
                            <li><a href="#amenites">Amenities</a></li>
                        @endif
                        @if($project->property_document)
                            <li><a href="#propert_document">Brochure</a></li>
                        @endif
                        <li><a href="#locality">Locality</a></li>
                        <li><a href="#documents">Documents</a></li>
                        <li><a href="#map_view">Map</a></li>
                        <li><a href="#developers">Developer</a></li>
                    </ul>
                </div>
            </div>
        @endif
        <div class="overViewSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        @if (getDeviceType() == 'desktop')
                            <div class="leftScrollSection desktop">

                                <div id="overView">
                                    @include('frontend.include.common-html-mobile-web', ['type' => 'overviewBox'])
                                    @include('frontend.include.common-html-mobile-web', ['type' => 'project-details'])
                                </div>

                                {{-- master plan web view --}}
                                <div id="master_plan">
                                    <div class="materHeader">
                                        <div class="tabBox">
                                            <nav>
                                                <h5>Master Plan</h5>
                                                @include('frontend.include.common-html-mobile-web', ['type' => 'master-plan-2d-3d-section'])
                                            </nav>
                                            <div class="newMaster">
                                                <div class="viewText">
                                                    <ul id="masterPlanList" data-section="web">
                                                        @foreach ($project->masterPlans as $index => $masterPlan)
                                                            <li class="cursor-pointer {{ $index === 0 ? 'active' : '' }}"
                                                                data-index="{{ $index }}"
                                                                data-2dImage="{{ $masterPlan->get2DImageUrl() }}"
                                                                data-3dImage="{{ $masterPlan->get3DImageUrl() }}"
                                                            >
                                                                <h6>{{ $masterPlan->name }}</h6>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                @include('frontend.include.common-html-mobile-web', ['type' => 'master-plan-2d-3d-image'])

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="floor_paln">
                                    <div class="floorPlan">
                                        <div class="tabBox">
                                            <nav>
                                                <div class="title">
                                                    <h5>Floor Plan</h5>
                                                    <p>{{ $project->floorPlans->count() }} Floor Plans Available</p>
                                                </div>
                                                @include('frontend.include.common-html-mobile-web', ['type' => 'floor-plan-2d-3d-section'])
                                            </nav>
                                            @include('frontend.include.common-html-mobile-web', ['type' => 'floor-plan-2d-3d-image'])
                                        </div>
                                    </div>
                                </div>

                                @if(count($amenitiesList) > 0)
                                    <div id="amenites">
                                        <div class="amenitesBox">
                                            <div class="title">
                                                <h5>Amenities</h5>
                                                <p>Total {{ count($amenitiesList) }}+ Amenities</p>
                                            </div>
                                            <div class="amenitiesItem">
                                                @include('frontend.include.common-html-mobile-web', ['type' => 'amenities', 'page' => 'project-detail', 'showAmenities' => 23])
                                                @include('frontend.include.common-html-mobile-web', ['type' => 'amenities-more-button', 'page' => 'project-detail', 'showAmenities' => 23])
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($project->property_document)
                                    <div id="propert_document">
                                        <div class="title">
                                            <h5>Property Documents</h5>
                                        </div>
                                        @include('frontend.include.common-html-mobile-web', ['type' => 'download-brochure'])
                                    </div>
                                @endif

                                <div id="locality">
                                    <div class="title">
                                        <h5>Locality</h5>
                                    </div>
                                    <div class="localityBox">
                                        @include('frontend.include.common-html-mobile-web', ['type' => 'localities', 'page' => 'property-details'])
                                        @include('frontend.include.common-html-mobile-web', ['type' => 'localities-more-button'])
                                    </div>
                                </div>

                                <div id="documents">
                                    <div class="title">
                                        <h5>RERA Details</h5>
                                    </div>
                                    <div class="documentBox">
                                        @include('frontend.include.common-html-mobile-web', ['type' => 'rera-document'])
                                    </div>
                                </div>

                                <div id="map_view">
                                    <div class="title">
                                        <h5>Map View</h5>
                                    </div>
                                    <div class="mapMianbox">
                                        @include('frontend.include.common-html-mobile-web', ['type' => 'map'])
                                    </div>
                                    @include('frontend.include.common-html-mobile-web', ['type' => 'about'])
                                </div>
                            </div>
                        @endif
                        @if (getDeviceType() == 'mobile')
                            <div class="leftScrollSection mobile">
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    {{-- overview --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                aria-expanded="false" aria-controls="flush-collapseOne">
                                                Overview
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div id="overView">
                                                    @include('frontend.include.common-html-mobile-web', ['type' => 'overviewBox'])
                                                    @include('frontend.include.common-html-mobile-web', ['type' => 'project-details'])
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    {{-- master-plan mobile view --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                aria-expanded="false" aria-controls="flush-collapseTwo">
                                                Master Plan
                                            </button>
                                        </h2>
                                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div id="master_plan">
                                                    <div class="materHeader">
                                                        <div class="tabBox">
                                                            <nav>
                                                                <!-- <h5>Master Plan</h5> -->
                                                                @include('frontend.include.common-html-mobile-web', ['type' => 'master-plan-2d-3d-section'])
                                                            </nav>
                                                            <div class="newMaster">
                                                                <div class="viewText">
                                                                    <ul id="master_plan">
                                                                        <li>
                                                                            @foreach ($project->masterPlans->take(1) as $index => $masterPlan)
                                                                                <a class="masterClick" href="javascript:void(0)">
                                                                                    <div>
                                                                                        <span>Plan</span>
                                                                                        <h6>{{$masterPlan->name}}</h6>
                                                                                    </div>
                                                                                    <i class="fa-solid fa-chevron-down"></i>
                                                                                </a>
                                                                            @endforeach
                                                                            <ul class="dataDropdown" id="masterPlanList" data-section="mobile">
                                                                                <li>
                                                                                    @foreach ($project->masterPlans as $index => $masterPlan)
                                                                                        <a href="javascript:void(0)" data-index="{{ $index }}" data-2dImage="{{$masterPlan->get2DImageUrl()}}" data-3dImage="{{$masterPlan->get3DImageUrl()}}">
                                                                                            <span>Plan</span>
                                                                                            <h6>{{$masterPlan->name}}</h6>
                                                                                        </a>
                                                                                    @endforeach
                                                                                </li>
                                                                            </ul>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                @include('frontend.include.common-html-mobile-web', ['type' => 'master-plan-2d-3d-image'])
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Floor plan --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingThree">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                                aria-expanded="false" aria-controls="flush-collapseThree">
                                                Floor Plan
                                            </button>
                                        </h2>
                                        <div id="flush-collapseThree" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div id="floor_paln">
                                                    <div class="floorPlan">
                                                        <div class="tabBox">
                                                            <nav>
                                                                @include('frontend.include.common-html-mobile-web', ['type' => 'floor-plan-2d-3d-section'])
                                                            </nav>
                                                            @include('frontend.include.common-html-mobile-web', ['type' => 'floor-plan-2d-3d-image'])
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- amenities --}}
                                    @if(count($amenitiesList) > 0)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingFour">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                                    Amenities
                                                </button>
                                            </h2>
                                            <div id="flush-collapseFour" class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <div id="amenites">
                                                        <div class="amenitesBox">
                                                            <div class="title">
                                                                <p>Total {{ count($amenitiesList) }}+ Amenities</p>
                                                            </div>
                                                            <div class="amenitiesItem">
                                                                @include('frontend.include.common-html-mobile-web', ['type' => 'amenities', 'page' => 'project-detail', 'showAmenities' => 23])
                                                            </div>
                                                                @include('frontend.include.common-html-mobile-web', ['type' => 'amenities-more-button', 'page' => 'project-detail', 'showAmenities' => 23])
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Property Documents --}}
                                    @if($project->property_document)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingFive">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseFive"
                                                    aria-expanded="false" aria-controls="flush-collapseFive">
                                                    Brochure
                                                </button>
                                            </h2>
                                            <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <div id="propert_document">
                                                        @include('frontend.include.common-html-mobile-web', ['type' => 'download-brochure'])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Locality --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingSix">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                                                Locality
                                            </button>
                                        </h2>
                                        <div id="flush-collapseSix" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div id="locality">
                                                    <div class="localityBox">
                                                        @include('frontend.include.common-html-mobile-web', ['type' => 'localities', 'page' => 'property-details'])
                                                    </div>
                                                        @include('frontend.include.common-html-mobile-web', ['type' => 'localities-more-button'])
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Rera Documents --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingSeven">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
                                                Documents
                                            </button>
                                        </h2>
                                        <div id="flush-collapseSeven" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingSeven" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div id="documents">
                                                    <div class="documentBox">
                                                        @include('frontend.include.common-html-mobile-web', ['type' => 'rera-document'])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- map --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingEight">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight">
                                                Map
                                            </button>
                                        </h2>
                                        <div id="flush-collapseEight" class="accordion-collapse collapse" aria-labelledby="flush-headingEight" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div id="map_view">
                                                    <div class="mapMianbox">
                                                        @include('frontend.include.common-html-mobile-web', ['type' => 'map'])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- developer --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingNine">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine">
                                                Developer
                                            </button>
                                        </h2>
                                        <div id="flush-collapseNine" class="accordion-collapse collapse" aria-labelledby="flush-headingNine" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                @include('frontend.include.common-html-mobile-web', ['type' => 'about'])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @include('frontend.include.exio-suggest')
                </div>
            </div>
        </div>
        <div id="developers">
            <div class="container">
                <div class="title">
                    <h4>Similar Properties</h4>
                </div>
                @if($similarProperties->count() > 0 )
                    @if (getDeviceType() == 'desktop')
                        <div class="developersProprty webViewSection">
                            <div class="row">
                                @foreach ($similarProperties as $similarProperty)
                                    <div class="col-lg-4 col-md-6">
                                        @include('frontend.include.common-html-mobile-web', ['type' => 'similar-property'])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if (getDeviceType() == 'mobile')
                        <div class="developersProprty mobileViewSection">
                            <div class="owl-carousel owl-theme">
                                @foreach ($similarProperties as $similarProperty)
                                    <div class="item">
                                        @include('frontend.include.common-html-mobile-web', ['type' => 'similar-property'])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <p style="height: 100px; margin-top:10px;">Property not found</p>
                @endif
            </div>
        </div>
    </div>
    <!-- propert overview section -->

@endsection
@section('modal')
    <!-- imageModal Modal -->
    <div class="modal fade imageLightBox" id="imageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="img_view_section">
                        <img src="" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- imageModal Modal -->

    <!-- downloadBrochure Modal -->
    <div class="modal fade downloadBrochure" id="downloadBrochure" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="docHeader">
                        <h5>Download Brochure of</h5>
                        <div class="glorybox">
                            <div class="gloryImg">
                                <img src="{{ $project->builder->getBuilderLogoUrl() }}"
                                    alt="{{ $project->builder->builder_name }}">
                            </div>
                            <div class="gloryText">
                                <h6>{{ $project->project_name }}</h6>
                                <p>By {{ $project->builder->builder_name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="brochureBox">
                        <p>Please share below details</p>
                        <form id="downloadBrochureForm" autocomplete="on" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
                                <span class="error"></span>
                            </div>
                            <div class="form-group">
                                <label class="labelClass" for="name">Full Name<span>*</span></label>
                                <input class="inputClass name" type="text" name="name" id="name" placeholder="John Deo" autocomplete="name" autofocus>
                                <div id="autocomplete-list" class="autocomplete-list"></div>
                                <span class="error"></span>
                            </div>
                            <div class="form-group">
                                <label class="labelClass" for="phone_number">Phone Number<span>*</span></label>
                                <input class="inputClass phone_number" type="tel" name="phone_number" id="phone_number" placeholder="98989 89898" autocomplete="tel">
                                <span class="error"></span>
                            </div>
                            <div class="form-group">
                                <label class="labelClass" for="email">Email Address<span>*</span></label>
                                <input class="inputClass email" type="email" name="email" id="brochure_email" placeholder="johndeo@gmail.com" autocomplete="email">
                                <span class="error"></span>
                            </div>
                            <div class="btnDown">
                                <button type="submit" class="btn btnDownload">Download</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>   

    <!-- downloadBrochure Modal -->

    <!-- Share_property Modal -->
    @include('frontend.include.share_property_modal')

@endsection
@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('constants.google_maps_api_key') }}&callback=initMap" async defer></script>
    <script>
        var latitude = {{ $project->latitude }};
        var longitude = {{ $project->longitude }};
        var downloadBrochureUrl = "{{ route('property.download-brochure-form') }}";
        var baseUrl = "{{ $baseUrl }}";
    </script>
    <script src="{{ frontendPageJsLink('property-details.js') }}"></script>
@endsection
