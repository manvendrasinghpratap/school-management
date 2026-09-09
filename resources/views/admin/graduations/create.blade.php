@extends('backend.layout.default')

@section('title', 'Create Graduation')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Graduation</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.graduations.index') }}">
                                Graduations
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
    @if ($errors->any())
        <div class="alert alert-danger">
            <h6 class="alert-heading">
                Please correct the following errors:
            </h6>

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Session Error --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-1">Graduation Information</h5>
                    <p class="text-muted mb-0">
                        Create a graduation record for an eligible SS 3 student.
                    </p>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.graduations.store') }}"
                          id="graduationForm">

                        @csrf

                        {{-- Student --}}
                        <div class="mb-3">
                            <label for="student_id" class="form-label">
                                Student <span class="text-danger">*</span>
                            </label>

                            <select name="student_id"
                                    id="student_id"
                                    class="form-select @error('student_id') is-invalid @enderror"
                                    required>

                                <option value="">Select SS 3 Student</option>

                                @forelse ($students as $student)
                                    @php
                                        $enrollment = $student->enrollments->first();
                                    @endphp

                                    <option value="{{ $student->id }}"
                                            {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->student_number }}
                                        -
                                        {{ trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name) }}

                                        @if ($enrollment)
                                            ({{ $enrollment->class->name ?? 'SS 3' }}
                                            @if ($enrollment->section)
                                                - {{ $enrollment->section->name }}
                                            @endif
                                            )
                                        @endif
                                    </option>
                                @empty
                                    <option value="" disabled>
                                        No eligible SS 3 students found
                                    </option>
                                @endforelse
                            </select>

                            @error('student_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Only active students currently enrolled in SS 3 are displayed.
                            </div>
                        </div>

                        {{-- Academic Year --}}
                        <div class="mb-3">
                            <label for="academic_year_id" class="form-label">
                                Academic Year <span class="text-danger">*</span>
                            </label>

                            <select name="academic_year_id"
                                    id="academic_year_id"
                                    class="form-select @error('academic_year_id') is-invalid @enderror"
                                    required>

                                <option value="">Select Academic Year</option>

                                @foreach ($academicYears as $academicYear)
                                    <option value="{{ $academicYear->id }}"
                                            {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}>
                                        {{ $academicYear->name }}

                                        @if ($academicYear->is_current)
                                            (Current)
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @error('academic_year_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Graduation Date --}}
                        <div class="mb-3">
                            <label for="graduation_date" class="form-label">
                                Graduation Date <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                   name="graduation_date"
                                   id="graduation_date"
                                   value="{{ old('graduation_date') }}"
                                   class="form-control @error('graduation_date') is-invalid @enderror"
                                   required>

                            @error('graduation_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Qualification --}}
                        <div class="mb-3">
                            <label for="qualification" class="form-label">
                                Qualification
                            </label>

                            <input type="text"
                                   name="qualification"
                                   id="qualification"
                                   value="{{ old('qualification') }}"
                                   class="form-control @error('qualification') is-invalid @enderror"
                                   maxlength="255"
                                   placeholder="e.g. Senior Secondary School Certificate">

                            @error('qualification')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Remarks --}}
                        <div class="mb-3">
                            <label for="remarks" class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      id="remarks"
                                      rows="4"
                                      maxlength="5000"
                                      class="form-control @error('remarks') is-invalid @enderror"
                                      placeholder="Optional graduation remarks">{{ old('remarks') }}</textarea>

                            @error('remarks')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            <button type="submit"
                                    class="btn btn-primary"
                                    id="submitGraduationBtn"
                                    {{ $students->isEmpty() || $academicYears->isEmpty() ? 'disabled' : '' }}>
                                <i class="ri-graduation-cap-line me-1"></i>
                                Create Graduation
                            </button>

                            <a href="{{ route('admin.graduations.index') }}"
                               class="btn btn-light">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        {{-- Information Card --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Graduation Workflow
                    </h5>
                </div>

                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <span class="avatar-sm rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-user-search-line text-primary"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">1. Eligibility</h6>
                            <p class="text-muted mb-0">
                                Student must be active and currently enrolled in SS 3.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <span class="avatar-sm rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-file-edit-line text-warning"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">2. Create</h6>
                            <p class="text-muted mb-0">
                                The graduation record is created with pending status.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <span class="avatar-sm rounded-circle bg-info-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-checkbox-circle-line text-info"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">3. Approval</h6>
                            <p class="text-muted mb-0">
                                An authorized user reviews and approves the graduation.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <span class="avatar-sm rounded-circle bg-success-subtle d-flex align-items-center justify-content-center">
                                <i class="ri-award-line text-success"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">4. Completion</h6>
                            <p class="text-muted mb-0">
                                Approved graduation is finalized as completed.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="ri-information-line me-1"></i>
                <strong>Note:</strong>
                Graduation records cannot be deleted after approval.
            </div>
        </div>
    </div>

</div>
@endsection