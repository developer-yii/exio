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
            {{-- <div class="paginationBox">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <!-- First Page Arrow (Disabled) -->
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="First">
                                <span aria-hidden="true">«</span>
                            </a>
                        </li>
                        <!-- Previous Page Arrow (Disabled) -->
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">‹</span>
                            </a>
                        </li>
                        <!-- Page Numbers -->
                        <li class="page-item"><a class="page-link active" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><span class="page-link">...</span></li>
                        <!-- Next Page Arrow -->
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">›</span>
                            </a>
                        </li>
                        <!-- Last Page Arrow -->
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Last">
                                <span aria-hidden="true">»</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div> --}}
        </div>
    </section>

    <!-- compare project section -->
@endsection
@section('js')

@endsection

