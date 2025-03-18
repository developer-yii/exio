@php
    $priceFormatted = '₹' . $project->price_from . formatPriceUnit($project->price_from_unit);

    if ($project->price_from != $project->price_to || $project->price_from_unit != $project->price_to_unit) {
        $priceFormatted .= ' - ' . $project->price_to . formatPriceUnit($project->price_to_unit);
    }
@endphp

<div class="col-md-6">
    <div class="propertyCard propertyCardModal cursor-pointer" data-id="{{ $project->id }}"
        data-slug="{{ $project->slug }}" data-image="{{ $project->getCoverImageUrl() }}"
        data-project-name="{{ $project->project_name }}" data-builder-name="{{ $project->builder->builder_name }}"
        data-custom-type="{{ $project->custom_property_type ?? 'N/A' }}"
        data-location="{{ $project->location->location_name }}, {{ $project->city->city_name }}"
        data-price="{{ $priceFormatted }}" data-area="{{ $project->carpet_area ?? 'N/A' }} sqft"
        data-floors="{{ $project->total_floors ? $project->total_floors . ' Floors' : 'N/A' }}"
        data-towers="{{ $project->total_tower ?? 'N/A' }}"
        data-age="{{ getAgeOfConstruction($project->age_of_construction) }}"
        data-type="{{ $project->property_type }}"
        data-property-type="{{ getPropertyType($project->property_type) }}"
        {{-- data-description="{{ htmlspecialchars($project->project_about, ENT_QUOTES, 'UTF-8') }}" --}}
        data-description="{{ htmlentities($project->project_about, ENT_QUOTES, 'UTF-8') }}"
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
                <i data-id="{{ $project->id }}"
                    class="{{ $project->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart heartIconFill"></i>
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
            <h5>{{ $project->project_name }}</h5>
        </div>
        <div class="locationProperty">
            @if ($project->custom_property_type)
                <div class="homeBox comBox">
                    <img src="{{ asset('/') }}frontend/assest/images/Home.png" alt="Home">
                    <p>{{ $project->custom_property_type }}</p>
                </div>
            @endif
            @if ($project->location->location_name || $project->city->city_name)
                <div class="location comBox">
                    <img src="{{ asset('/') }}frontend/assest/images/Location.png" alt="Location">
                    <p>
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
            <img src="{{ asset('/') }}frontend/assest/images/Home.png" alt="Home">
            @php
                $maxChars = 20;
                $amenityList = collect(explode(',', $project->amenities))
                    ->filter()
                    ->map(fn($id) => $amenities[$id] ?? null)
                    ->filter()
                    ->values();

                $amenityListString = $amenityList->implode(', ');
                $hasMore = strlen($amenityListString) > $maxChars;
                $displayText = $hasMore ? substr($amenityListString, 0, $maxChars) . '...' : $amenityListString;
            @endphp
            <p class="amenityText d-flex">
                @if ($amenityList->isNotEmpty())
                    <span class="amenity-text">{{ $displayText }}</span>
                    @if ($hasMore)
                        <span class="more-amenities" style="display: none;">{{ $amenityListString }}</span>
                        <a href="javascript:void(0)" class="toggle-amenities">more</a>
                    @endif
                @else
                    -
                @endif
            </p>
        </div>
    </div>
</div>
