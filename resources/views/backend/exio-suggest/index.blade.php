@extends('backend.layouts.app')

@section('title', 'Exio-Suggest')

@section('content')

    @php
        $label_main = 'Exio-Suggest';
        $label = 'Exio-Suggest';
    @endphp

    <!-- start page title -->
    <div class="row mt-3 mb-3">
        <div class="col-md-6 col-lg-6 col-xl-6">
            <h4 class="page-title">{{ $label_main }}</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-6">
            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addModal"
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
                                <th>ID</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Weitage(%)</th>                                
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
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span>Add</span> {{ $label }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body m-1">
                        <input type="hidden" name="id" id="id" class="id">
                        
                        <div class="row">
                                <div class="form-group mb-3">
                                    <input type="hidden" name="type" id="type" class="form-control type" value="{{ $section }}">
                                    <span class="error"></span>
                                </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title*
                                        <span class="text-danger add_edit_required">*</span>
                                    </label>
                                    <input type="text" id="title" name="title" class="form-control title" value="" />
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="weightage" class="form-label">Weightage*
                                        <span class="text-danger add_edit_required">*</span></label>
                                    <input type="text" id="weightage" name="weightage" class="form-control weightage" value="" />
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
        var apiUrl = "{{ route('admin.section.list', ['section' => $section]) }}";
        var addUpdateUrl = "{{ route('admin.section.add-update', ['section' => $section]) }}";
        var detailUrl = "{{ route('admin.section.detail', ['section' => $section]) }}";
        var deleteUrl = "{{ route('admin.section.delete', ['section' => $section]) }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('exio-suggest.js') }}"></script>
@endsection
