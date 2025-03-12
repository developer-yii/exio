@php
    $baseUrl = asset('frontend') . '/';
@endphp

@section('title', 'Shortlisted Properties')
@extends('frontend.layouts.app')
@section('content')
     <!-- compare project section -->
     <section class="bannerSky">
        <div class="container">
            <div class="bannerSkyText">
                <h4>Shortlisted Properties</h4>
                <p>Lorem Ipsum is simply dummy text of the printing and  typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever.</p>
            </div>
        </div>
     </section>

     <section class="liked_properties">
        <div class="container">
            <div class="liked_properties_box">
                <div class="row">
                    @foreach($favourite_properties as $property)
                        @php
                            $priceFormatted = "₹" . $property->price_from . formatPriceUnit($property->price_from_unit);

                            if ($property->price_from != $property->price_to || $property->price_from_unit != $property->price_to_unit) {
                                $priceFormatted .= " - " . $property->price_to . formatPriceUnit($property->price_to_unit);
                            }
                        @endphp
                        <div class="col-md-6 col-xl-4">
                            <div class="propertyCard cursor-pointer"
                                data-id="{{ $property->id }}"
                                data-slug="{{ $property->slug }}"
                                data-image="{{ $property->getCoverImageUrl() }}"
                                data-project-name="{{ $property->project_name }}"
                                data-builder-name="{{ $property->builder->builder_name }}"
                                data-custom-type="{{ $property->custom_property_type ?? 'N/A' }}"
                                data-location="{{ $property->location->location_name }}, {{ $property->city->city_name }}"
                                data-price="{{ $priceFormatted }}"
                                data-area="{{ $property->carpet_area ?? 'N/A' }} sqft"
                                data-floors="{{ $property->total_floors ? $property->total_floors. ' Floors' : 'N/A' }}"
                                data-towers="{{ $property->total_tower ?? 'N/A' }}"
                                data-age="{{ getAgeOfConstruction($property->age_of_construction) }}"
                                data-type="{{ $property->property_type }}"
                                data-property-type="{{ getPropertyType($property->property_type) }}"
                                data-description="{!! $property->project_about !!}"
                                data-size="{{ json_encode($property->projectDetails->map(fn($detail) => ['name' => $detail->name, 'value' => $detail->value])) }}"
                                data-multi-image="{{ json_encode($property->projectImages->take(3)->map(fn($detail) => ['imgurl' => $detail->getProjectImageUrl()])) }}"
                                data-whatsapp-number="{{ getSettingFromDb('support_mobile') }}"
                                data-like-class = "{{ $property->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }}"

                            >
                                <div class="imgBox">
                                    <img src="{{ $property->getCoverImageUrl() }}" alt="{{ $property->project_name }}" loading="lazy">
                                    <div class="imgheader">
                                        <span>Best for Investment</span>
                                        @if (Auth::check())
                                            <i class="{{ $property->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart heartIconFill" data-id="{{ $property->id }}"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="priceBox">
                                    <div class="price">
                                        <h5>{{ $priceFormatted }}
                                        </h5>
                                    </div>
                                    <div class="boxLogo">
                                        <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn" loading="lazy">
                                        <span>{{ $property->exio_suggest_percentage }}%</span>
                                    </div>
                                </div>
                                <div class="propertyName">
                                    <h5>{{ $property->project_name }}</h5>
                                </div>
                                <div class="locationProperty">
                                    <div class="homeBox comBox">
                                        <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home" loading="lazy">
                                        <p>{{ $property->custom_property_type ?? '' }}</p>
                                        {{-- <p>{{ $property->property_type ?? '' }}</p> --}}
                                    </div>
                                    <div class="location comBox">
                                        <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location" loading="lazy">
                                        <p>{{ $property->location->location_name . ', ' . $property->city->city_name }}</p>
                                    </div>
                                </div>
                                <div class="addressBox">
                                    <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home" loading="lazy">
                                    <p>{{ getAmenitiesList($property->amenities) }}</p>
                                    <span class="more-locality">more</span>
                                </div>
                                <div class="addtocompareBtn">
                                    <a href="javascript:void(0)" class="compareBoxOpen">
                                        <input type="checkbox" class="form-check-input checkbox" id="checkbox-signin" name="compare[]" autocomplete="off" value="{{ $property->id }}">
                                        Add to Compare
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Pagination Section start--}}
                    @if ($favourite_properties->hasPages())
                        @include('frontend.include.pagination', ['propertyPages' => $favourite_properties])
                    @endif
                {{-- Pagination Section end--}}
            </div>
            <div class="comparePorjectModal">
                <div class="compareMain">
                    <div class="comparePorjectCard">
                       {{-- compare property details here --}}
                    </div>
                    <div class="compareBtn">
                        <a href="javascript:void(0)" class="btn btnCompare cursor-default" disabled>Compare</a>
                    </div>
                    <div class="closeModal">
                        <a href="javascript:void(0)"><img src="{{ $baseUrl }}assest/images/x-orange.png" alt="x-orange"></a>
                    </div>
                </div>
            </div>
        </div>
     </section>
    <!-- compare project section -->

@endsection
@section('modal')
    <!-- propertyModal -->
    @include('frontend.include.share_property_modal')
    <!-- propertyModal -->

    <!-- Share_property Modal -->
    @include('frontend.include.property_detail_modal')
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script>
        var baseUrl = "{{ $baseUrl }}";
        var getPropertyDetailsUrl = "{{ route('property.details', ["_slug_"]) }}";
        // var propertyDetailsUrl = "{{ route('property.details', ['slug' => ':slug']) }}";
        var getComparePropertyUrl = "{{ route('property.compare') }}";
        var comparePropertytUrl = "{{ route('property.comparepage') }}";
    </script>
    <script src="{{ frontendPageJsLink('liked-properties.js') }}"></script>
@endsection
