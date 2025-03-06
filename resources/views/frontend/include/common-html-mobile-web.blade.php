@if($type == 'overview')
    <div id="overView">
        <div class="overViewBox">
            <div class="overBox">
                <span>Total Floors</span>
                <h6>{{ $project->total_floors }} Floors</h6>
            </div>
            <div class="overBox">
                <span>Total Tower</span>
                <h6>{{ $project->total_tower }}</h6>
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
                    <span>{{ $projectDetail->name }}</span>
                    <h6>{{ $projectDetail->value }}</h6>
                </div>
            @endforeach
        </div>
        <div class="textBox">
            <h6>About {{ $project->project_name }}</h6>
            <p>{!! $project->project_about !!}</p>
        </div>
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
                <div class="masterImg">
                    <img id="masterImg2D" src="" alt="master-img" loading="lazy">
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav3d" role="tabpanel" aria-labelledby="nav-tab-3d" tabindex="0">
            <div class="innerViewBox">
                <div class="masterImg">
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
                            <div class="item">
                                <div class="imgBox">
                                    <img src="{{ $floorPlan->$method() }}" alt="{{ $floorPlan->type }}" loading="lazy">
                                </div>
                                <div class="textSlider">
                                    @foreach (['Carpet Area' => $floorPlan->carpet_area . ' sqft', 'Type' => $floorPlan->type] as $label => $value)
                                        <div class="imgText">
                                            <span>{{ $label }}</span>
                                            <h6>{{ $value }}
                                            </h6>
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
    @foreach ($amenitiesList as $index => $amenity)
        <div class="itemsBox {{ $index >= 23 ? 'd-none' : '' }}">
            <div class="iconImg">
                <img src="{{ $amenity->getAmenityIconUrl() }}" alt="{{ $amenity->name }}" height="20" loading="lazy">
            </div>
            <div class="iconText">
                <p>{{ $amenity->amenity_name }}</p>
            </div>
        </div>
    @endforeach
@endif

@if($type == "amenities-more-button")
    @if (count($amenitiesList) > 23)
        <div class="itemsBox more">
            <a href="javascript:void(0)" id="showMoreAmenity">
                <div class="iconText">
                    <p>+ {{ count($amenitiesList) - 23 }} <br>more</p>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </a>
        </div>
    @endif
@endif

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
            <a href="javascript:void(0)" id="showMoreLocality">+
                {{ count($project->localities) - 7 }} More <i class="fa-solid fa-chevron-down"></i>
            </a>
        </div>
    @endif
@endif

@if($type == "rera-document")
    <div id="documents">
        <div class="title">
            <h5>RERA Details</h5>
        </div>
        <div class="documentBox">
            @foreach ($project->reraDetails as $reraDetail)
            <a href="{{ $reraDetail->getReraDocumentUrl() }}" download>
                <div class="boxBox">
                    <i class="bi bi-file-earmark"></i>
                    <p>{{ $reraDetail->title }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
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
    <a href="{{ route('property.details', [$similarProperty->slug]) }}">
        <div class="propertyCard">
            <div class="imgBox">
                <img src="{{ $similarProperty->getCoverImageUrl() }}" alt="{{ $similarProperty->project_name }}" loading="lazy">
                <div class="imgheader">
                    <span>Best for Investment</span>
                    @if (Auth::check())
                        <i class="{{ $similarProperty->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart heartIconFill"
                            data-id="{{ $similarProperty->id }}"></i>
                    @endif
                </div>
            </div>
            <div class="priceBox">
                <div class="price">
                    <h5>₹{{ $similarProperty->price_from }}{{ formatPriceUnit($similarProperty->price_from_unit) }}-{{ $similarProperty->price_to }}{{ formatPriceUnit($similarProperty->price_to_unit) }}
                    </h5>
                </div>
                <div class="boxLogo">
                    <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn" loading="lazy">
                    <span>{{ $similarProperty->exio_suggest_percentage }}%</span>
                </div>
            </div>
            <div class="propertyName">
                <h5>{{ $similarProperty->project_name }}</h5>
            </div>
            <div class="locationProperty">
                <div class="homeBox comBox">
                    <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home" loading="lazy">
                    <p>{{ $similarProperty->custom_property_type ?? '' }}</p>
                </div>
                <div class="location comBox">
                    <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location" loading="lazy">
                    <p>{{ $similarProperty->location->location_name . ', ' . $similarProperty->city->city_name }}</p>
                </div>
            </div>
            <div class="addressBox">
                <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home" loading="lazy">
                <p>{{ getAmenitiesList($similarProperty->amenities) }}</p>
                <span>more</span>
            </div>
        </div>
    </a>
@endif

