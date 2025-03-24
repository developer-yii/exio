@php
    $baseUrl = asset('frontend') . '/';
@endphp
@section('title', 'Compare Project')
@extends('frontend.layouts.app')
@section('content')
<section class="bannerSky">
    <div class="container">
        <div class="bannerSkyText">
            <h4>Compare Project</h4>
            <p>Lorem Ipsum is simply dummy text of the printing and  typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever.</p>
        </div>
    </div>
 </section>

 <section class="compare_project">
    <div class="container">
        <div class="compare_projectBox">

            {{-- Property section --}}
            <div class="compareComBox">
                <div class="leftBoxSmall">
                    <div class="lableBox">
                        <h5>Property</h5>
                    </div>
                </div>
                @foreach($properties as $project)
                    <div class="detailSameBox skyBg">
                        <div class="borderBox">
                            @if(getDeviceType() == 'desktop')
                                <div class="desktop">
                                    @include('frontend.property.partial_compare_section_mobile', ['property' => $project])
                                </div>
                            @endif

                            @if(getDeviceType() == 'mobile')
                                <div class="mobile">
                                    <div class="siteDetails">
                                        <div class="logoMain">
                                            <img src="{{ $project->builder->getBuilderLogoUrl() }}" loading="lazy" alt="{{ $project->builder->builder_name }}" title="{{ $project->builder->builder_name }}">
                                        </div>
                                        <div class="textBox">
                                            <h5  class="one-line-text" title="{{ $project->project_name }}">{{ $project->project_name }}</h5>
                                            <span>By {{ $project->builder->builder_name }}</span>
                                        </div>
                                    </div>
                                    <div class="locationProperty">
                                        <div class="homeBox comBox">
                                            <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home">
                                            <p class="one-line-text" title="{{ $project->custom_property_type ?? '' }}">{{ $project->custom_property_type ?? '' }}</p>
                                        </div>
                                        <div class="location comBox">
                                            <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location">
                                            <p class="one-line-text" title="{{ $project->location->location_name . ', ' . $project->city->city_name }}">{{ $project->location->location_name . ', ' . $project->city->city_name }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="priceBox">
                                <p><i class="fa-regular fa-calendar"></i> Possession by {{ getFormatedDate($project->possession_by, 'M, Y') }}</p>
                                <h5>
                                    {!! formatPriceRange($project->price_from, $project->price_from_unit, $project->price_to, $project->price_to_unit) !!}
                                </h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Exio suggest section --}}
            <div class="compareComBox">
                <div class="leftBoxSmall">
                    <div class="lableBox">
                        <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn">
                    </div>
                </div>
                @foreach($properties as $project)
                    <div class="detailSameBox">
                        <div class="borderBox">
                            <div class="reportTitle">
                                <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn">
                                <span>{{ $project->exio_suggest_percentage }}%</span>
                            </div>
                            @foreach(['Amenities' => 'amenities_percentage', 'Project Plan' => 'project_plan_percentage', 'Locality' => 'locality_percentage', 'Return of Investment' => 'return_of_investment_percentage'] as $title => $field)
                                <div class="progressBox">
                                    <div class="topBar">
                                        <h5>{{ $title }}</h5>
                                        <span>{{ $project->$field }}%</span>
                                    </div>
                                    <div class="barBox">
                                        <div class="progress">
                                            {!! renderProgressBar($project->$field) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Project Description section --}}
            <div class="compareComBox">
                <div class="leftBoxSmall">
                    <div class="lableBox">
                        <h5>Project Description</h5>
                    </div>
                </div>
                @foreach($properties as $project)
                    <div class="detailSameBox">
                        <div class="borderBox">
                            <div class="discriptP">
                                <p>{!! $project->project_about !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Overview section --}}
            <div class="compareComBox">
                <div class="leftBoxSmall">
                    <div class="lableBox">
                        <h5>Overview</h5>
                    </div>
                </div>
                @foreach($properties as $project)
                    <div class="detailSameBox">
                        <div class="borderBox">
                            @include('frontend.include.common-html-mobile-web', ['type' => 'overviewBox'])
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="compareComBox">
                <div class="leftBoxSmall">
                    <div class="lableBox">
                        <h5>Flore Plan</h5>
                    </div>
                </div>
                @foreach($properties as $project)
                    <div class="detailSameBox">
                        <div class="borderBox">
                            <p class="titleSlide">{{ $project->floorPlans->count() }} Floor Plans Available</p>
                            <div class="owl-carousel owl-theme">
                                @foreach($project->floorPlans as $floorPlan)
                                    <div class="item">
                                        <div class="imgBox">
                                            <img src="{{ $floorPlan->get2DImageUrl() }}" alt="{{ $floorPlan->type }}"  loading="lazy">
                                        </div>
                                        <div class="textSlider">
                                            <div class="imgText">
                                                <span>Carpet Area</span>
                                                <h6>{{ $floorPlan->carpet_area }}sqft</h6>
                                            </div>
                                            <div class="imgText">
                                                <span>Type</span>
                                                <h6>{{ $floorPlan->type }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Amenities section --}}
            <div class="compareComBox">
                <div class="leftBoxSmall">
                    <div class="lableBox">
                        <h5>Amenities</h5>
                    </div>
                </div>
                @foreach($properties as $project)
                    <div class="detailSameBox">
                        <div class="borderBox">
                            <p class="titleSlide">{{ count($project->amenitiesList) }} Amenities</p>
                            <div class="amenitiesItem">
                                @include('frontend.include.common-html-mobile-web', ['type' => 'amenities', 'page' => 'compare', 'showAmenities' => 11])
                                @include('frontend.include.common-html-mobile-web', ['type' => 'amenities-more-button', 'page' => 'compare',  'showAmenities' => 11])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Locality section --}}
            <div class="compareComBox">
                <div class="leftBoxSmall">
                    <div class="lableBox">
                        <h5>Locality</h5>
                    </div>
                </div>
                @foreach($properties as $project)
                    <div class="detailSameBox">
                        <div class="borderBox">
                            <div class="localityBox">
                                @include('frontend.include.common-html-mobile-web', ['type' => 'localities', 'page' => 'compare'])
                                @include('frontend.include.common-html-mobile-web', ['type' => 'localities-more-button'])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="compareComBox">
                <div class="leftBoxSmall">
                    <div class="lableBox">
                        <h5>RERA Details</h5>
                    </div>
               </div>
                @foreach($properties as $project)
                    <div class="detailSameBox">
                        <div class="borderBox">
                            <div class="documentBox">
                                @include('frontend.include.common-html-mobile-web', ['type' => 'rera-document'])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="compareBtn">
                {{-- <a href="" class="btn linkBtn">Download Compare Reports</a> --}}
                <a href="{{ request()->fullUrlWithQuery(['download' => 'pdf']) }}" class="btn linkBtn">Download Compare Reports</a>
            </div>
        </div>
    </div>
 </section>
@endsection
@section('js')
    <script>
        var baseUrl = "{{ $baseUrl }}";
        $('.compareComBox .owl-carousel').owlCarousel({
            loop:true,
            margin:0,
            nav:true,
            navText: [
                '<img src="' + baseUrl + 'assest/images/left-ar.png" alt="left-ar">',
                '<img src="' + baseUrl + 'assest/images/right-ar.png" alt="right-ar">'
            ],

            dots:false,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1000:{
                    items:1
                }
            }
        })
    </script>
@endsection

