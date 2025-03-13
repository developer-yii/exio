@php
    $baseUrl = asset('frontend') . '/';
@endphp
@section('title', 'Compare Reports')
@extends('frontend.layouts.app')
@section('content')
    <!-- compare project section -->
    <section class="bannerSky">
        <div class="container">
            <div class="bannerSkyText">
                <h4>Compare Reports</h4>
                <p>Lorem Ipsum is simply dummy text of the printing and  typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever.</p>
            </div>
        </div>
     </section>

    <section class="compare_reports">
        <div class="container">
            @if($compareReports->count() > 0)
                @foreach($compareReports as $report)
                    <div class="compare_reports_box">
                        <div class="certificates_box">
                            @include('frontend.property.partial_compare_section', ['property' => $report->propertyOne])
                            <div class="compareVs">Vs</div>
                            @include('frontend.property.partial_compare_section', ['property' => $report->propertyTwo])
                        </div>
                        <div class="download_box">
                            <a href="{{ route('property.compare-download', ['reportId' => $report->id]) }}"><img src="{{ $baseUrl }}assest/images/folder-download.png" alt="folder-download"><br>Download Again</a>
                        </div>
                    </div>
                @endforeach

                @if ($compareReports->hasPages())
                    @include('frontend.include.pagination', ['propertyPages' => $compareReports])
                @endif
            @else
                <p>Compare property not found</p>
            @endif

        </div>
    </section>

    <!-- compare project section -->
@endsection
@section('js')

@endsection

