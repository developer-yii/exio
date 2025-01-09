@extends('backend.layouts.app')

@section('title', 'FAQs')

@section('content')

    @php
        $label_main = 'FAQs';
        $label = 'FAQ';
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
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Created at</th>
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
                                    <label for="question" class="form-label">Question<span class="text-danger add_edit_required">*</span></label>
                                    <input type="text" name="question" class="form-control question" value="">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="answer" class="form-label">Answer<span class="text-danger add_edit_required">*</span></label>
                                    <textarea class="form-control answer" id="answer" name="answer" rows="5"></textarea>
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="order_index" class="form-label">Order Index<span class="text-danger add_edit_required">*</span></label>
                                    <input type="number" name="order_index" class="form-control order_index" value="">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status<span class="text-danger add_edit_required">*</span></label>
                                    <select class="form-control status" name="status">
                                        @if (isset($status) && count($status) > 0)
                                            @foreach ($status as $status_id => $status_name)
                                                <option value="{{ $status_id }}">{{ $status_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
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
                    <h4 class="modal-title">{{ $label }} <span>information</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-centered mb-0" id="information">
                                <tbody>
                                    <tr>
                                        <th width="30%">Question</th>
                                        <td><span class="question"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Answer</th>
                                        <td><span class="answer"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Order Index</th>
                                        <td><p class="order_index"></p></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Status</th>
                                        <td><span class="status"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Created at</th>
                                        <td><span class="created_at"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Updated at</th>
                                        <td><span class="updated_at"></span></td>
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
        var apiUrl = "{{ route('admin.faq.list') }}";
        var detailUrl = "{{ route('admin.faq.detail') }}";
        var deleteUrl = "{{ route('admin.faq.delete') }}";
        var addUpdateUrl = "{{ route('admin.faq.addupdate') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('faq.js') }}"></script>
@endsection
