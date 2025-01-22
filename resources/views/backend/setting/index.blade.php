@extends('backend.layouts.app')

@section('title', 'Settings')

@section('content')

    @php
        $label_main = 'Settings';
        $label = 'Setting';
    @endphp

    <!-- start page title -->
    <div class="row mt-3 mb-3">
        <div class="col-md-12 col-lg-12 col-xl-12">
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
                                <th>Label</th>
                                <th>Value</th>
                                <th>Description</th>
                                <th>Created at</th>
                                <th>Updated by</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> <!-- end row-->

    <div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <form action="#" method="POST" id="add-form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span>Add</span> {{ $label }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body m-1">
                        <input type="hidden" name="id" id="id" class="id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="setting_label" class="form-label">Label<span class="text-danger add_edit_required">*</span></label>
                                    <input type="text" name="setting_label" class="form-control setting_label" value="">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="setting_value" class="form-label">Value<span class="text-danger add_edit_required">*</span></label>
                                    <input type="text" name="setting_value" class="form-control setting_value" value="">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description<span class="text-danger add_edit_required">*</span></label>
                                    <textarea class="form-control description" id="description" name="description" rows="5"></textarea>
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

@endsection

@section('js')
    <script>
        var apiUrl = "{{ route('admin.setting.list') }}";
        var detailUrl = "{{ route('admin.setting.detail') }}";
        var addUpdateUrl = "{{ route('admin.setting.addupdate') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('setting.js') }}"></script>
@endsection
