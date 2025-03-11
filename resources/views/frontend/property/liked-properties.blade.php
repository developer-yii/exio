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
                        <div class="col-md-6 col-xl-4">
                            <div class="propertyCard cursor-pointer"
                                data-id="{{ $property->id }}"
                                data-slug="{{ $property->slug }}"
                                data-image="{{ $property->getCoverImageUrl() }}"
                                data-project-name="{{ $property->project_name }}"
                                data-builder-name="{{ $property->builder->builder_name }}"
                                data-custom-type="{{ $property->custom_property_type ?? 'N/A' }}"
                                data-location="{{ $property->location->location_name }}, {{ $property->city->city_name }}"
                                data-price="₹{{ $property->price_from }}{{ formatPriceUnit($property->price_from_unit) }} - ₹{{ $property->price_to }}{{ formatPriceUnit($property->price_to_unit) }}"
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

                            >
                                <div class="imgBox">
                                    <img src="{{ $property->getCoverImageUrl() }}" alt="{{ $property->project_name }}" loading="lazy">
                                    <div class="imgheader">
                                        <span>Best for Investment</span>
                                        @if (Auth::check())
                                            <i class="{{ $property->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart heartIconFill"
                                                data-id="{{ $property->id }}"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="priceBox">
                                    <div class="price">
                                        <h5>₹{{ $property->price_from }}{{ formatPriceUnit($property->price_from_unit) }}-{{ $property->price_to }}{{ formatPriceUnit($property->price_to_unit) }}
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
                        <div class="paginationBox">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <!-- First Page Link -->
                                    <li class="page-item {{ $favourite_properties->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $favourite_properties->url(1) }}" aria-label="First">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>

                                    <!-- Previous Page Link -->
                                    <li class="page-item {{ $favourite_properties->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $favourite_properties->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&lsaquo;</span>
                                        </a>
                                    </li>

                                    <!-- Page Numbers with Dots -->
                                    @php
                                        $currentPage = $favourite_properties->currentPage();
                                        $lastPage = $favourite_properties->lastPage();
                                        $start = max(1, $currentPage - 1);
                                        $end = min($lastPage, $currentPage + 1);
                                    @endphp

                                    @if ($start > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $favourite_properties->url(1) }}">1</a>
                                        </li>
                                        @if ($start > 2)
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        @endif
                                    @endif

                                    @for ($page = $start; $page <= $end; $page++)
                                        <li class="page-item">
                                            <a class="page-link {{ $page == $currentPage ? 'active' : '' }}" href="{{ $favourite_properties->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endfor

                                    @if ($end < $lastPage)
                                        @if ($end < $lastPage - 1)
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $favourite_properties->url($lastPage) }}">{{ $lastPage }}</a>
                                        </li>
                                    @endif

                                    <!-- Next Page Link -->
                                    <li class="page-item {{ $favourite_properties->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $favourite_properties->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&rsaquo;</span>
                                        </a>
                                    </li>

                                    <!-- Last Page Link -->
                                    <li class="page-item {{ $favourite_properties->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $favourite_properties->url($lastPage) }}" aria-label="Last">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
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
    <div class="modal fade propertyModal" id="propertyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="modalgallery">
                                <div class="top-img comImg">
                                    <img src="" alt="" id="coverImage">
                                </div>
                                <div class="multyimg"></div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="modalTextBox">
                                <div class="priceAndshare">
                                    <div class="price">
                                        <h5 id="property_price"></h5>
                                        <h5 id="property_name"></h5>
                                    </div>
                                    <ul>
                                        <li><a href="javascript:void(0)"><i class="fa-regular fa-heart"></i>Save</a></li>
                                        <li><a href="javascript:void(0)"><i class="fa-solid fa-arrow-up-from-bracket"></i>Share</a></li>
                                        <li><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></li>
                                    </ul>
                                </div>
                                <div class="locationProperty">
                                    <div class="homeBox comBox">
                                        <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home">
                                        <p id="custom_type"></p>
                                    </div>
                                    <div class="location comBox">
                                        <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location">
                                        <p id="location"></p>
                                    </div>
                                </div>

                                <div class="discriptBox">
                                    <p><strong>Description:</strong><span id="description"></span></p>
                                </div>
                                <div class="overViewBox">
                                    <div class="overBox">
                                        <span>Carpet Area</span><h6 id="carpet_area"></h6>
                                    </div>
                                    <div class="overBox">
                                        <span>Total Floors</span><h6 id="total_floor"></h6>
                                    </div>
                                    <div class="overBox">
                                        <span>Total Tower</span><h6 id="total_tower"></h6>
                                    </div>
                                    <div class="overBox">
                                        <span>Age of Construction</span><h6 id="age_of_construction"></h6>
                                    </div>
                                    <div class="overBox">
                                        <span>Property Type</span><h6 id="property_type"></h6>
                                    </div>
                                </div>
                                <div class="btn-container">
                                    <a class="btn btnWp" id="whatsapplink" target="_blank"><img src="{{ $baseUrl }}assest/images/wpicon.png" alt="wpicon">Quick Connect</a>
                                    <a href="" class="btn linkBtn" id="more-details">More Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- propertyModal -->

@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script>
        var baseUrl = "{{ $baseUrl }}";
        var getPropertyDetailsUrl = "{{ route('property.details', ["_slug_"]) }}";
        var getComparePropertyUrl = "{{ route('property.compare') }}";
        var comparePropertytUrl = "{{ route('property.comparepage') }}";
    </script>
    <script src="{{ frontendPageJsLink('liked-properties.js') }}"></script>
@endsection
