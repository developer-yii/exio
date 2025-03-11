@php
    $baseUrl = asset('frontend') . '/';
    $metaTitle = "Insight Details | " . $project->project_name;
    $metaDesc = $project->project_name. " by " . $project->builder->builder_name;
@endphp

@section('title', $metaTitle)
@extends('frontend.layouts.app')
@section('content')
    <!-- compare project section -->
    <div class="site_traking_detail_page">
        <section class="bannerSky">
            <div class="container">
                <div class="detailmainSec">
                    @include('frontend.include.common-html-mobile-web', ['type' => 'project-builder-section'])
                </div>
            </div>
        </section>

        <section class="site_traking_section">
            <div class="container">
                <div class="site_traking_detail_box">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="left_traking_details">
                                <div class="chartBox">
                                    <canvas id="chart"></canvas>
                                </div>
                                @if(getDeviceType() == 'desktop')
                                    <div class="left_traking_stepSec desktop">
                                        @foreach($project->actualProgress as $index => $actualProgress)
                                            <div class="stepallBox">
                                                <div class="stepText">
                                                    @include('frontend.include.common-html-mobile-web', ['type' => 'date-progressStatus'])
                                                    @include('frontend.include.common-html-mobile-web', ['type' => 'how-to-step'])
                                                </div>

                                                @include('frontend.include.common-html-mobile-web', ['type' => 'stepimg'])
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(getDeviceType() == 'mobile')
                                    <div class="left_traking_stepSec mobile">
                                        @foreach($project->actualProgress as $index => $actualProgress)
                                            <div class="stepallBox">
                                                <div class="stepText">
                                                    <div class="stepTextmobile">
                                                        @include('frontend.include.common-html-mobile-web', ['type' => 'date-progressStatus'])
                                                    </div>
                                                    @include('frontend.include.common-html-mobile-web', ['type' => 'how-to-step'])
                                                </div>
                                                @include('frontend.include.common-html-mobile-web', ['type' => 'stepimg'])
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @include('frontend.include.exio-suggest')
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- compare project section -->
@endsection
@section('modal')
    @include('frontend.include.share_property_modal')
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script>
        var actualProgress = {!! json_encode($actualProgressData) !!};
        var reraProgress = {!! json_encode($reraProgressData) !!};
    </script>
    <script src="{{ frontendPageJsLink('insight-details.js') }}"></script>
@endsection