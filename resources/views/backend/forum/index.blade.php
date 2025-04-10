@extends('backend.layouts.app')

@section('title', 'Forums')

@section('content')

    @php
        $label_main = 'Forums';
        $label = 'Forums';
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
                                <th>ID</th>
                                <th>User</th>
                                <th>Question</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> <!-- end row-->


    <div class="modal fade" id="editModal" tabindex="-1" data-bs-focus="false" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <form action="#" method="POST" id="edit-form" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span>Edit</span> {{ $label }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body m-1">
                        <input type="hidden" name="id" id="id" class="id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="question" class="form-label">Question </label>
                                    <textarea name="question" class="form-control question" id="question" rows="5"></textarea>                                   
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <select id="status_id" class="form-control status" name="status">
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
@endsection

@section('js')
    <script>
        var apiUrl = "{{ route('admin.forum.list') }}";
        var forumUpdateUrl = "{{ route('admin.forum.update') }}";
        var forumDeleteUrl = "{{ route('admin.forum.delete') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('forum.js') }}"></script>
@endsection
