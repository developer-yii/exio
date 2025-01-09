@extends('backend.layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row mt-3 mb-3">
        <div class="col-md-2">
            <h4 class="page-title">Dashboard</h4>
        </div>
        <div class="col-md-10"></div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="uil-users-alt widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Users</h5>
                            <h3 class="mt-3 mb-3">1500</h3>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->

                <div class="col-sm-3">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="uil-layer-group widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Property</h5>
                            <h3 class="mt-3 mb-3">1 0199</h3>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->

            </div> <!-- end row -->
        </div>

    </div>
@endsection
