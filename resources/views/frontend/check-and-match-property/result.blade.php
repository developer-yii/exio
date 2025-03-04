@php
    $baseUrl = asset('frontend/');
@endphp
@extends('frontend.layouts.app')

@section('css')
    <!-- Google Maps -->
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    <script>
        (g => {
            var h, a, k, p = "The Google Maps JavaScript API",
                c = "google",
                l = "importLibrary",
                q = "__ib__",
                m = document,
                b = window;
            b = b[c] || (b[c] = {});
            var d = b.maps || (b.maps = {}),
                r = new Set,
                e = new URLSearchParams,
                u = () => h || (h = new Promise(async (f, n) => {
                    await (a = m.createElement("script"));
                    e.set("libraries", [...r] + "");
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                    e.set("callback", c + ".maps." + q);
                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                    d[q] = f;
                    a.onerror = () => h = n(Error(p + " could not load."));
                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                    m.head.append(a)
                }));
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
                d[l](f, ...n))
        })
        ({
            key: "{{ config('constants.google_maps_api_key') }}",
            v: "weekly"
        });
    </script>
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
                                    $displayProperty = formatChar($displayProperty);
                                    $displaySqft = formatChar($displaySqft);
                                    $displayLocation = formatChar($displayLocation);
                                    $displayAmenities = formatChar($displayAmenities);
                                    $displayBudget = formatChar($displayBudget);
                                @endphp
                                <li class="breadcrumb-item"><a
                                        href="{{ route('front.check-and-match-property.result') }}?{{ $allReqDataString }}">{{ $displayProperty }}</a>
                                </li>
                                @if ($displaySqft)
                                    <li class="breadcrumb-item" aria-current="page">{{ $displaySqft }}</li>
                                @endif
                                @if ($displayLocation)
                                    <li class="breadcrumb-item" aria-current="page">{{ $displayLocation }}</li>
                                @endif
                                @if ($displayAmenities)
                                    <li class="breadcrumb-item" aria-current="page">{{ $displayAmenities }}</li>
                                @endif
                                @if ($displayBudget)
                                    <li class="breadcrumb-item" aria-current="page">{{ $displayBudget }}</li>
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

@section('js')
    <script>
        var projects = {!! json_encode($projects) !!};
        var map_pin = "{{ asset('frontend/assest/images/map-pin.png') }}";
        var priceUnit = @json($priceUnit);
    </script>
    <script src="{{ $baseUrl }}/assest/js/pages/check-and-match-property-result.js"></script>
@endsection
