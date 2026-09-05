@extends('backend.layout.default')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>School / Institution Setup</h1>

        <a
            href="{{ route('admin.school.settings.edit', $school) }}"
            class="btn btn-outline-primary"
        >
            System Settings
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('admin.school.update', $school) }}"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        {{-- Institution Information --}}
        <div class="card mb-4">

            <div class="card-header">
                <strong>Institution Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            Institution Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $school->name) }}"
                            maxlength="255"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">
                            School Code
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code', $school->code) }}"
                            maxlength="100"
                        >

                        @error('code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="registration_number" class="form-label">
                            Registration Number
                        </label>

                        <input
                            type="text"
                            id="registration_number"
                            name="registration_number"
                            class="form-control @error('registration_number') is-invalid @enderror"
                            value="{{ old('registration_number', $school->registration_number) }}"
                            maxlength="255"
                        >

                        @error('registration_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

            </div>
        </div>


        {{-- Contact Information --}}
        <div class="card mb-4">

            <div class="card-header">
                <strong>Contact Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $school->email) }}"
                            maxlength="255"
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="phone" class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $school->phone) }}"
                            maxlength="30"
                        >

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="website" class="form-label">
                            Website
                        </label>

                        <input
                            type="url"
                            id="website"
                            name="website"
                            class="form-control @error('website') is-invalid @enderror"
                            value="{{ old('website', $school->website) }}"
                            maxlength="255"
                            placeholder="https://example.com"
                        >

                        @error('website')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

            </div>
        </div>


        {{-- Address --}}
        <div class="card mb-4">

            <div class="card-header">
                <strong>Institution Address</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <label for="address" class="form-label">
                        Address
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        class="form-control @error('address') is-invalid @enderror"
                        rows="4"
                        maxlength="2000"
                        placeholder="Enter the complete institution address"
                    >{{ old('address', $school->address) }}</textarea>

                    @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>
        </div>


        {{-- School Logo --}}
        <div class="card mb-4">

            <div class="card-header">
                <strong>School Logo</strong>
            </div>

            <div class="card-body">

                @if($school->logo)

                    <div class="mb-3">

                        <p class="mb-2">
                            Current Logo:
                        </p>

                        <img
                            src="{{ Storage::url($school->logo) }}"
                            alt="{{ $school->name }} Logo"
                            style="max-height: 120px; max-width: 250px;"
                            class="img-thumbnail"
                        >

                    </div>

                @endif

                <label for="logo" class="form-label">
                    {{ $school->logo ? 'Replace Logo' : 'Upload Logo' }}
                </label>

                <input
                    type="file"
                    id="logo"
                    name="logo"
                    class="form-control @error('logo') is-invalid @enderror"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                @error('logo')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <small class="text-muted">
                    JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                </small>

            </div>
        </div>


        {{-- Actions --}}
        <div class="d-flex gap-2 mb-4">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save School Information
            </button>

            <a
                href="{{ route('admin.school.settings.edit', $school) }}"
                class="btn btn-outline-secondary"
            >
                System Settings
            </a>

        </div>

    </form>

</div>

@endsection