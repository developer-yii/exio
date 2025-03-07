@extends('backend.layouts.app')

@section('title', 'Download Brochure Data')

@section('content')

    @php
        $label_main = 'Download Brochure Data';
        $label = 'Download Brochure Data';
    @endphp

    <!-- start page title -->
    <div class="row mt-3 mb-3">
        <div class="col-md-6 col-lg-6 col-xl-6">
            <h4 class="page-title">{{ $label_main }}</h4>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="dataTableMain" class="table site_table w-100 nowrap">
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> <!-- end row-->
@endsection

@section('js')
    <script>
        var apiUrl = "{{ route('admin.download-brochure.list') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('brochure-data.js') }}"></script>
@endsection
