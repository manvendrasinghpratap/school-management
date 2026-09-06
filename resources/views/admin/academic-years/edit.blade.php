@extends('backend.layout.default')

@section('title', 'Edit Academic Year')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Edit Academic Year
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

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.academic-years.show', $academicYear) }}">
                                {{ $academicYear->name }}
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

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Edit Form --}}
    <div class="row">

        <div class="col-xl-8 col-lg-10">

            <div class="card">

                <div class="card-body">

                    {{-- Card Header --}}
                    <div class="d-flex align-items-center mb-4">

                        <div class="avatar-sm me-3">

                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">

                                <i class="bx bx-edit-alt"></i>

                            </span>

                        </div>

                        <div>

                            <h4 class="card-title mb-1">
                                Edit Academic Year
                            </h4>

                            <p class="text-muted mb-0">
                                Update the academic year information below.
                            </p>

                        </div>

                    </div>


                    <form method="POST"
                          action="{{ route('admin.academic-years.update', $academicYear) }}">

                        @csrf
                        @method('PUT')


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
                                       value="{{ old('name', $academicYear->name) }}"
                                       maxlength="100"
                                       required>

                                @error('name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="form-text">
                                    The academic year name must be unique within your school.
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
                                       value="{{ old('start_date', $academicYear->start_date?->format('Y-m-d')) }}"
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
                                       value="{{ old('end_date', $academicYear->end_date?->format('Y-m-d')) }}"
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
                                           {{ old('is_current', $academicYear->is_current) ? 'checked' : '' }}>

                                    <label class="form-check-label"
                                           for="is_current">

                                        Set as Current Academic Year

                                    </label>

                                </div>

                                <small class="text-muted">

                                    Only one academic year can be current at a time.
                                    Enabling this will make other academic years non-current.

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
                                           {{ old('is_active', $academicYear->is_active) ? 'checked' : '' }}>

                                    <label class="form-check-label"
                                           for="is_active">

                                        Active Academic Year

                                    </label>

                                </div>

                                <small class="text-muted">

                                    Inactive academic years should normally not be used
                                    for new academic operations.

                                </small>

                            </div>

                        </div>


                        {{-- Existing Terms Warning --}}
                        @if($academicYear->terms()->exists())

                            <div class="alert alert-warning mt-3">

                                <div class="d-flex align-items-start">

                                    <i class="bx bx-info-circle font-size-20 me-2"></i>

                                    <div>

                                        <strong>
                                            This academic year has configured terms.
                                        </strong>

                                        <p class="mb-0 mt-1">
                                            Be careful when changing the academic year
                                            dates because existing terms may depend on
                                            this period.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @endif


                        <hr class="my-4">


                        {{-- Actions --}}
                        <div class="d-flex justify-content-between">

                            <a href="{{ route('admin.academic-years.show', $academicYear) }}"
                               class="btn btn-light">

                                <i class="bx bx-arrow-back me-1"></i>
                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bx bx-save me-1"></i>
                                Save Changes

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Information Sidebar --}}
        <div class="col-xl-4 col-lg-2">

            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-4">

                        <i class="bx bx-info-circle text-primary me-1"></i>
                        Academic Year

                    </h5>


                    <div class="mb-4">

                        <p class="text-muted mb-1">
                            Current Name
                        </p>

                        <h5 class="mb-0">
                            {{ $academicYear->name }}
                        </h5>

                    </div>


                    <div class="mb-4">

                        <p class="text-muted mb-1">
                            Current Status
                        </p>

                        @if($academicYear->is_current)

                            <span class="badge bg-success">
                                <i class="bx bx-check-circle me-1"></i>
                                Current
                            </span>

                        @else

                            <span class="badge bg-light text-muted">
                                Not Current
                            </span>

                        @endif

                        @if($academicYear->is_active)

                            <span class="badge bg-success-subtle text-success ms-1">
                                Active
                            </span>

                        @else

                            <span class="badge bg-danger-subtle text-danger ms-1">
                                Inactive
                            </span>

                        @endif

                    </div>


                    <div class="mb-4">

                        <p class="text-muted mb-1">
                            Configured Terms
                        </p>

                        <h5 class="mb-0">

                            {{ $academicYear->terms()->count() }}

                            {{ $academicYear->terms()->count() === 1 ? 'Term' : 'Terms' }}

                        </h5>

                    </div>


                    <div class="alert alert-info mb-0">

                        <small>

                            <i class="bx bx-info-circle me-1"></i>

                            Changes to this academic year are restricted to your
                            assigned school.

                        </small>

                    </div>

                </div>

            </div>


            {{-- Danger Zone --}}
            <div class="card border border-danger">

                <div class="card-body">

                    <h5 class="card-title text-danger mb-3">

                        <i class="bx bx-error-circle me-1"></i>
                        Danger Zone

                    </h5>

                    <p class="text-muted">

                        Deleting an academic year will soft-delete it. Academic years
                        with terms or student enrollments cannot be deleted.

                    </p>

                    <form method="POST"
                          action="{{ route('admin.academic-years.destroy', $academicYear) }}">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-outline-danger w-100"
                                onclick="return confirm('Are you sure you want to delete {{ $academicYear->name }}?')">

                            <i class="bx bx-trash me-1"></i>
                            Delete Academic Year

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection