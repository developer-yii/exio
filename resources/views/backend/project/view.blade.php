@extends('backend.layouts.app')

@section('title', 'Project Details')

@section('content')
    @php
        $label_main = 'Project Details';
        use App\Models\Project;
    @endphp

    <!-- Page Header -->
    <div class="row mt-3 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="page-title">Project Details</h4>
            <a href="{{ route('admin.project') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left"></i> Back to Projects
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card">
        <div class="card-body">
            <section class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <i class="uil uil-info-circle me-2 text-primary"></i>
                    <h5 class="text-primary mb-0">General Details</h5>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-group">
                            <label class="text-muted">Project Name</label>
                            <p class="mb-3"><strong>{{ $model->project_name }}</strong></p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Project About</label>
                            <p class="mb-3">{!! $model->project_about !!}</p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">City</label>
                            <p class="mb-3">{{ $model->city->city_name ?? '' }}</p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Location</label>
                            <p class="mb-3">
                                {{ $model->location->location_name ?? '' }}
                            </p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Price From</label>
                            <p class="mb-3">
                                {{ $model->price_from }} {{ Project::$priceUnit[$model->price_from_unit] ?? '' }}
                            </p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Price To</label>
                            <p class="mb-3">
                                {{ $model->price_to }} {{ Project::$priceUnit[$model->price_to_unit] ?? '' }}
                            </p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Possession By</label>
                            <p class="mb-3">{{ date('m-Y', strtotime($model->possession_by)) }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-group">
                            <label class="text-muted">Property Type</label>
                            <p class="mb-3">
                                {{ Project::$propertyType[$model->property_type] ?? '' }}
                            </p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Property Sub Type</label>
                            <p class="mb-3">{{ $model->property_sub_types }}</p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">RERA Number</label>
                            <p class="mb-3">{{ $model->rera_number }}</p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Total Floors</label>
                            <p class="mb-3">{{ $model->total_floors }}</p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Total Towers</label>
                            <p class="mb-3">{{ $model->total_tower }}</p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Status</label>
                            <p class="mb-3">
                                <span
                                    class="badge {{ $model->status ? 'badge-success-lighten' : 'badge-danger-lighten' }}">
                                    {{ $model->status_text }}
                                </span>
                            </p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Created at</label>
                            <p class="mb-3">{{ $model->created_at_view }}</p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Updated at</label>
                            <p class="mb-3">{{ $model->updated_at_view }}</p>
                        </div>

                        <div class="info-group">
                            <label class="text-muted">Updated by</label>
                            <p class="mb-3">{{ $model->updated_by_view }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <i class="uil uil-map me-2 text-primary"></i>
                    <h5 class="text-primary mb-0">Master Plan</h5>
                </div>
            </section>

        </div>
    </div>
@endsection

@section('css')
    <style>
        .info-group label {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .info-group p {
            font-size: 1rem;
        }
    </style>
@endsection

@section('js')
    <script>
        var addUpdateUrl = "{{ route('admin.project.addupdate') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('project_view.js') }}"></script>
@endsection
