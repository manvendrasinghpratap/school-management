@extends('backend.layout.default')

@section('title', 'Create Academic Year')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Create Academic Year
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.academic-years.index') }}">
                                Academic Years
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create
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

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif

    {{-- Create Form --}}
    <div class="row">

        <div class="col-xl-8 col-lg-10">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center mb-4">

                        <div class="avatar-sm me-3">
                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-calendar-plus"></i>
                            </span>
                        </div>

                        <div>
                            <h4 class="card-title mb-1">
                                Academic Year Information
                            </h4>

                            <p class="text-muted mb-0">
                                Create a new academic year for your school.
                            </p>
                        </div>

                    </div>

                    <form method="POST"
                          action="{{ route('admin.academic-years.store') }}">

                        @csrf

                        <div class="row">

                            {{-- Academic Year Name --}}
                            <div class="col-md-12 mb-3">

                                <label for="name"
                                       class="form-label">
                                    Academic Year Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       id="name"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="e.g. 2026/2027"
                                       maxlength="100"
                                       required
                                       autofocus>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Enter a unique academic year name, for example
                                    <strong>2026/2027</strong>.
                                </div>

                            </div>

                            {{-- Start Date --}}
                            <div class="col-md-6 mb-3">

                                <label for="start_date"
                                       class="form-label">
                                    Start Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       id="start_date"
                                       name="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date') }}"
                                       required>

                                @error('start_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- End Date --}}
                            <div class="col-md-6 mb-3">

                                <label for="end_date"
                                       class="form-label">
                                    End Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       id="end_date"
                                       name="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date') }}"
                                       required>

                                @error('end_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Current --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-check form-switch">

                                    <input type="hidden"
                                           name="is_current"
                                           value="0">

                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="is_current"
                                           name="is_current"
                                           value="1"
                                           {{ old('is_current') ? 'checked' : '' }}>

                                    <label class="form-check-label"
                                           for="is_current">

                                        Set as Current Academic Year

                                    </label>

                                </div>

                                <small class="text-muted">
                                    Setting this as current will automatically make
                                    other academic years non-current.
                                </small>

                            </div>

                            {{-- Active --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-check form-switch">

                                    <input type="hidden"
                                           name="is_active"
                                           value="0">

                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}>

                                    <label class="form-check-label"
                                           for="is_active">

                                        Active Academic Year

                                    </label>

                                </div>

                                <small class="text-muted">
                                    Inactive academic years cannot normally be used
                                    for new academic operations.
                                </small>

                            </div>

                        </div>

                        <hr class="my-4">

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between">

                            <a href="{{ route('admin.academic-years.index') }}"
                               class="btn btn-light">

                                <i class="bx bx-arrow-back me-1"></i>
                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bx bx-save me-1"></i>
                                Create Academic Year

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
                            <strong>Academic Year</strong>
                        </p>

                        <p class="mb-0">
                            An academic year defines the main school period during
                            which students are enrolled and academic activities
                            take place.
                        </p>

                    </div>

                    <ul class="text-muted ps-3 mb-0">

                        <li class="mb-2">
                            The name must be unique within your school.
                        </li>

                        <li class="mb-2">
                            The end date must be after the start date.
                        </li>

                        <li class="mb-2">
                            Only one academic year can be current at a time.
                        </li>

                        <li>
                            Terms will be configured under the academic year.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection