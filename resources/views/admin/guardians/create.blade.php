@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Register Parent / Guardian</h1>
            <p class="text-muted mb-0">
                Add a new parent or guardian to the school.
            </p>
        </div>

        <a
            href="{{ route('admin.guardians.index') }}"
            class="btn btn-secondary"
        >
            Back to Guardians
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Guardian Information</h5>
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.guardians.store') }}"
            >
                @csrf

                <div class="row">

                    {{-- Guardian Number --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="guardian_number"
                            class="form-label"
                        >
                            Guardian Number <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="guardian_number"
                            id="guardian_number"
                            class="form-control @error('guardian_number') is-invalid @enderror"
                            value="{{ old('guardian_number') }}"
                            required
                        >

                        @error('guardian_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Title --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="title"
                            class="form-label"
                        >
                            Title
                        </label>

                        <select
                            name="title"
                            id="title"
                            class="form-select @error('title') is-invalid @enderror"
                        >
                            <option value="">Select title</option>
                            <option value="Mr." @selected(old('title') === 'Mr.')>Mr.</option>
                            <option value="Mrs." @selected(old('title') === 'Mrs.')>Mrs.</option>
                            <option value="Miss" @selected(old('title') === 'Miss')>Miss</option>
                            <option value="Ms." @selected(old('title') === 'Ms.')>Ms.</option>
                            <option value="Dr." @selected(old('title') === 'Dr.')>Dr.</option>
                            <option value="Prof." @selected(old('title') === 'Prof.')>Prof.</option>
                        </select>

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- First Name --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="first_name"
                            class="form-label"
                        >
                            First Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="first_name"
                            id="first_name"
                            class="form-control @error('first_name') is-invalid @enderror"
                            value="{{ old('first_name') }}"
                            required
                        >

                        @error('first_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Middle Name --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="middle_name"
                            class="form-label"
                        >
                            Middle Name
                        </label>

                        <input
                            type="text"
                            name="middle_name"
                            id="middle_name"
                            class="form-control @error('middle_name') is-invalid @enderror"
                            value="{{ old('middle_name') }}"
                        >

                        @error('middle_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Last Name --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="last_name"
                            class="form-label"
                        >
                            Last Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="last_name"
                            id="last_name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            value="{{ old('last_name') }}"
                            required
                        >

                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="phone"
                            class="form-label"
                        >
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}"
                        >

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- WhatsApp --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="whatsapp"
                            class="form-label"
                        >
                            WhatsApp
                        </label>

                        <input
                            type="text"
                            name="whatsapp"
                            id="whatsapp"
                            class="form-control @error('whatsapp') is-invalid @enderror"
                            value="{{ old('whatsapp') }}"
                        >

                        @error('whatsapp')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Occupation --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="occupation"
                            class="form-label"
                        >
                            Occupation
                        </label>

                        <input
                            type="text"
                            name="occupation"
                            id="occupation"
                            class="form-control @error('occupation') is-invalid @enderror"
                            value="{{ old('occupation') }}"
                        >

                        @error('occupation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- State --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="state"
                            class="form-label"
                        >
                            State
                        </label>

                        <input
                            type="text"
                            name="state"
                            id="state"
                            class="form-control @error('state') is-invalid @enderror"
                            value="{{ old('state') }}"
                        >

                        @error('state')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Local Government --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="local_government"
                            class="form-label"
                        >
                            Local Government
                        </label>

                        <input
                            type="text"
                            name="local_government"
                            id="local_government"
                            class="form-control @error('local_government') is-invalid @enderror"
                            value="{{ old('local_government') }}"
                        >

                        @error('local_government')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Address --}}
                    <div class="col-12 mb-4">

                        <label
                            for="address"
                            class="form-label"
                        >
                            Address
                        </label>

                        <textarea
                            name="address"
                            id="address"
                            rows="4"
                            class="form-control @error('address') is-invalid @enderror"
                        >{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Register Guardian
                    </button>

                    <a
                        href="{{ route('admin.guardians.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection