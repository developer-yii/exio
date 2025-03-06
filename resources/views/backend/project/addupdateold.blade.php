@extends('backend.layouts.app')

@section('title', 'Project')

@section('content')

    @php
        $label_main = 'Project';
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
                    <form action="{{ route('admin.project.addupdate') }}" method="POST" id="add-form">
                        <input type="hidden" name="id" value="{{ isset($model->id) ? $model->id : '' }}">
                        <div class="row">
                            <!-- General Details Section -->
                            <div class="col-md-12 mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <h4 class="mb-0">General Details</h4>
                                </div>
                                <hr>
                            </div>

                            <!-- Project Name -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="project_name" class="form-label">Project Name<span
                                            class="text-danger add_edit_required">*</span></label>
                                    <input type="text" name="project_name" class="form-control project_name"
                                        value="@if (isset($model->id)) {{ $model->project_name }} @endif">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Project About -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="project_about" class="form-label">Project About</label>
                                    <div id="project_about" style="height: 100px;">
                                        @if (isset($model->id) && !empty($model->project_about))
                                            {!! $model->project_about !!}
                                        @endif
                                    </div>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- City -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="city_id" class="form-label">City</label>
                                    <select id="city_id" class="form-control city_id" name="city_id">
                                        <option value="">Select City</option>
                                        @foreach ($city as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (isset($model->city_id) && $model->city_id == $key) selected @endif>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Area -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="area_id" class="form-label">Area</label>
                                    <select id="area_id" class="form-control area_id" name="area_id">
                                        <option value="">Select Area</option>
                                        @foreach ($area as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (isset($model->location_id) && $model->location_id == $key) selected @endif>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Builder -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="builder_id" class="form-label">Builder</label>
                                    <select id="builder_id" class="form-control builder_id" name="builder_id">
                                        <option value="">Select Builder</option>
                                        @foreach ($builder as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (isset($model->builder_id) && $model->builder_id == $key) selected @endif>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Property Type -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Property Type</label>
                                    <div id="property_type">
                                        @foreach ($propertyTypes as $key => $value)
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="property_type" value="{{ $key }}"
                                                    class="form-check-input" id="property_type_{{ $key }}"
                                                    @if ((isset($model->property_type) && $model->property_type == $key) || $key == 'residential') checked @endif>
                                                <label class="form-check-label"
                                                    for="property_type_{{ $key }}">{{ $value }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Property Sub Types -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="property_sub_types" class="form-label">Property Sub Types</label>
                                    <select id="property_sub_types" class="form-control property_sub_types"
                                        name="property_sub_types">
                                        <option value="">Select Property Sub Type</option>
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Possession By -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="possession_by" class="form-label">Possession By</label>
                                    <input type="month" name="possession_by" class="form-control possession_by"
                                        value="{{ isset($model->possession_by) ? date('Y-m', strtotime($model->possession_by)) : '' }}">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- RERA Number -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="rera_number" class="form-label">RERA Number</label>
                                    <input type="text" name="rera_number" class="form-control rera_number"
                                        value="{{ isset($model->rera_number) ? $model->rera_number : '' }}">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Price From -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price_from" class="form-label">Price From</label>
                                    <input type="number" name="price_from" class="form-control price_from"
                                        min="0" value="{{ isset($model->price_from) ? $model->price_from : '' }}">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Price From Unit -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price_from_unit" class="form-label">Price From Unit</label>
                                    <select id="price_from_unit" class="form-control price_from_unit"
                                        name="price_from_unit">
                                        <option value="">Select Price From Unit</option>
                                        @foreach ($priceUnit as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (isset($model->price_from_unit) && $model->price_from_unit == $key) selected @endif>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Price To -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price_to" class="form-label">Price To</label>
                                    <input type="number" name="price_to" class="form-control price_to" min="0"
                                        value="{{ isset($model->price_to) ? $model->price_to : '' }}">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Price To Unit -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price_to_unit" class="form-label">Price To Unit</label>
                                    <select id="price_to_unit" class="form-control price_to_unit" name="price_to_unit">
                                        <option value="">Select Price To Unit</option>
                                        @foreach ($priceUnit as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (isset($model->price_to_unit) && $model->price_to_unit == $key) selected @endif>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Total Floors -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="total_floors" class="form-label">Total Floors</label>
                                    <input type="number" name="total_floors" class="form-control total_floors"
                                        min="0"
                                        value="{{ isset($model->total_floors) ? $model->total_floors : '' }}">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Total Tower -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="total_tower" class="form-label">Total Tower</label>
                                    <input type="number" name="total_tower" class="form-control total_tower"
                                        min="0"
                                        value="{{ isset($model->total_tower) ? $model->total_tower : '' }}">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Age Of Construction -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="age_of_construction" class="form-label">Age Of Construction</label>
                                    <select id="age_of_construction" class="form-control age_of_construction"
                                        name="age_of_construction">
                                        <option value="">Select Age Of Construction</option>
                                        @if (isset($ageOfConstruction))
                                            @foreach ($ageOfConstruction as $key => $value)
                                                <option value="{{ $key }}"
                                                    @if (isset($model->age_of_construction) && $model->age_of_construction == $key) selected @endif>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Project Status -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="project_status" class="form-label">Project Status</label>
                                    <select id="project_status" class="form-control project_status"
                                        name="project_status">
                                        <option value="">Select Project Status</option>
                                        @if (isset($projectStatus))
                                            @foreach ($projectStatus as $key => $value)
                                                <option value="{{ $key }}"
                                                    @if (isset($model->status) && $model->status == $key) selected @endif>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <!-- Add More of Project Detail Fields -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Add More of Project Detail Fields</label>
                                    <div id="project_detail_fields">
                                        {{-- <div class="row mb-2 project-detail-row">
                                            <input type="hidden" name="project_detail[0][id]" value="">
                                            <div class="col-md-6">
                                                <input type="text" name="project_detail[0][name]"
                                                    class="form-control project_detail_0_name" placeholder="Enter Name">
                                                <span class="error"></span>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="project_detail[0][value]"
                                                    class="form-control project_detail_0_value" placeholder="Enter Value">
                                                <span class="error"></span>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-primary add-more-field">
                                                    <i class="uil uil-plus"></i>
                                                </button>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Master Plan Section -->
                            <div class="col-md-12 mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <h4 class="mb-0">Master Plan</h4>
                                </div>
                                <hr>
                            </div>

                            <!-- Master Plan Add More -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Add More of Master Plan Fields</label>
                                    <div id="master_plan_fields">
                                        {{-- <div class="row mb-2 master-plan-row">
                                            <input type="hidden" name="master_plan[0][id]" value="">
                                            <div class="col-md-5">
                                                <input type="text" name="master_plan[0][name]"
                                                    class="form-control master_plan_0_name" placeholder="Enter Title">
                                                <span class="error"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="file" name="master_plan[0][2d_image]"
                                                    class="form-control master_plan_0_2d_image" accept="image/*">
                                                <span class="error"></span>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="file" name="master_plan[0][3d_image]"
                                                    class="form-control master_plan_0_3d_image" accept="image/*">
                                                <span class="error"></span>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-primary add-more-master-plan">
                                                    <i class="uil uil-plus"></i>
                                                </button>
                                            </div>
                                            </input>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-success" id="addorUpdateBtn">Save</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        var addUpdateUrl = "{{ route('admin.project.addupdate') }}";
        var getPropertySubTypesUrl = "{{ route('admin.project.get-property-sub-types') }}";
        var projectUrl = "{{ route('admin.project') }}";
        @if (isset($existingProjectDetails))
            var existingProjectDetails = {!! json_encode($existingProjectDetails) !!};
        @endif
        @if (isset($existingMasterPlans))
            var existingMasterPlans = {!! json_encode($existingMasterPlans) !!};
        @endif
        @if (isset($model->property_sub_types))
            var selectedPropertySubTypes = {!! json_encode($model->property_sub_types) !!};
        @endif
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('project_addupdate.js') }}"></script>
    <script src="{{ addPageJsLink('addmore/project-detail-row.js') }}"></script>
    <script src="{{ addPageJsLink('addmore/master_plan_fields.js') }}"></script>
@endsection
