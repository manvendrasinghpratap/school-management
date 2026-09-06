@extends('backend.layout.default')

@section('title', 'Create User')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1">Create User</h4>
                    <p class="text-muted mb-0">
                        Create a new system user account.
                    </p>
                </div>

                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-secondary">

                    <i class="bx bx-arrow-back me-1"></i>
                    Back to Users

                </a>

            </div>
        </div>
    </div>


    {{-- Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    <div class="row">

        {{-- Main Form --}}
        <div class="col-xl-8 col-lg-8">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">

                        <i class="bx bx-user-plus me-2"></i>

                        User Account Information

                    </h5>
                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.users.store') }}"
                          enctype="multipart/form-data">

                        @csrf


                        {{-- Name --}}
                        <div class="mb-3">

                            <label for="name"
                                   class="form-label">

                                Full Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter full name"
                                   maxlength="255"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="row">

                            {{-- Username --}}
                            <div class="col-md-6 mb-3">

                                <label for="username"
                                       class="form-label">

                                    Username
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text"
                                       name="username"
                                       id="username"
                                       value="{{ old('username') }}"
                                       class="form-control @error('username') is-invalid @enderror"
                                       placeholder="e.g. john.doe"
                                       maxlength="100"
                                       required>

                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Email --}}
                            <div class="col-md-6 mb-3">

                                <label for="email"
                                       class="form-label">

                                    Email Address
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="user@example.com"
                                       required>

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- Password --}}
                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label for="password"
                                       class="form-label">

                                    Password
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       minlength="8"
                                       required>

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Minimum 8 characters.
                                </div>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label for="password_confirmation"
                                       class="form-label">

                                    Confirm Password
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control"
                                       minlength="8"
                                       required>

                            </div>

                        </div>


                        <div class="row">

                            {{-- User Type --}}
                            <div class="col-md-6 mb-3">

                                <label for="user_type_id"
                                       class="form-label">

                                    User Type

                                </label>

                                <select name="user_type_id"
                                        id="user_type_id"
                                        class="form-select @error('user_type_id') is-invalid @enderror">

                                    <option value="">
                                        Select User Type
                                    </option>

                                    @php
                                        $userTypes = DB::table('user_types')
                                            ->where('status', 1)
                                            ->orderBy('name')
                                            ->get();
                                    @endphp

                                    @foreach($userTypes as $userType)

                                        <option value="{{ $userType->id }}"
                                            {{ old('user_type_id') == $userType->id ? 'selected' : '' }}>

                                            {{ $userType->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('user_type_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Designation --}}
                            <div class="col-md-6 mb-3">

                                <label for="designation_id"
                                       class="form-label">

                                    Designation

                                </label>

                                <select name="designation_id"
                                        id="designation_id"
                                        class="form-select @error('designation_id') is-invalid @enderror">

                                    <option value="">
                                        Select Designation
                                    </option>

                                    @php
                                        $designations = DB::table('designations')
                                            ->where('status', 1)
                                            ->where('is_deleted', 0)
                                            ->orderBy('name')
                                            ->get();
                                    @endphp

                                    @foreach($designations as $designation)

                                        <option value="{{ $designation->id }}"
                                            {{ old('designation_id') == $designation->id ? 'selected' : '' }}>

                                            {{ $designation->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('designation_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        <div class="row">

                            {{-- Timezone --}}
                            <div class="col-md-6 mb-3">

                                <label for="timezone"
                                       class="form-label">

                                    Timezone

                                </label>

                                <input type="text"
                                       name="timezone"
                                       id="timezone"
                                       value="{{ old('timezone', auth()->user()->timezone ?? 'Africa/Lagos') }}"
                                       class="form-control @error('timezone') is-invalid @enderror"
                                       placeholder="Africa/Lagos">

                                @error('timezone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Avatar --}}
                            <div class="col-md-6 mb-3">

                                <label for="avatar"
                                       class="form-label">

                                    Profile Photo

                                </label>

                                <input type="file"
                                       name="avatar"
                                       id="avatar"
                                       class="form-control @error('avatar') is-invalid @enderror"
                                       accept=".jpg,.jpeg,.png,.webp">

                                @error('avatar')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    JPG, JPEG, PNG or WEBP. Maximum 2MB.
                                </div>

                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <div class="form-check form-switch">

                                    <input type="hidden"
                                           name="is_active"
                                           value="0">

                                    <input type="checkbox"
                                           name="is_active"
                                           id="is_active"
                                           value="1"
                                           class="form-check-input"
                                           {{ old('is_active', 1) ? 'checked' : '' }}>

                                    <label for="is_active"
                                           class="form-check-label">

                                        <strong>Active Account</strong>

                                    </label>

                                </div>

                                <div class="form-text">
                                    Active users can access the system.
                                </div>

                            </div>


                            <div class="col-md-6 mb-3">

                                <div class="form-check form-switch">

                                    <input type="hidden"
                                           name="is_staff"
                                           value="0">

                                    <input type="checkbox"
                                           name="is_staff"
                                           id="is_staff"
                                           value="1"
                                           class="form-check-input"
                                           {{ old('is_staff') ? 'checked' : '' }}>

                                    <label for="is_staff"
                                           class="form-check-label">

                                        <strong>Staff Account</strong>

                                    </label>

                                </div>

                                <div class="form-text">
                                    Mark this account as belonging to staff.
                                </div>

                            </div>

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between mt-4">

                            <a href="{{ route('admin.users.index') }}"
                               class="btn btn-secondary">

                                <i class="bx bx-x me-1"></i>
                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bx bx-save me-1"></i>
                                Create User

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Information --}}
        <div class="col-xl-4 col-lg-4">

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="bx bx-info-circle me-2"></i>
                        User Account

                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted">
                        A system user account provides access to the
                        School Management System.
                    </p>

                    <hr>

                    <h6>Account Security</h6>

                    <ul class="text-muted ps-3">

                        <li class="mb-2">
                            Use a strong password.
                        </li>

                        <li class="mb-2">
                            Usernames must be unique.
                        </li>

                        <li class="mb-2">
                            Email addresses must be unique.
                        </li>

                        <li>
                            Roles and permissions control system access.
                        </li>

                    </ul>

                    <hr>

                    <h6>Important</h6>

                    <p class="text-muted mb-0">
                        Role assignment will be managed separately
                        using the existing Spatie permission system.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection