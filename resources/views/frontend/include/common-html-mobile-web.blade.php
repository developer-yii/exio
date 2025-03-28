@if($type == 'project-builder-section')
    <div class="detailsTextSec">
        @if(getDeviceType() == 'desktop')
            <div class="siteDetails">
                <div class="logoMain">
                    <img src="{{ $project->builder->getBuilderLogoUrl() }}" loading="lazy">
                </div>
                <div class="textBox">
                    <h5 class="projectTitle" data-title='{{ $project->project_name }}'>{{ $project->project_name }}</h5>
                    <span>By {{ $project->builder ? $project->builder->builder_name : '' }}</span>
                    <div class="locationProperty">
                        <div class="homeBox comBox">
                            <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home" loading="lazy">
                            <p>{{ $project->custom_property_type ?? '' }}</p>
                        </div>
                        <div class="location comBox">
                            <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location" loading="lazy">
                            <p>{{ $project->location->location_name . ', ' . $project->city->city_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mobile">
                <div class="siteDetails">
                    <div class="logoMain">
                        <img src="{{ $project->builder->getBuilderLogoUrl() }}" loading="lazy">
                    </div>
                    <div class="textBox">
                        <h5 class="projectTitle" data-title='{{ $project->project_name }}'>{{ $project->project_name }}</h5>
                        <span>By {{ $project->builder ? $project->builder->builder_name : '' }}</span>
                    </div>
                </div>
                <div class="locationProperty">
                    <div class="homeBox comBox">
                        <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home" loading="lazy">
                        <p>{{ $project->custom_property_type ?? '' }}</p>
                    </div>
                    <div class="location comBox">
                        <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location" loading="lazy">
                        <p>{{ $project->location->location_name . ', ' . $project->city->city_name }}</p>
                    </div>
                </div>
            </div>
        @endif
        <div class="priceShare">
            @include('frontend.include.save_share_button', ['project' => $project])
            <h5>
                {!! formatPriceRange($project->price_from, $project->price_from_unit, $project->price_to, $project->price_to_unit) !!}
            </h5>
        </div>
    </div>
    <div class="endText">
        <p>
            <i class="fa-regular fa-calendar"></i> Possession by
            {{ getFormatedDate($project->possession_by, 'M, Y') }}
            <span class="line">|</span>
            RERA No. {{ $project->rera_number }}
        </p>
    </div>
@endif

@if($type == 'overviewBox')
    <div id="overView">
        <div class="overViewBox">
            <div class="overBox">
                <span>Total Floors</span>
                <h6 class="one-line-text" title="{{ $project->total_floors }}">{{ $project->total_floors }} Floors</h6>
            </div>
            <div class="overBox">
                <span>Total Tower</span>
                <h6 class="one-line-text" title="{{ $project->total_tower }}">{{ $project->total_tower }}</h6>
            </div>
            <div class="overBox">
                <span>Age of Construction</span>
                <h6>{{ getAgeOfConstruction($project->age_of_construction) }}</h6>
            </div>
            <div class="overBox">
                <span>Property Type</span>
                <h6>{{ getPropertyType($project->property_type) }}</h6>
            </div>
            @foreach ($project->projectDetails as $projectDetail)
                <div class="overBox">
                    <span class="one-line-text" title="{{ $projectDetail->name }}">{{ $projectDetail->name }}</span>
                    <h6 class="one-line-text" title="{{ $projectDetail->value }}">{{ $projectDetail->value }}</h6>
                </div>
            @endforeach
          </div>
    </div>
@endif
@if($type == 'project-details')
    <div class="textBox">
        <h6>About {{ $project->project_name }}</h6>
        <p>{!! $project->project_about !!}</p>
    </div>
@endif

@if($type == "master-plan-2d-3d-section")
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-tab-2d" data-bs-toggle="tab" data-bs-target="#nav2d" type="button" role="tab" aria-controls="nav2d" aria-selected="true">2D</button>

        <button class="nav-link" id="nav-tab-3d" data-bs-toggle="tab" data-bs-target="#nav3d" type="button" role="tab" aria-controls="nav3d" aria-selected="false">3D</button>
    </div>
@endif

@if($type == "master-plan-2d-3d-image")
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav2d" role="tabpanel" aria-labelledby="nav-tab-2d" tabindex="0">
            <div class="innerViewBox">
                <div class="masterImg cursor-pointer">
                    <img id="masterImg2D" src="" alt="master-img" loading="lazy">
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav3d" role="tabpanel" aria-labelledby="nav-tab-3d" tabindex="0">
            <div class="innerViewBox">
                <div class="masterImg cursor-pointer">
                    <img id="masterImg3D" src="" alt="master-img" loading="lazy">
                </div>
            </div>
        </div>
    </div>
@endif

@if($type == "floor-plan-2d-3d-section")
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-2d-tab" data-bs-toggle="tab" data-bs-target="#nav-2d" type="button" role="tab" aria-controls="nav-2d" aria-selected="true">2D</button>

        <button class="nav-link" id="nav-3d-tab" data-bs-toggle="tab" data-bs-target="#nav-3d" type="button" role="tab" aria-controls="nav-3d" aria-selected="false">3D</button>
    </div>
@endif

@if($type == "floor-plan-2d-3d-image")
    <div class="tab-content" id="nav-tabContent2">
        @foreach (['2d' => 'get2DImageUrl', '3d' => 'get3DImageUrl'] as $key => $method)
            <div class="tab-pane fade {{ $key == '2d' ? 'show active' : '' }}" id="nav-{{ $key }}" role="tabpanel" aria-labelledby="nav-{{ $key }}-tab">
                <div class="sliderImgSec">
                    <div class="owl-carousel owl-theme">
                        @foreach ($project->floorPlans as $floorPlan)
                            <div class="item cursor-pointer">
                                <div class="imgBoxFloorPlan">
                                    <a href="{{ $floorPlan->$method() }}" data-fancybox="floorplan{{$key}}">
                                        <img src="{{ $floorPlan->$method() }}" alt="{{ $floorPlan->type }}" loading="lazy">
                                    </a>
                                </div>
                                <div class="textSlider">
                                    @foreach (['Carpet Area' => $floorPlan->carpet_area . ' sqft', 'Type' => $floorPlan->type] as $label => $value)
                                        <div class="imgText">
                                            <span>{{ $label }}</span>
                                            <h6>{{ $value }}</h6>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($type == "amenities")
    @php
        $amenities = ($page ?? '') == 'compare' ? $project->amenitiesList : $amenitiesList;
        $iconSrc = ($page ?? '') == 'compare' ? $baseUrl . 'assest/images/circle_gray.png' : null;
    @endphp

    @foreach ($amenities as $index => $amenity)
        <div class="itemsBox {{ $index >= $showAmenities ? 'd-none' : '' }}">
            <div class="iconImg">
                <img src="{{ $amenity->getAmenityIconUrl() }}"
                     alt="{{ $amenity->name }}"
                     height="20"
                     loading="lazy">
            </div>
            <div class="iconText">
                <p>{{ $amenity->amenity_name }}</p>
            </div>
        </div>
    @endforeach
@endif


@if($type == "amenities-more-button")
    @php
        $amenities = ($page ?? '') == 'compare' ? ($project->amenitiesList ?? []) : ($amenitiesList ?? []);
    @endphp

    @if (count($amenities) > $showAmenities)
        <div class="itemsBox more">
            <a href="javascript:void(0)" class="showMoreAmenity">
                <div class="iconText">
                    <p>+ {{ count($amenities) - $showAmenities }} <br>more</p>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </a>
        </div>
    @endif
@endif

{{-- @if($type == "amenities-more-button")
    $amenities = ($page ?? '') == 'compare' ? $project->amenitiesList : $amenitiesList;
    @if (count($amenities) > 11)
        <div class="itemsBox more">
            <a href="javascript:void(0)" class="showMoreAmenity">
                <div class="iconText">
                    <p>+ {{ count($amenities) - 11 }} <br>more</p>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </a>
        </div>
    @endif
@endif --}}

@if($type == "download-brochure")
    <div class="browser_get">
        <a href="javascript:void(0)" class="btnbrowser" data-bs-toggle="modal" data-bs-target="#downloadBrochure">
            <i class="bi bi-file-earmark"></i> Download Brochure
        </a>
    </div>
@endif

@if($type == "localities")
    @foreach ($project->localities as $index => $locality)
        <div class="localityItem {{ $index >= 7 ? 'd-none' : '' }}">
            <div class="imgIcon">
                {{-- @if($page == 'compare')
                    <img src="{{ $baseUrl }}assest/images/circle_gray.png" alt="circle_gray"  loading="lazy">
                @else
                @endif --}}
                <img src="{{ $locality->locality->getLocalityImageUrl() }}" loading="lazy">
            </div>
            <div class="textBox">
                <span>{{ $locality->locality->locality_name }}
                    ({{ $locality->time_to_reach }} min)</span>
                <h6>{{ $locality->distance }} {{ $locality->distance_unit }}</h6>
            </div>
        </div>
    @endforeach
@endif

@if($type == "localities-more-button")
    @if (count($project->localities) > 7)
        <div class="localityItem">
            <a href="javascript:void(0)" class="showMoreLocality">+
                {{ count($project->localities) - 7 }} More <i class="fa-solid fa-chevron-down"></i>
            </a>
        </div>
    @endif
@endif

@if($type == "rera-document")
    @foreach ($project->reraDetails as $reraDetail)
        <a href="{{ $reraDetail->getReraDocumentUrl() }}" download>
            <div class="boxBox">
                <i class="bi bi-file-earmark"></i>
                <p>{{ $reraDetail->title }}</p>
            </div>
        </a>
    @endforeach
@endif

@if($type == "map")
    <div class="mapText">
        <h6>{{ $project->project_name }}</h6>
        <p>{{ $project->address }}</p>
    </div>
    <div class="mapmain" id="map" style="height: 400px; width: 100%;"></div>
@endif

@if($type == "about")
    <div class="aboutGlory">
        <div class="title">
            <h5>About {{ $project->builder->builder_name }}</h5>
        </div>
        <div class="aboutUs">
            <p>{!! $project->builder->builder_about !!}</p>
        </div>
    </div>
@endif

@if($type == "similar-property")
    <a href="{{ route('property.details', [$similarProperty->slug]) }}" class="w-100">
        <div class="propertyCard">
            <div class="imgBox">
                <img src="{{ $similarProperty->getCoverImageUrl() }}" alt="{{ $similarProperty->project_name }}" loading="lazy">
                <div class="imgheader">
                    @if ($similarProperty->projectBadge)
                        <span>{{ $similarProperty->getProjectBadgeName() }}</span>
                    @else
                        <span style="opacity: 0 !important;"></span>
                    @endif
                    
                    @if (Auth::check())
                        <i class="{{ $similarProperty->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart heartIconFill" data-id="{{ $similarProperty->id }}"></i>
                    @else
                        <i class="fa-regular fa-heart show-login-toastr"></i>
                    @endif
                </div>
            </div>
            <div class="priceBox">
                <div class="price">
                    <h5>
                        {!! formatPriceRangeSingleSign($similarProperty->price_from, $similarProperty->price_from_unit, $similarProperty->price_to, $similarProperty->price_to_unit) !!}
                    </h5>
                </div>
                <div class="boxLogo">
                    <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn" loading="lazy">
                    <span>{{ $similarProperty->exio_suggest_percentage }}%</span>
                </div>
            </div>
            <div class="propertyName">
                <h5 class="one-line-text" title="{{ $similarProperty->project_name }}">{{ $similarProperty->project_name }}</h5>
            </div>
            <div class="locationProperty">
                <div class="homeBox comBox">
                    <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home" loading="lazy">
                    <p class="one-line-text" title="{{ $similarProperty->custom_property_type ?? '' }}">{{ $similarProperty->custom_property_type ?? '' }}</p>
                </div>
                <div class="location comBox">
                    <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location" loading="lazy">
                    <p class="one-line-text" title="{{ $similarProperty->location->location_name . ', ' . $similarProperty->city->city_name }}">{{ $similarProperty->location->location_name . ', ' . $similarProperty->city->city_name }}</p>
                </div>
            </div>
            <div class="addressBox">
                <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home" loading="lazy">
                <p>{{ $similarProperty->amenities ? getAmenitiesList($similarProperty->amenities) : '-' }}</p>
                <span class="more-locality">more</span>
            </div>
        </div>
    </a>
@endif

@if($type == 'stepimg')
    <div class="stepimg">
        @php
            $images = $actualProgress->images; // Get images
            $firstImage = $images->first(); // Get first image
            $imageCount = $images->count(); // Get total image count
        @endphp

        <img src="{{ $firstImage->getProgressImageUrl() }}" alt="step-image" data-lightbox="step-{{ $actualProgress->id }}">

        @if($imageCount > 1)
            <div class="moreimg">
                <a href="javascript:void(0)" class="open-lightbox" data-step-id="{{ $actualProgress->id }}">
                    {{ $imageCount - 1 }}+
                </a>
            </div>

            <!-- Hidden lightbox images -->
            <div class="lightbox" id="lightbox-{{ $actualProgress->id }}">
                @foreach($images as $image)
                    <a href="{{ $image->getProgressImageUrl() }}" data-lightbox="step-{{ $actualProgress->id }}">
                        {{-- <img src="{{ $image->getProgressImageUrl() }}" alt="step-image"> --}}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endif

@if($type == 'date-progressStatus')
    <span> {{ getFormatedDate($actualProgress->date, 'jS F Y') }}</span>
    <div class="checkImg">
        @if($actualProgress->status)
        <img src="{{ $baseUrl }}assest/images/check-green.png" alt="{{ $progressStatus[$actualProgress->status] }}">
        @else
        <img src="{{ $baseUrl }}assest/images/in-progress.png" alt="{{ $progressStatus[$actualProgress->status] }}">
        @endif
    </div>
@endif
@if($type == 'how-to-step')
    <div class="howtoStep">
        <h5>Step {{ count($project->actualProgress) - ($index) }}</h5>
        <p>{!! $actualProgress->description !!}</p>
    </div>
@endif

@if($type == 'best-property-title')
<div class="sectionTitleBox">
    <h3 id="city_best_property_title">Best Property in Ahmedabad</h3>
</div>
@endif

@if($type == 'search-key')
    <div class="search-key d-none">        
        <ul>
            @if ($sLocations->count() > 0)
                <h6>Locality</h6>
                @foreach ($sLocations as $location)
                    <li style="display: none;">
                        <a href="javascript:void(0)" data-type="locality"
                            data-id="{{ $location->id }}"
                            data-name="{{ $location->location_name }}">
                            {{ $location->location_name }}
                        </a>
                    </li>
                @endforeach
            @endif
            @if ($sProjects->count() > 0)
                <h6>Project</h6>
                @foreach ($sProjects as $project)
                    <li style="display: none;">
                        <a href="javascript:void(0)" data-type="project"
                            data-id="{{ $project->id }}"
                            data-name="{{ $project->project_name }}">
                            {{ $project->project_name }}
                        </a>
                    </li>
                @endforeach
            @endif
            @if ($sBuilders->count() > 0)
                <h6>Builder</h6>
                @foreach ($sBuilders as $builder)
                    <li style="display: none;">
                        <a href="javascript:void(0)" data-type="builder"
                            data-id="{{ $builder->id }}"
                            data-name="{{ $builder->builder_name }}">
                            {{ $builder->builder_name }}
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
@endif

