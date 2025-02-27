@extends('backend.layouts.app')

@section('title', 'News')

@section('content')

    @php
        $label_main = 'News';
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
                            <input type="hidden" name="id" value="{{ $model->id ?? '' }}">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <input type="text" name="title" class="form-control title"
                                        placeholder="Enter title" id="title"
                                        value="@if (isset($model->id)) {{ $model->title }} @endif">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <!-- slug -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" name="slug" class="form-control slug" placeholder="Enter slug"
                                        id="slug" value="@if (isset($model->id)) {{ $model->slug }} @endif">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control image" accept="image/*"
                                        value="@if (isset($model->id)) {{ $model->image }} @endif">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <input type="text" name="description" class="form-control description"
                                        id="description" placeholder="Enter description"
                                        value="@if (isset($model->id)) {{ $model->description }} @endif">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="content" class="form-label">Content<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <textarea name="content" class="form-control content hide" id="content">
                                @if (isset($model->id))
{{ $model->title }}
@endif
                                </textarea>
                                    <div id="editor_content" style="height: 500px;">
                                        @if (isset($model->id))
                                            {!! $model->content !!}
                                        @endif
                                    </div>
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <select name="status" class="form-control status" id="status_id">
                                        <option value="">Select Status</option>
                                        @if (isset($status) && count($status) > 0)
                                            @foreach ($status as $status_id => $status_name)
                                                <option value="{{ $status_id }}"
                                                    @if (isset($model->id) && $model->status == $status_id) selected @endif>
                                                    {{ $status_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="added_by" class="form-label">Added By<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <input type="text" name="added_by" class="form-control added_by" id="added_by"
                                        placeholder="Enter added by"
                                        value="@if (isset($model->id)) {{ $model->added_by }} @endif">
                                    <span class="error"></span>
                                </div>
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
        var addUpdateUrl = "{{ route('admin.news.addupdate') }}";
        var newsUrl = "{{ route('admin.news') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('addupdate_news.js') }}"></script>
@endsection
