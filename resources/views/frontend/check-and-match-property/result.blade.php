@php
    $baseUrl = asset('/') . 'frontend/';
@endphp
@extends('frontend.layouts.app')

@section('css')
    @include('frontend.include.google-maps')
@endsection

@section('content')
    <section class="startProperty mapProprty">
        <div class="container">
            <div class="check_propertMap">
                <div class="check_propertheader">
                    <div class="menuBread">
                        <span class="">You have selected</span>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                @php
                                    $displayProperty_a = formatChar($displayProperty);
                                    $displaySqft_a = formatChar($displaySqft);
                                    $displayLocation_a = formatChar($displayLocation);
                                    $displayAmenities_a = formatChar($displayAmenities);
                                    $displayBudget_a = formatChar($displayBudget);
                                @endphp
                                <li class="breadcrumb-item"><a
                                        href="{{ route('front.check-and-match-property.result', []) }}?{{ $allReqDataString }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ $displayProperty }}">{{ $displayProperty_a }}</a>
                                </li>
                                @if ($displaySqft_a)
                                    <li class="breadcrumb-item" aria-current="page" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ $displaySqft }}">{{ $displaySqft_a }}</li>
                                @endif
                                @if ($displayLocation_a)
                                    <li class="breadcrumb-item" aria-current="page" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ $displayLocation }}">{{ $displayLocation_a }}</li>
                                @endif
                                @if ($displayAmenities_a)
                                    <li class="breadcrumb-item" aria-current="page" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ $displayAmenities }}">{{ $displayAmenities_a }}
                                    </li>
                                @endif
                                @if ($displayBudget_a)
                                    <li class="breadcrumb-item" aria-current="page" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ $displayBudget }}">{{ $displayBudget_a }}
                                    </li>
                                @endif
                            </ol>
                            <a href="{{ route('front.check-and-match-property') }}" class="linkBtn">Start Again</a>
                        </nav>
                    </div>
                </div>
                <div class="mapProView">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- compare project section -->
    @include('frontend.include.compare')
    <!-- compare project section -->
@endsection

@section('js')
    <script>
        var projects = {!! json_encode($projects) !!};
        var map_pin = "{{ asset('frontend/assest/images/map-pin.png') }}";
        var priceUnit = @json($priceUnit);
        var assetUrl = "{{ asset('') }}";
        var getComparePropertyUrl = "{{ route('property.compare') }}";
        var comparePropertytUrl = "{{ route('property.comparepage') }}";
        var baseUrl = "{{ $baseUrl }}";
    </script>
    <script src="{{ $baseUrl }}/assest/js/pages/check-and-match-property-result.js"></script>
    <script src="{{ $baseUrl }}/assest/js/owl.carousel.js"></script>
    <script src="{{ frontendPageJsLink('compare.js') }}"></script>
    <script>
        $('.propertyCardMap .owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: false,
            dots: true,
            responsive: {
                0: {
                    items: 1
                }
            }
        })
    </script>
@endsection
