@extends('backend.layouts.app')

@section('title', 'Project')

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

        /* Updated accordion styles */
        .custom-accordion-title {
            position: relative;
            color: #6c757d;
            transition: all 0.2s;
            padding-right: 25px;
        }

        .custom-accordion-title::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 10px;
            height: 10px;
            border-right: 2px solid #6c757d;
            border-bottom: 2px solid #6c757d;
            transform: translateY(-70%) rotate(45deg);
            transition: transform 0.3s ease;
        }

        .custom-accordion-title:not(.collapsed)::after {
            transform: translateY(-30%) rotate(-135deg);
        }

        /* Optional: Add hover effect */
        .custom-accordion-title:hover {
            color: #000;
        }

        .custom-accordion-title:hover::after {
            border-color: #000;
        }

        .document-preview-container {
            min-height: 200px;
            border: 1px dashed #dee2e6;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .pdf-preview,
        .no-preview {
            text-align: center;
        }

        .document-preview-container i {
            display: block;
            margin-bottom: 10px;
        }
    </style>
@endsection

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
    <form action="{{ route('admin.project.addupdate') }}" method="POST" id="add-form">
        <input type="hidden" name="id" value="{{ isset($model->id) ? $model->id : '' }}">
        <div class="row">
            <div class="accordion" id="projectAccordion">
                <div class="card border-1 ">
                    <div class="card-header" id="headingGeneralDetails">
                        <h5 class="m-0">
                            <a class="custom-accordion-title d-block pt-2 pb-2" data-bs-toggle="collapse"
                                href="#collapseGeneralDetails" aria-expanded="true" aria-controls="collapseGeneralDetails">
                                General Details
                            </a>
                        </h5>
                    </div>

                    <div id="collapseGeneralDetails" class="collapse show" aria-labelledby="headingGeneralDetails"
                        data-bs-parent="#projectAccordion">
                        <div class="card-body">
                            <div class="row">
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
                                            min="0"
                                            value="{{ isset($model->price_from) ? $model->price_from : '' }}">
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
                                        <input type="number" name="price_to" class="form-control price_to"
                                            min="0" value="{{ isset($model->price_to) ? $model->price_to : '' }}">
                                        <span class="error"></span>
                                    </div>
                                </div>

                                <!-- Price To Unit -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="price_to_unit" class="form-label">Price To Unit</label>
                                        <select id="price_to_unit" class="form-control price_to_unit"
                                            name="price_to_unit">
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

                                <!-- Carpet Area -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="carpet_area" class="form-label">Carpet Area</label>
                                        <input type="number" name="carpet_area" class="form-control carpet_area"
                                            min="0"
                                            value="{{ isset($model->carpet_area) ? $model->carpet_area : '' }}">
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
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Project Details Fields</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" id="project_detail_fields">
                                                <div class="col-md-4 mb-3">
                                                    <a href="javascript:void(0);"
                                                        class="add-more-project-detail text-decoration-none">
                                                        <div class="card border-dashed h-100">
                                                            <div
                                                                class="card-body text-center d-flex flex-column justify-content-center">
                                                                <i class="uil uil-plus-circle text-muted"
                                                                    style="font-size: 24px;"></i>
                                                                <span class="text-muted mt-2">Add Project Detail</span>
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
                    </div>
                </div>
                <div class="card border-1">
                    <div class="card-header" id="headingMasterPlan">
                        <h5 class="m-0">
                            <a class="custom-accordion-title collapsed d-block pt-2 pb-2" data-bs-toggle="collapse"
                                href="#collapseMasterPlan" aria-expanded="false" aria-controls="collapseMasterPlan">
                                Master Plan
                            </a>
                        </h5>
                    </div>

                    <div id="collapseMasterPlan" class="collapse" aria-labelledby="headingMasterPlan"
                        data-bs-parent="#projectAccordion">
                        <div class="card-body">
                            <!-- Master Plans Section -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Master Plan Details</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row" id="master_plan_container">
                                        <!-- Existing master plans will be dynamically added here -->

                                        <!-- Add More Button -->
                                        <div class="col-md-4 mb-3">
                                            <a href="javascript:void(0);"
                                                class="add-more-master-plan text-decoration-none">
                                                <div class="card border-dashed h-100">
                                                    <div
                                                        class="card-body text-center d-flex flex-column justify-content-center">
                                                        <i class="uil uil-plus-circle text-muted"
                                                            style="font-size: 24px;"></i>
                                                        <span class="text-muted mt-2">Add Master Plan</span>
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
                <div class="card border-1">
                    <div class="card-header" id="headingFloorPlan">
                        <h5 class="m-0">
                            <a class="custom-accordion-title collapsed d-block pt-2 pb-2" data-bs-toggle="collapse"
                                href="#collapseFloorPlan" aria-expanded="false" aria-controls="collapseFloorPlan">
                                Floor Plan
                            </a>
                        </h5>
                    </div>

                    <div id="collapseFloorPlan" class="collapse" aria-labelledby="headingFloorPlan"
                        data-bs-parent="#projectAccordion">
                        <div class="card-body">
                            <!-- Floor Plans Section -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="row" id="floor-plans-container">
                                        <div class="col-md-4 mb-3">
                                            <a href="javascript:void(0);"
                                                class="add-more-floor-plan text-decoration-none">
                                                <div class="card border-dashed h-100">
                                                    <div
                                                        class="card-body text-center d-flex flex-column justify-content-center">
                                                        <i class="uil uil-plus-circle text-muted"
                                                            style="font-size: 24px;"></i>
                                                        <span class="text-muted mt-2">Add Floor Plan</span>
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
                <div class="card border-1">
                    <div class="card-header" id="headingAmenities">
                        <h5 class="m-0">
                            <a class="custom-accordion-title collapsed d-block pt-2 pb-2" data-bs-toggle="collapse"
                                href="#collapseAmenities" aria-expanded="false" aria-controls="collapseAmenities">
                                Amenities
                            </a>
                        </h5>
                    </div>

                    <div id="collapseAmenities" class="collapse" aria-labelledby="headingAmenities"
                        data-bs-parent="#projectAccordion">
                        <div class="card-body">
                            <!-- Amenities Section -->
                            <div class="row" id="amenities-container">
                                <!-- Amenities Section Multiple Select of Amenities -->
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <select id="amenities" class="form-control amenities select2"
                                            data-toggle="select2" name="amenities[]" data-placeholder="Select Amenities"
                                            multiple>
                                            @if (isset($amenities))
                                                @foreach ($amenities as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (isset($model->amenities) && in_array($key, $model->amenities)) selected @endif>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Property Documents -->
                <div class="card border-1">
                    <div class="card-header" id="headingPropertyDocuments">
                        <h5 class="m-0">
                            <a class="custom-accordion-title collapsed d-block pt-2 pb-2" data-bs-toggle="collapse"
                                href="#collapsePropertyDocuments" aria-expanded="false"
                                aria-controls="collapsePropertyDocuments">
                                Property Documents
                            </a>
                        </h5>
                    </div>

                    <div id="collapsePropertyDocuments" class="collapse" aria-labelledby="headingPropertyDocuments"
                        data-bs-parent="#projectAccordion">
                        <div class="card-body">
                            <!-- Property Documents Section -->
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Document Title -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Document Title</label>
                                        <input type="text" class="form-control property_document_title"
                                            name="property_document_title" placeholder="Enter Document Title"
                                            value="{{ isset($model->property_document_title) ? $model->property_document_title : '' }}">
                                        <span class="error"></span>
                                    </div>

                                    <!-- Document File -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Upload Document</label>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <input type="file" class="form-control property_document"
                                                    name="property_document" accept=".pdf" data-toggle="tooltip"
                                                    data-placement="top" title="Upload document">
                                                <div class="form-text">Allowed formats: PDF</div>
                                            </div>
                                            <div class="col-md-3">
                                                @if (isset($model->property_document) && !empty($model->property_document))
                                                    <a href="{{ asset('storage/property_documents/' . $model->property_document) }}"
                                                        class="btn btn-info w-100"
                                                        download="{{ $model->property_document }}">
                                                        <i class="uil uil-download-alt"></i> View
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="error"></span>
                                    </div>
                                </div>

                                <!-- Preview Section -->
                                <div class="col-md-6">
                                    {{-- <div class="document-preview-container text-center">
                                        @if (isset($model->property_document) && !empty($model->property_document))
                                            <div class="pdf-preview">
                                                <i class="uil uil-file-alt text-muted" style="font-size: 48px;"></i>
                                                <p class="mt-2 mb-0">{{ $model->property_document }}</p>
                                            </div>
                                        @else
                                            <div class="no-preview text-muted">
                                                <i class="uil uil-file-upload-alt" style="font-size: 48px;"></i>
                                                <p class="mt-2 mb-0">No document uploaded</p>
                                            </div>
                                        @endif
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Locality Add More  -->
                <div class="card border-1">
                    <div class="card-header" id="headingLocality">
                        <h5 class="m-0">
                            <a class="custom-accordion-title collapsed d-block pt-2 pb-2" data-bs-toggle="collapse"
                                href="#collapseLocality" aria-expanded="false" aria-controls="collapseLocality">
                                Locality
                            </a>
                        </h5>
                    </div>

                    <div id="collapseLocality" class="collapse" aria-labelledby="headingLocality"
                        data-bs-parent="#projectAccordion">
                        <div class="card-body">
                            <!-- Locality Section -->
                            <div class="row" id="locality_container">
                                <div class="col-md-4 mb-3">
                                    <a href="javascript:void(0);" class="add-more-locality text-decoration-none">
                                        <div class="card border-dashed h-100">
                                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                                <i class="uil uil-plus-circle text-muted" style="font-size: 24px;"></i>
                                                <span class="text-muted mt-2">Add Locality</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row my-4">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success" id="addorUpdateBtn">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
        @if (isset($existingFloorPlans))
            var existingFloorPlans = {!! json_encode($existingFloorPlans) !!};
        @endif
        @if (isset($model->property_sub_types))
            var selectedPropertySubTypes = {!! json_encode($model->property_sub_types) !!};
        @endif
        @if (isset($locality))
            var locality = {!! json_encode($locality) !!};
        @endif
        @if (isset($existingLocalities))
            var existingLocalities = {!! json_encode($existingLocalities) !!};
        @endif
        var assetUrl = "{{ asset('') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('project_addupdate.js') }}"></script>
    <script src="{{ addPageJsLink('addmore/project-detail-row.js') }}"></script>
    <script src="{{ addPageJsLink('addmore/master_plan_fields.js') }}"></script>
    <script src="{{ addPageJsLink('addmore/floor_plan_fields.js') }}"></script>
    <script src="{{ addPageJsLink('addmore/locality_fields.js') }}"></script>
@endsection
