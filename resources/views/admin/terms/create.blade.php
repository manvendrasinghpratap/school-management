@extends('backend.layout.default')

@section('title', 'Create Term')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Create Term / Semester
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.terms.index') }}">
                                Terms / Semesters
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

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

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


    <div class="row">

        {{-- Form --}}
        <div class="col-xl-8 col-lg-10">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center mb-4">

                        <div class="avatar-sm me-3">

                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">

                                <i class="bx bx-list-plus"></i>

                            </span>

                        </div>

                        <div>

                            <h4 class="card-title mb-1">
                                Term Information
                            </h4>

                            <p class="text-muted mb-0">
                                Create a term within an academic year.
                            </p>

                        </div>

                    </div>


                    <form method="POST"
                          action="{{ route('admin.terms.store') }}">

                        @csrf

                        <div class="row">

                            {{-- Academic Year --}}
                            <div class="col-md-12 mb-3">

                                <label for="academic_year_id"
                                       class="form-label">

                                    Academic Year
                                    <span class="text-danger">*</span>

                                </label>

                                <select id="academic_year_id"
                                        name="academic_year_id"
                                        class="form-select @error('academic_year_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Academic Year
                                    </option>

                                    @foreach($academicYears as $academicYear)

                                        <option value="{{ $academicYear->id }}"
                                            {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}>

                                            {{ $academicYear->name }}

                                            @if($academicYear->is_current)
                                                — Current
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('academic_year_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="form-text">
                                    The term must belong to the selected academic year.
                                </div>

                            </div>


                            {{-- Term Name --}}
                            <div class="col-md-8 mb-3">

                                <label for="name"
                                       class="form-label">

                                    Term Name
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text"
                                       id="name"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="e.g. First Term"
                                       maxlength="100"
                                       required>

                                @error('name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Term Number --}}
                            <div class="col-md-4 mb-3">

                                <label for="term_number"
                                       class="form-label">

                                    Term Number
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="number"
                                       id="term_number"
                                       name="term_number"
                                       class="form-control @error('term_number') is-invalid @enderror"
                                       value="{{ old('term_number', 1) }}"
                                       min="1"
                                       max="10"
                                       required>

                                @error('term_number')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="form-text">
                                    Example: 1, 2, or 3.
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

                                        Set as Current Term

                                    </label>

                                </div>

                                <small class="text-muted">

                                    Only one term can be current within an
                                    academic year.

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

                                        Active Term

                                    </label>

                                </div>

                                <small class="text-muted">

                                    Active terms are available for academic
                                    operations.

                                </small>

                            </div>

                        </div>


                        <hr class="my-4">


                        {{-- Actions --}}
                        <div class="d-flex justify-content-between">

                            <a href="{{ route('admin.terms.index') }}"
                               class="btn btn-light">

                                <i class="bx bx-arrow-back me-1"></i>
                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bx bx-save me-1"></i>
                                Create Term

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Information --}}
        <div class="col-xl-4 col-lg-2">

            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-4">

                        <i class="bx bx-info-circle text-primary me-1"></i>
                        Term Information

                    </h5>


                    <div class="alert alert-info">

                        <strong>Academic Period</strong>

                        <p class="mb-0 mt-2">

                            A term is a defined period within an academic year
                            during which classes, attendance, examinations and
                            other academic activities take place.

                        </p>

                    </div>


                    <ul class="text-muted ps-3 mb-0">

                        <li class="mb-2">
                            Select an active academic year.
                        </li>

                        <li class="mb-2">
                            Term dates must fall within the academic year's dates.
                        </li>

                        <li class="mb-2">
                            Term numbers must be unique within the academic year.
                        </li>

                        <li class="mb-2">
                            Term names must be unique within the academic year.
                        </li>

                        <li>
                            Only one term can be current per academic year.
                        </li>

                    </ul>

                </div>

            </div>


            {{-- Academic Year Summary --}}
            <div class="card">

                <div class="card-body">

                    <h5 class="card-title mb-3">
                        Active Academic Years
                    </h5>

                    @forelse($academicYears as $academicYear)

                        <div class="d-flex align-items-center mb-3">

                            <div class="avatar-xs me-2">

                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">

                                    <i class="bx bx-calendar"></i>

                                </span>

                            </div>

                            <div>

                                <h6 class="mb-0">
                                    {{ $academicYear->name }}
                                </h6>

                                <small class="text-muted">

                                    {{ $academicYear->start_date?->format('d M Y') }}
                                    -
                                    {{ $academicYear->end_date?->format('d M Y') }}

                                </small>

                            </div>

                            @if($academicYear->is_current)

                                <span class="badge bg-success ms-auto">
                                    Current
                                </span>

                            @endif

                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No active academic years available.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection