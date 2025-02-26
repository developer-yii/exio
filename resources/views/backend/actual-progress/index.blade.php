@extends('backend.layouts.app')

@section('title', 'Actual Progress Of ' . $project->project_name)

@section('css')
    <style>
        .image-preview {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
        }

        .image-preview img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .hover-image:hover {
            transform: scale(1.5);/ enlarge the image on hover /
        }
    </style>
@endsection

@section('content')

    @php
        $label_main = 'Actual Progress Of ' . $project->project_name;
        $label = 'Actual Progress';
    @endphp

    <!-- start page title -->
    <div class="row mt-3 mb-3">
        <div class="col-md-6 col-lg-6 col-xl-6">
            <h4 class="page-title">{{ $label_main }}</h4>
        </div>
        <div class="col-md-2 col-lg-2 col-xl-2">

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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"
                id="add-new-btn"><i class="uil-plus"></i> Add {{ $label }}</button>
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
                                <th>Timeline</th>
                                <th>Work Completed</th>
                                <th>Description</th>
                                <th>Date</th>
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


    <div class="modal fade" id="addModal" tabindex="-1" data-bs-focus="false" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <form action="#" method="POST" id="add-form" enctype="multipart/form-data">
                <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}" />
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span>Add</span> {{ $label }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body m-1">
                        <input type="hidden" name="id" id="id" class="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="timeline" class="form-label">Timeline<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <input type="text" id="timeline" name="timeline" class="form-control timeline"
                                        value="" placeholder="Enter Timeline in Months" style="margin-bottom: 0;" />
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="work_completed" class="form-label">Work Completed<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <input type="text" id="work_completed" name="work_completed"
                                        class="form-control work_completed" value=""
                                        placeholder="Enter Work Completed in Percentage" />
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <textarea id="description" name="description" class="form-control description" placeholder="Enter Description"
                                        rows="3"></textarea>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="date" class="form-label">Date<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <input type="date" id="date" name="date" class="form-control date"
                                        value="" placeholder="Enter Date" />
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <select id="status_a" name="status" class="form-control status">
                                        <option value="">Select Status</option>
                                        @if (!empty($statuses))
                                            @foreach ($statuses as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Add More of Actual Progress Images -->
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Actual Progress Images</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" id="actual-progress-images">
                                            <div class="col-md-4 mb-3">
                                                <a href="javascript:void(0);"
                                                    class="add-more-actual-progress-image text-decoration-none">
                                                    <div class="card border-dashed h-100">
                                                        <div
                                                            class="card-body text-center d-flex flex-column justify-content-center">
                                                            <i class="uil uil-plus-circle text-muted"
                                                                style="font-size: 24px;"></i>
                                                            <span class="text-muted mt-2">Add Image</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-block modal-footer">
                        <button type="button" class="btn btn-secondary float-start" id="model-cancle-btn"
                            data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-success float-end" id="addorUpdateBtn">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $label }} <span>Information</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-centered mb-0" id="information">
                                <tbody>
                                    <!-- Timeline -->
                                    <tr>
                                        <th width="30%">Timeline</th>
                                        <td><span class="timeline"></span> %</td>
                                    </tr>
                                    <!-- Work Completed -->
                                    <tr>
                                        <th width="30%">Work Completed</th>
                                        <td><span class="work_completed"></span> Months</td>
                                    </tr>
                                    <!-- Description -->
                                    <tr>
                                        <th width="30%">Description</th>
                                        <td><span class="description"></span></td>
                                    </tr>
                                    <!-- Date -->
                                    <tr>
                                        <th width="30%">Date</th>
                                        <td><span class="date"></span></td>
                                    </tr>
                                    <!-- Status -->
                                    <tr>
                                        <th width="30%">Status</th>
                                        <td><span class="status"></span></td>
                                    </tr>
                                    <!-- Images -->
                                    <tr>
                                        <th width="30%">Images</th>
                                        <td><span class="images"></span></td>
                                    </tr>
                                    <!-- Created at -->
                                    <tr>
                                        <th width="30%">Created at</th>
                                        <td><span class="created_at"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Updated at</th>
                                        <td><span class="updated_at"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Updated By</th>
                                        <td><span class="updated_by_view"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        var apiUrl = "{{ route('admin.actual_progress.list') }}";
        var detailUrl = "{{ route('admin.actual_progress.detail') }}";
        var deleteUrl = "{{ route('admin.actual_progress.delete') }}";
        var addUpdateUrl = "{{ route('admin.actual_progress.addupdate') }}";
        var getImagesUrl = "{{ route('admin.actual_progress.get-images') }}";
        var actualProjectId = "{{ $project->id }}";
        var existingActualProgressImages = {!! isset($existingActualProgressImages) ? json_encode($existingActualProgressImages) : '[]' !!};
        var assetUrl = "{{ asset('') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('actual_progress.js') }}"></script>
    <script src="{{ addPageJsLink('addmore/actual_progress_images.js') }}"></script>
@endsection
