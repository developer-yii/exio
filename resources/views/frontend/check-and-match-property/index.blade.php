@php
    $baseUrl = asset('frontend') . '/';
@endphp
@extends('frontend.layouts.app')

@section('content')
    <!-- Start check property section -->
    <section class="startProperty">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="leftVideoSec">
                        <div class="videoCoverImage">
                            <video autoplay muted loop>
                                @if(getCheckAndMatchVideoPath($checkandmatch->setting_value))
                                    <source src="{{ getCheckAndMatchVideoPath($checkandmatch->setting_value) }}">
                                @else
                                    <source src="{{ $baseUrl }}assest/images/video1.mp4">
                                @endif
                            </video>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="rightvideoText">
                        <div class="centerBox">
                            <div class="img">
                                <img src="{{ $baseUrl }}assest/images/bot-2.png" alt="bot-2">
                            </div>
                            <div class="bottext">
                                <img src="{{ $baseUrl }}assest/images/logo-img.png" alt="">
                                <h4>Bot</h4>
                            </div>
                            <p>{{ $checkandmatch->description ?? '' }}</p>
                            <a data-bs-toggle="modal" data-bs-target="#lookingProperty" href="javascript:void(0)"
                                class="btn linkBtn">Start Check & Match Property</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Start check property section -->


    <!-- lookingProperty Modal -->
    <div class="modal fade lookingProperty modelSize" id="lookingProperty" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="title">
                        <h4>Are you Looking for?</h4>
                    </div>
                    <div class="feetSelectBox">
                        @if (count($propertyTypes) > 0)
                            @foreach ($propertyTypes as $key => $propertyType)
                                <div class="clickTo">
                                    <input type="radio" name="property_type" value="{{ $key }}"
                                        class="keyword-checkbox" {{ $key == 'residential' ? 'checked' : '' }}>
                                    <label for="{{ $key }}" class="keyword-label">{{ $propertyType }}</label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="footerBtn">
                        <button id="nextLookingPropertyButton" class="btn btnNext btnFull">Next <span>(1/5)</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- lookingProperty Modal -->

    <!-- feetRequired Modal -->
    <div class="modal fade feetRequired modelSize" id="feetRequired" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="title">
                        <h4>How many square feet are required?</h4>
                    </div>
                    <div class="feetSelectBox">
                        @if (count($sqftOptions) > 0)
                            @foreach ($sqftOptions as $key => $sqftOption)
                                <div class="clickTo">
                                    <label class="checkbox">
                                        <input class="checkbox__input" type="checkbox" name="sqft_options[]"
                                            value="{{ $key }}" data-option="{{ $sqftOption }}" />
                                        <span class="checkbox__label">{{ $sqftOption }}</span>
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="footerBtn">
                        <a class="btn btnPreview" href="javascript:void(0)" id="sqftPrevButton"><i
                                class="fa-solid fa-arrow-left"></i>Previous</a>
                        <button class="btn btnNext" id="sqftNextButton">Next <span>(2/5)</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- feetRequired Modal -->

    <!-- cityselect Modal -->
    <div class="modal fade cityselect modelSize" id="cityselect" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="title">
                        <h4>For which city are you looking for?</h4>
                    </div>
                    <div class="selectArea">
                        <div class="dropBox">
                            <label for="city">Select City</label>
                            <select id="city" name="city" class="form-control">
                                <option value="">Select City</option>
                                @if (count($cities) > 0)
                                    @foreach ($cities as $key => $city)
                                        <option value="{{ $key }}" {{ $key == '1' ? 'selected' : '' }}>
                                            {{ $city }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="dropBox">
                            <label for="location">Select Area</label>
                            <select name="location" id="location" class="form-control">
                                <option value="">Select Area</option>
                                @if (count($areas) > 0)
                                    @foreach ($areas as $key => $area)
                                        <option value="{{ $key }}">{{ $area }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="footerBtn">
                        <a class="btn btnPreview" href="javascript:void(0)" id="cityPrevButton"><i
                                class="fa-solid fa-arrow-left"></i>Previous</a>
                        <button class="btn btnNext" id="cityNextButton">Next <span>(3/5)</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cityselect Modal -->

    <!-- amenities Modal -->
    <div class="modal fade amenities modelSize" id="amenities" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="title">
                        <h4>Which amenities are required?</h4>
                    </div>
                    <div class="feetSelectBox" id="amenitiesList">
                    </div>
                    <div class="footerBtn">
                        <a class="btn btnPreview" href="javascript:void(0)" id="amenitiesPrevButton"><i
                                class="fa-solid fa-arrow-left"></i>Previous</a>
                        <button class="btn btnNext" id="amenitiesNextButton">Next <span>(4/5)</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- amenities Modal -->

    <!-- budgets Modal -->
    <div class="modal fade budgets modelSize" id="budgets" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="title">
                        <h4>What is your Budgets?</h4>
                    </div>
                    <div class="feetSelectBox">
                        @if (count($budgets) > 0)
                            @foreach ($budgets as $key => $budget)
                                <div class="clickTo">
                                    <label class="checkbox">
                                        <input class="checkbox__input" type="checkbox" name="budgets[]"
                                            value="{{ $key }}" />
                                        <span class="checkbox__label">{{ $budget }}</span>
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="footerBtn">
                        <a class="btn btnPreview" href="javascript:void(0)" id="budgetsPrevButton"><i
                                class="fa-solid fa-arrow-left"></i>Previous</a>
                        <button class="btn btnNext" id="budgetsNextButton">Next <span>(5/5)</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- budgets Modal -->
@endsection

@section('js')
    <script>
        var getAmenitiesUrl = "{{ route('front.check-and-match-property.get-amenities') }}";
        var checkAndMatchPropertyResultUrl = "{{ route('front.check-and-match-property.result') }}";
    </script>
    <script src="{{ $baseUrl }}assest/js/pages/check-and-match-property.js"></script>
@endsection
