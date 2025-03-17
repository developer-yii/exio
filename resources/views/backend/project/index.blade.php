@extends('backend.layouts.app')

@section('title', 'Projects')

@section('content')

    @php
        $label_main = 'Projects';
        $label = 'Project';
    @endphp

    <!-- start page title -->
    <div class="row mt-3 mb-3">
        <div class="col-md-6 col-lg-6 col-xl-6">
            <h4 class="page-title">{{ $label_main }}</h4>
        </div>
        <div class="col-md-2 col-lg-2 col-xl-2">
            <select class="form-select filter_status" id="filter_status" name="filter_status">
                <option value="">Filter by Status</option>
                @if (isset($status) && count($status) > 0)
                    @foreach ($status as $status_id => $status_name)
                        <option value="{{ $status_id }}">{{ $status_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-2 col-lg-2 col-xl-2">
            <div class="input-group position-relative" id="datepicker_1">
                <span class="input-group-text" id="dateFilter"><span class="uil-calendar-alt"></span></span>
                <input type="text" class="form-control" id="filter_date" name="filter_date" placeholder="Filter by Date"
                    aria-describedby="dateFilter" data-provide="datepicker" data-date-autoclose="true"
                    data-date-container="#datepicker_1" data-date-format="d-m-yyyy">
            </div>
        </div>
        <div class="col-md-2 col-lg-2 col-xl-2">
            <a href="{{ route('admin.project.add') }}" class="btn btn-primary" id="add-new-btn"><i class="uil-plus"></i>
                Add
                {{ $label }}</a>
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
                                <th>Slug</th>
                                <th>Project About</th>
                                <th>Project Badge</th>
                                <th>Builder</th>
                                <th>City</th>
                                <th>Location</th>
                                <th>Property Type</th>
                                <th>Property Sub Type</th>
                                <th>Possession By</th>
                                <th>Rera Number</th>
                                <th>Rera Progress</th>
                                <th>Actual Progress</th>
                                <th>Price From</th>
                                <th>Price To</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th>Action</th>
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
        var apiUrl = "{{ route('admin.project.list') }}";
        var detailUrl = "{{ route('admin.project.detail') }}";
        var deleteUrl = "{{ route('admin.project.delete') }}";
        var addUpdateUrl = "{{ route('admin.project.addupdate') }}";
        var isSuperAdmin = "{{ isSuperAdmin() }}";
    </script>
@endsection

@section('pagejs')
    <script>
        var editUrl = "{{ route('admin.project.edit', ':id') }}";
        var viewUrl = "{{ route('admin.project.view', ':id') }}";
    </script>
    <script src="{{ addPageJsLink('project.js') }}"></script>
@endsection
