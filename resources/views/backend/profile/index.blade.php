@extends('backend.layouts.app')

@section('title', 'Profile')

@section('content')

    @php
        $label = 'Profile';
    @endphp

    <!-- start page title -->
    <div class="row mt-3 mb-3">
        <div class="col-md-2">
            <h4 class="page-title">{{ $label }}</h4>
        </div>
        <div class="col-md-10"></div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="header-title">USER INFORMATION</h4>
                    </div>
                    <div class="inbox-widget">
                        <form action="#" method="POST" id="form-profile">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="first_name" class="form-label">First Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control first_name"
                                            value="{{ $loginUser->first_name }}">
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="last_name" class="form-label">Last Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control last_name"
                                            value="{{ $loginUser->last_name }}">
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="email" class="form-control email"
                                            value="{{ $loginUser->email }}">
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="mobile" class="form-label">Mobile</label>
                                        <input type="text" name="mobile" class="form-control mobile"
                                            value="{{ $loginUser->mobile }}">
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success float-end"
                                        id="addorUpdateBtn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="header-title">MANAGE PASSWORD</h4>
                    </div>
                    <div class="inbox-widget">
                        <form action="#" method="POST" id="form-password">
                            <div class="row">
                                <div class="col-md-12 password_input">
                                    <div class="form-group mb-3">
                                        <label for="current_password" class="form-label">Current Password<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" name="current_password" class="form-control current_password" value="">
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-md-12 password_input">
                                    <div class="form-group mb-3">
                                        <label for="new_password" class="form-label">Password<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" name="new_password" class="form-control new_password" value="">
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-md-12 password_input">
                                    <div class="form-group mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" name="confirm_password" class="form-control confirm_password" value="">
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success float-end" id="addorUpdateBtn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        var profileupdateUrl = "{{ route('admin.profile.profileupdate') }}";
        var updatepasswordUrl = "{{ route('admin.profile.updatepassword') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{ addPageJsLink('profile.js') }}"></script>
@endsection
