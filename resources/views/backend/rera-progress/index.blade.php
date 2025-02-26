@extends('backend.layouts.app')

@section('title', 'Rera Progress Of ' . $project->project_name)

@section('content')

    @php
        $label_main = 'Rera Progress Of ' . $project->project_name;
        $label = 'Rera Progress';
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
        <div class="modal-dialog modal-lg">
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
        var apiUrl = "{{ route('admin.rera_progress.list') }}";
        var detailUrl = "{{ route('admin.rera_progress.detail') }}";
        var deleteUrl = "{{ route('admin.rera_progress.delete') }}";
        var addUpdateUrl = "{{ route('admin.rera_progress.addupdate') }}";
        var reraProjectId = "{{ $project->id }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('rera_progress.js') }}"></script>
@endsection
