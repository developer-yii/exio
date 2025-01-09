@extends('backend.layouts.app')

@section('title', 'Privacy Policies')

@section('content')

    @php
        $label_main = 'Privacy Policies';
    @endphp

    <!-- start page title -->
    <div class="row mt-3 mb-3">
        <div class="col-md-6 col-lg-6 col-xl-6">
            <h4 class="page-title">{{ $label_main }}</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-6"></div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="#" method="POST" id="add-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title<span class="text-danger add_edit_required">*</span></label>
                                    <input type="text" name="title" class="form-control title" value="@if(isset($model->id)) {{$model->title}} @endif">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <textarea name="content" class="form-control content hide" id="content">@if(isset($model->id)) {{$model->title}} @endif</textarea>
                                <div id="editor_content" style="height: 500px;">@if(isset($model->id)) {!!$model->content!!} @endif</div>
                            </div>
                            <div class="col-md-12 pt-3">
                                <button type="submit" class="btn btn-success float-end" id="addorUpdateBtn">Save</button>
                            </div>
                        </div>
                    </form>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> <!-- end row-->

@endsection

@section('js')
    <script>
        var addUpdateUrl = "{{ route('admin.privacy_policie.addupdate') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('privacy_policie.js') }}"></script>
@endsection
