@php
    $baseUrl = asset('frontend') . '/';
@endphp
@foreach ($properties as $property)
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('property.insight-details', [$property->slug]) }}">
            <div class="propertyCard">
                <div class="imgBox">
                    <img src="{{ $property->getCoverImageUrl() }}" alt="{{ $property->project_name }}" loading="lazy">
                    <div class="imgheader">
                        <span>Best for Investment</span>
                    </div>
                </div>
                <div class="priceBox">
                    <div class="price">
                        <h5>₹{{ formatPriceUnit($property->price_from, $property->price_from_unit) }}
                            @if (hasDifferentPrices($property))
                                -{{ formatPriceUnit($property->price_to, $property->price_to_unit) }}
                            @endif
                        </h5>
                    </div>
                    <div class="boxLogo">
                        <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn" loading="lazy">
                        <span>{{ $property->exio_suggest_percentage }}%</span>
                    </div>
                </div>
                <div class="propertyName">
                    <h5 class="one-line-text" title="{{ $property->project_name }}">{{ $property->project_name }}</h5>
                </div>
                <div class="locationProperty">
                    <div class="homeBox comBox">
                        <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home" loading="lazy">
                        <p class="one-line-text" title="{{ $property->custom_property_type ?? '' }}">{{ $property->custom_property_type ?? '' }}</p>
                    </div>
                    <div class="location comBox">
                        <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location" loading="lazy">
                        <p class="one-line-text" title="{{ $property->location->location_name . ', ' . $property->city->city_name }}">{{ $property->location->location_name . ', ' . $property->city->city_name }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endforeach
