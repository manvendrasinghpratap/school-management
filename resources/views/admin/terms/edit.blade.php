@extends('backend.layout.default')

@section('title', 'Edit Term')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Edit Term</h4>
                    <p class="text-muted mb-0">
                        Update academic term / semester information.
                    </p>
                </div>

                <div>
                    <a href="{{ route('admin.terms.show', $term) }}"
                       class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Term
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

        {{-- Edit Form --}}
        <div class="col-xl-8 col-lg-8">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-edit-alt me-2"></i>
                        Term Information
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.terms.update', $term) }}">

                        @csrf
                        @method('PUT')

                        {{-- Academic Year --}}
                        <div class="mb-3">
                            <label for="academic_year_id" class="form-label">
                                Academic Year
                                <span class="text-danger">*</span>
                            </label>

                            <select name="academic_year_id"
                                    id="academic_year_id"
                                    class="form-select @error('academic_year_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Academic Year
                                </option>

                                @foreach($academicYears as $academicYear)
                                    <option value="{{ $academicYear->id }}"
                                        {{ old('academic_year_id', $term->academic_year_id) == $academicYear->id ? 'selected' : '' }}>

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
                                Only active academic years belonging to your school
                                are available.
                            </div>
                        </div>

                        {{-- Term Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Term Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $term->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   maxlength="100"
                                   placeholder="e.g. First Term"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Term Number --}}
                        <div class="mb-3">
                            <label for="term_number" class="form-label">
                                Term Number
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="term_number"
                                   id="term_number"
                                   value="{{ old('term_number', $term->term_number) }}"
                                   class="form-control @error('term_number') is-invalid @enderror"
                                   min="1"
                                   max="10"
                                   required>

                            @error('term_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Example: 1 for First Term, 2 for Second Term,
                                3 for Third Term.
                            </div>
                        </div>

                        <div class="row">

                            {{-- Start Date --}}
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">
                                    Start Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="start_date"
                                       id="start_date"
                                       value="{{ old('start_date', $term->start_date?->format('Y-m-d')) }}"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       required>

                                @error('start_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- End Date --}}
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">
                                    End Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="end_date"
                                       id="end_date"
                                       value="{{ old('end_date', $term->end_date?->format('Y-m-d')) }}"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       required>

                                @error('end_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        {{-- Current Term --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">

                                <input type="hidden"
                                       name="is_current"
                                       value="0">

                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_current"
                                       id="is_current"
                                       value="1"
                                       {{ old('is_current', $term->is_current) ? 'checked' : '' }}>

                                <label class="form-check-label"
                                       for="is_current">
                                    <strong>Current Term</strong>
                                </label>

                            </div>

                            <div class="form-text">
                                Setting this term as current will automatically
                                remove the current status from other terms in
                                the same academic year.
                            </div>
                        </div>

                        {{-- Active --}}
                        <div class="mb-4">
                            <div class="form-check form-switch">

                                <input type="hidden"
                                       name="is_active"
                                       value="0">

                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_active"
                                       id="is_active"
                                       value="1"
                                       {{ old('is_active', $term->is_active) ? 'checked' : '' }}>

                                <label class="form-check-label"
                                       for="is_active">
                                    <strong>Active</strong>
                                </label>

                            </div>

                            <div class="form-text">
                                Inactive terms remain in the system but cannot
                                normally be selected for new operations.
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between">

                            <a href="{{ route('admin.terms.show', $term) }}"
                               class="btn btn-secondary">
                                <i class="bx bx-x me-1"></i>
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Update Term
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>

        {{-- Current Information --}}
        <div class="col-xl-4 col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        Current Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="mb-4">
                        <label class="text-muted d-block mb-1">
                            Term
                        </label>

                        <h5 class="mb-0">
                            {{ $term->name }}
                        </h5>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted d-block mb-1">
                            Academic Year
                        </label>

                        <div class="fw-semibold">
                            {{ $term->academicYear?->name ?? '—' }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted d-block mb-1">
                            Term Number
                        </label>

                        <div class="fw-semibold">
                            {{ $term->term_number }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted d-block mb-1">
                            Current Status
                        </label>

                        @if($term->is_current)
                            <span class="badge bg-success">
                                Current Term
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                Not Current
                            </span>
                        @endif
                    </div>

                    <div>
                        <label class="text-muted d-block mb-1">
                            Active Status
                        </label>

                        @if($term->is_active)
                            <span class="badge bg-success">
                                Active
                            </span>
                        @else
                            <span class="badge bg-danger">
                                Inactive
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Important Rules --}}
            <div class="card border-info">

                <div class="card-header">
                    <h5 class="card-title mb-0 text-info">
                        <i class="bx bx-bulb me-2"></i>
                        Important
                    </h5>
                </div>

                <div class="card-body">

                    <ul class="mb-0 ps-3">
                        <li class="mb-2">
                            Term dates must fall within the selected academic year.
                        </li>

                        <li class="mb-2">
                            Term numbers must be unique within an academic year.
                        </li>

                        <li class="mb-2">
                            Term names must be unique within an academic year.
                        </li>

                        <li>
                            Only one term can be current within an academic year.
                        </li>
                    </ul>

                </div>
            </div>

        </div>

    </div>

</div>
@endsection