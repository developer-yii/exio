@extends('backend.layouts.app')
@section('title', $page->page_label)
@section('content')

    <!-- start page title -->
    <div class="row mt-3 mb-3">
        <div class="col-md-6 col-lg-6 col-xl-6">
            <h4 class="page-title">{{ $page->page_label }}</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-6"></div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="#" method="POST" id="add-form">
                        <input type="hidden" name="page" value="{{$page->id}}" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title<span class="text-danger add_edit_required">*</span></label>
                                    <input type="text" name="title" class="form-control title" value="@if(isset($page->id)) {{$page->title}} @endif">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <textarea name="content" class="form-control content hide" id="content">@if(isset($page->id)) {!!$page->content!!} @endif</textarea>
                                {{-- <div id="editor_content" style="height: 500px;">@if(isset($page->id)) {!!$page->content!!} @endif</div>
                                <span class="error"></span> --}}
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
        var addUpdateUrl = "{{ route('admin.page.update') }}";
        var uploadImageUrl = "{{ route('admin.page.image') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('cmspages.js') }}"></script>
@endsection
