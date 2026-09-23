@extends('backend.layout.default')

@section('title', 'Edit Permission')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Edit Permission
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.permissions.index') }}">
                                Permissions
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit
                        </li>

                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <div class="d-flex align-items-start">

                <i class="bx bx-error-circle font-size-20 me-2"></i>

                <div>
                    <strong>Please correct the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <div class="d-flex align-items-center">

                <i class="bx bx-check-circle font-size-20 me-2"></i>

                <div>
                    {{ session('success') }}
                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif

    {{-- Edit Form --}}
    <div class="row">

        <div class="col-xl-8 col-lg-10">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center mb-4">

                        <div class="avatar-sm me-3">
                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-key"></i>
                            </span>
                        </div>

                        <div>
                            <h4 class="card-title mb-1">
                                Permission Information
                            </h4>

                            <p class="text-muted mb-0">
                                Update the selected system permission.
                            </p>
                        </div>

                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.permissions.update', $permission) }}"
                    >

                        @csrf
                        @method('PUT')

                        <div class="row">

                            {{-- Permission Name --}}
                            <div class="col-md-12 mb-3">

                                <label
                                    for="name"
                                    class="form-label"
                                >
                                    Permission Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $permission->name) }}"
                                    placeholder="e.g. students.view"
                                    maxlength="255"
                                    required
                                    autofocus
                                >

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Use the existing permission naming convention, such as
                                    <strong>students.view</strong>,
                                    <strong>students.create</strong>, or
                                    <strong>students.manage</strong>.
                                </div>

                            </div>

                        </div>

                        <hr class="my-4">

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between">

                            <a
                                href="{{ route('admin.permissions.index') }}"
                                class="btn btn-light"
                            >
                                <i class="bx bx-arrow-back me-1"></i>
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bx bx-save me-1"></i>
                                Update Permission
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        {{-- Information Card --}}
        <div class="col-xl-4 col-lg-2">

            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-3">
                        <i class="bx bx-info-circle text-primary me-1"></i>
                        Important Information
                    </h5>

                    <div class="alert alert-info">

                        <p class="mb-2">
                            <strong>Permission</strong>
                        </p>

                        <p class="mb-0">
                            Changing a permission name may affect routes,
                            middleware, roles, or other parts of the system
                            that reference that permission.
                        </p>

                    </div>

                    <ul class="text-muted ps-3 mb-0">

                        <li class="mb-2">
                            Permission names must remain unique.
                        </li>

                        <li class="mb-2">
                            Use clear and consistent permission names.
                        </li>

                        <li class="mb-2">
                            Check role assignments before changing an
                            existing permission name.
                        </li>

                        <li>
                            Save the permission only after confirming the
                            updated name is correct.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection