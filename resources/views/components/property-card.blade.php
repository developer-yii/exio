@php
    $priceFormatted = formatPriceRangeSingleSign($project->price_from, $project->price_from_unit, $project->price_to, $project->price_to_unit);
@endphp

<div class="col-md-6">
    <div class="propertyCard propertyCardModal cursor-pointer" data-id="{{ $project->id }}"
        data-slug="{{ $project->slug }}" data-image="{{ $project->getCoverImageUrl() }}"
        data-project-name="{{ $project->project_name }}" data-builder-name="{{ $project->builder->builder_name }}"
        data-custom-type="{{ $project->custom_property_type ?? 'N/A' }}"
        data-location="{{ $project->location->location_name }}, {{ $project->city->city_name }}"
        data-price="{{ $priceFormatted }}"
        data-floors="{{ $project->total_floors ? $project->total_floors . ' Floors' : 'N/A' }}"
        data-towers="{{ $project->total_tower ?? 'N/A' }}"
        data-age="{{ getAgeOfConstruction($project->age_of_construction) }}"
        data-type="{{ $project->property_type }}"
        data-property-type="{{ getPropertyType($project->property_type) }}"
        data-description="{{ formattedProjectAbout($project->project_about) }}"
        data-size="{{ json_encode($project->projectDetails->map(fn($detail) => ['name' => $detail->name, 'value' => $detail->value])) }}"
        data-multi-image="{{ json_encode($project->projectImages->take(3)->map(fn($detail) => ['imgurl' => $detail->getProjectImageUrl()])) }}"
        data-whatsapp-number="{{ getSettingFromDb('support_mobile') }}"
        data-like-class = "{{ $project->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }}">

        <div class="imgBox">
            <img src="{{ $project->getCoverImageUrl() }}" alt="property-img">
            <div class="imgheader">
                @if ($project->projectBadge)
                    <span>{{ $project->getProjectBadgeName() }}</span>
                @else
                    <span style="opacity: 0 !important;"></span>
                @endif

                @if (Auth::check())
                    <i data-id="{{ $project->id }}" class="{{ $project->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart heartIconFill"></i>
                @else
                    <i class="fa-regular fa-heart show-login-toastr"></i>
                @endif

            </div>
        </div>
        <div class="priceBox">
            <div class="price">
                <h5>{{ $priceFormatted }}</h5>
            </div>
            <div class="boxLogo">
                <img src="{{ asset('/') }}frontend/assest/images/x-btn.png" alt="x-btn">
                <span>{{ $project->exio_suggest_percentage }}%</span>
            </div>
        </div>
        <div class="propertyName">
            <h5 class="one-line-text" title="{{ $project->project_name }}">{{ $project->project_name }}</h5>
        </div>
        <div class="locationProperty">
            @if ($project->custom_property_type)
                <div class="homeBox comBox">
                    <img src="{{ asset('/') }}frontend/assest/images/Home.png" alt="Home">
                    <p class="one-line-text" title="{{ $project->custom_property_type }}">{{ $project->custom_property_type }}</p>
                </div>
            @endif
            @if ($project->location->location_name || $project->city->city_name)
                <div class="location comBox">
                    <img src="{{ asset('/') }}frontend/assest/images/Location.png" alt="Location">
                    <p class="one-line-text" title="{{ $project->location->location_name . ', ' . $project->city->city_name }}">
                        @if ($project->location->location_name || $project->city->city_name)
                            {{ $project->location->location_name }}{{ $project->location->location_name && $project->city->city_name ? ', ' : '' }}{{ $project->city->city_name }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            @endif
        </div>
        <div class="addressBox">
            <img src="{{ asset('/') }}frontend/assest/images/Home.png" alt="Home" loading="lazy">
            <p class="one-line-text" title="{{ $project->amenities ? getAmenitiesList($project->amenities) : '-' }}">
                {{ $project->amenities ? getAmenitiesList($project->amenities) : '-' }}
            </p>
        </div>
    </div>
</div>
