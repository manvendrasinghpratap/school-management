@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit Guardian</h4>
            <p class="text-muted mb-0">
                Update guardian information.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.guardians.show', $guardian) }}"
               class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i>
                Back to Guardian
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
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
            <h5 class="mb-0">
                <i class="bx bx-user-edit me-1"></i>
                Guardian Information
            </h5>
        </div>

        <div class="card-body">

            @can('guardians.update')

                <form method="POST"
                      action="{{ route('admin.guardians.update', $guardian) }}">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- Guardian Number --}}
                        <div class="col-md-6 mb-3">
                            <label for="guardian_number" class="form-label">
                                Guardian Number <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="guardian_number"
                                name="guardian_number"
                                class="form-control @error('guardian_number') is-invalid @enderror"
                                value="{{ old('guardian_number', $guardian->guardian_number) }}"
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
                            <label for="title" class="form-label">
                                Title
                            </label>

                            <select
                                id="title"
                                name="title"
                                class="form-select @error('title') is-invalid @enderror">

                                <option value="">Select title</option>

                                @foreach(['Mr', 'Mrs', 'Ms', 'Dr', 'Prof'] as $title)
                                    <option
                                        value="{{ $title }}"
                                        @selected(old('title', $guardian->title) === $title)>
                                        {{ $title }}
                                    </option>
                                @endforeach

                            </select>

                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- First Name --}}
                        <div class="col-md-4 mb-3">
                            <label for="first_name" class="form-label">
                                First Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name', $guardian->first_name) }}"
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
                            <label for="middle_name" class="form-label">
                                Middle Name
                            </label>

                            <input
                                type="text"
                                id="middle_name"
                                name="middle_name"
                                class="form-control @error('middle_name') is-invalid @enderror"
                                value="{{ old('middle_name', $guardian->middle_name) }}"
                            >

                            @error('middle_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class="col-md-4 mb-3">
                            <label for="last_name" class="form-label">
                                Last Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name', $guardian->last_name) }}"
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
                            <label for="phone" class="form-label">
                                Phone
                            </label>

                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $guardian->phone) }}"
                            >

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- WhatsApp --}}
                        <div class="col-md-6 mb-3">
                            <label for="whatsapp" class="form-label">
                                WhatsApp
                            </label>

                            <input
                                type="text"
                                id="whatsapp"
                                name="whatsapp"
                                class="form-control @error('whatsapp') is-invalid @enderror"
                                value="{{ old('whatsapp', $guardian->whatsapp) }}"
                            >

                            @error('whatsapp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $guardian->email) }}"
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Occupation --}}
                        <div class="col-md-6 mb-3">
                            <label for="occupation" class="form-label">
                                Occupation
                            </label>

                            <input
                                type="text"
                                id="occupation"
                                name="occupation"
                                class="form-control @error('occupation') is-invalid @enderror"
                                value="{{ old('occupation', $guardian->occupation) }}"
                            >

                            @error('occupation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- State --}}
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">
                                State
                            </label>

                            <input
                                type="text"
                                id="state"
                                name="state"
                                class="form-control @error('state') is-invalid @enderror"
                                value="{{ old('state', $guardian->state) }}"
                            >

                            @error('state')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Local Government --}}
                        <div class="col-md-6 mb-3">
                            <label for="local_government" class="form-label">
                                Local Government
                            </label>

                            <input
                                type="text"
                                id="local_government"
                                name="local_government"
                                class="form-control @error('local_government') is-invalid @enderror"
                                value="{{ old('local_government', $guardian->local_government) }}"
                            >

                            @error('local_government')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div class="col-12 mb-4">
                            <label for="address" class="form-label">
                                Address
                            </label>

                            <textarea
                                id="address"
                                name="address"
                                rows="4"
                                class="form-control @error('address') is-invalid @enderror"
                            >{{ old('address', $guardian->address) }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i>
                            Update Guardian
                        </button>

                        <a href="{{ route('admin.guardians.show', $guardian) }}"
                           class="btn btn-secondary">
                            <i class="bx bx-x me-1"></i>
                            Cancel
                        </a>

                    </div>

                </form>

            @else

                <div class="alert alert-danger mb-0">
                    <i class="bx bx-lock-alt me-1"></i>
                    You do not have permission to update guardians.
                </div>

                <div class="mt-3">
                    <a href="{{ route('admin.guardians.show', $guardian) }}"
                       class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Guardian
                    </a>
                </div>

            @endcan

        </div>
    </div>

</div>
@endsection