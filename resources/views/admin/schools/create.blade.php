@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">School Setup</h4>
            <p class="text-muted mb-0">
                Enter your institution information to complete the initial setup.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.school.setup.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            School Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">
                            School Code
                        </label>

                        <input
                            type="text"
                            name="code"
                            id="code"
                            class="form-control"
                            value="{{ old('code') }}"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="registration_number" class="form-label">
                            Registration Number
                        </label>

                        <input
                            type="text"
                            name="registration_number"
                            id="registration_number"
                            class="form-control"
                            value="{{ old('registration_number') }}"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="logo" class="form-label">
                            School Logo
                        </label>

                        <input
                            type="file"
                            name="logo"
                            id="logo"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <small class="text-muted">
                            JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                        </small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">
                            Address
                        </label>

                        <textarea
                            name="address"
                            id="address"
                            class="form-control"
                            rows="3"
                        >{{ old('address') }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="phone" class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            class="form-control"
                            value="{{ old('phone') }}"
                        >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            value="{{ old('email') }}"
                        >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="website" class="form-label">
                            Website
                        </label>

                        <input
                            type="url"
                            name="website"
                            id="website"
                            class="form-control"
                            value="{{ old('website') }}"
                            placeholder="https://example.com"
                        >
                    </div>

                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        Complete School Setup
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection