@php
    $baseUrl = asset('frontend') . '/';
@endphp
@section('title', 'Insight Reports')
@extends('frontend.layouts.app')
@section('content')
    <!-- compare project section -->
    <section class="bannerSky">
        <div class="container">
            <div class="bannerSkyText">
                <h4>Download Insight Report</h4>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever.</p>
            </div>
        </div>
    </section>

    <section class="downloaded_certificates">
        <div class="container">
            @if($insightsReports->count() > 0)
                @foreach($insightsReports as $project)
                    @php
                        $property = $project->property ?? null;
                        $builder = $property->builder ?? null;
                        $location = $property->location->location_name ?? '';
                        $city = $property->city->city_name ?? '';
                        $possessionBy = $property->possession_by ?? null;
                    @endphp

                    <div class="downloaded_certificates_box"
                        data-slug="{{ $property->slug ?? '#' }}"
                        data-description="Explore {{ $property->project_name ?? 'Insight Reports' }} in {{ $location }}, {{ $city }}"
                        data-image="{{ $property->getCoverImageUrl() }}"
                        data-url="{{ route('property.details', $property->slug ?? '#') }}"
                        >
                        <div class="certificates_box">
                            <div class="detailmainSec">
                                <div class="detailsTextSec">
                                    <div class="siteDetails">
                                        <div class="logoMain">
                                            <img src="{{ $builder?->getBuilderLogoUrl() }}"
                                                alt="{{ $builder?->builder_name }}"
                                                loading="lazy">
                                        </div>
                                        <div class="textBox">
                                            <a href="{{ route('property.details', [$property->slug]) }}"> 
                                                <h5 class="projectTitle" data-title='{{ $property->project_name }}'>
                                                    {{ $property->project_name }} 
                                                    <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn" loading="lazy">
                                                    <span>{{ $property->exio_suggest_percentage ?? '0' }}%</span>
                                                </h5>
                                            </a><br>
                                            <span>By {{ $builder?->builder_name ?? 'Unknown Builder' }}</span>

                                            <div class="locationProperty">
                                                <div class="homeBox comBox">
                                                    <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home" loading="lazy">
                                                    <p class="one-line-text" title="{{ $property->custom_property_type ?? 'N/A' }}">{{ $property->custom_property_type ?? 'N/A' }}</p>
                                                </div>
                                                <div class="location comBox">
                                                    <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location" loading="lazy">
                                                    <p class="one-line-text" title="{{ $location }}, {{ $city }}">{{ $location }}, {{ $city }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="priceShare">
                                        @include('frontend.include.save_share_button', ['project' => $property])

                                        <h5>
                                            {!! formatPriceRange($property->price_from, $property->price_from_unit, $property->price_to, $property->price_to_unit) !!}
                                        </h5>
                                    </div>
                                </div>

                                <div class="endText">
                                    <p>
                                        <i class="fa-regular fa-calendar"></i>
                                        Possession by {{ $possessionBy ? getFormatedDate($possessionBy, 'M, Y') : 'TBD' }}
                                        <span class="line">|</span> RERA No. {{ $property->rera_number ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="download_box">
                            <a href="javascript:void(0)">
                                <img src="{{ $baseUrl }}assest/images/folder-download.png" alt="folder-download" class="downloadInsightReportPdf" 
                                    data-id="{{ $property->id }}"
                                    data-property-name="{{ $property->project_name }}"><br>
                                Download Again
                            </a>
                        </div>
                    </div>
                @endforeach

                @if ($insightsReports->hasPages())
                    @include('frontend.include.pagination', ['propertyPages' => $insightsReports])
                @endif

            @else
                <p>No such report found</p>
            @endif

        </div>
    </section>

@endsection
@section('modal')
    @include('frontend.include.share_property_modal')
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.saveProperty').click(function() {
                var container = $(this).closest('.downloaded_certificates_box');
                let title = container.data('title');
                let description = container.data('description');
                let image = container.data('image');
                let url = container.data('url');

                metaUpdate(title, description, image, url)
                updateShareLinks(url)
            });
        });

    </script>
@endsection
