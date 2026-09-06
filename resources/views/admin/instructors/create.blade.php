@extends('backend.layout.default')

@section('title', 'Add Instructor')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-sm-0">Add Instructor</h4>
                    <p class="text-muted mb-0">
                        Create an instructor profile for an existing staff member.
                    </p>
                </div>

                <div class="page-title-right">
                    <a href="{{ route('admin.instructors.index') }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Instructors
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <div class="d-flex">
                <i class="bx bx-error-circle fs-4 me-2"></i>

                <div>
                    <strong>Please correct the following:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>
    @endif

    <div class="row">

        {{-- Main Form --}}
        <div class="col-xl-8">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-1">
                        Instructor Profile
                    </h5>

                    <p class="text-muted mb-0">
                        Select an existing staff member and enter their teaching details.
                    </p>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.instructors.store') }}">

                        @csrf

                        {{-- Staff Member --}}
                        <div class="mb-4">

                            <label for="staff_id" class="form-label">
                                Staff Member
                                <span class="text-danger">*</span>
                            </label>

                            <select name="staff_id"
                                    id="staff_id"
                                    class="form-select @error('staff_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Select Staff Member --
                                </option>

                                @foreach($staff as $member)

                                    <option value="{{ $member->id }}"
                                        {{ old('staff_id') == $member->id ? 'selected' : '' }}>

                                        {{ $member->full_name }}
                                        — {{ $member->staff_number }}

                                        @if($member->department)
                                            — {{ $member->department->name }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('staff_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            @if($staff->isEmpty())
                                <div class="form-text text-danger">
                                    No active staff members are currently available
                                    for an instructor profile.
                                </div>
                            @else
                                <div class="form-text">
                                    Only active staff members without an existing
                                    instructor profile are shown.
                                </div>
                            @endif

                        </div>

                        {{-- Specialization --}}
                        <div class="mb-4">

                            <label for="specialization" class="form-label">
                                Specialization
                            </label>

                            <input type="text"
                                   name="specialization"
                                   id="specialization"
                                   value="{{ old('specialization') }}"
                                   class="form-control @error('specialization') is-invalid @enderror"
                                   placeholder="e.g. Mathematics, Physics, English Language">

                            @error('specialization')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                The subject or academic area the instructor specializes in.
                            </div>

                        </div>

                        {{-- Qualification --}}
                        <div class="mb-4">

                            <label for="qualification" class="form-label">
                                Qualification
                            </label>

                            <textarea name="qualification"
                                      id="qualification"
                                      rows="4"
                                      class="form-control @error('qualification') is-invalid @enderror"
                                      placeholder="e.g. B.Ed Mathematics, M.Sc Physics">{{ old('qualification') }}</textarea>

                            @error('qualification')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Enter the instructor's relevant academic or professional qualifications.
                            </div>

                        </div>

                        {{-- Form Actions --}}
                        <div class="border-top pt-3">

                            <div class="d-flex justify-content-end gap-2">

                                <a href="{{ route('admin.instructors.index') }}"
                                   class="btn btn-light">
                                    Cancel
                                </a>

                                <button type="submit"
                                        class="btn btn-primary"
                                        @if($staff->isEmpty()) disabled @endif>
                                    <i class="bx bx-save me-1"></i>
                                    Create Instructor
                                </button>

                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>

        {{-- Information Card --}}
        <div class="col-xl-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-info-circle me-1"></i>
                        Instructor Management
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex mb-4">

                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    <i class="bx bx-user fs-4"></i>
                                </span>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">
                                Existing Staff
                            </h6>

                            <p class="text-muted mb-0">
                                Instructor profiles are attached to existing staff
                                records. No duplicate staff member is created.
                            </p>
                        </div>

                    </div>

                    <div class="d-flex mb-4">

                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                    <i class="bx bx-book-open fs-4"></i>
                                </span>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">
                                Teaching Details
                            </h6>

                            <p class="text-muted mb-0">
                                Specialization and qualification describe the
                                instructor's teaching profile.
                            </p>
                        </div>

                    </div>

                    <div class="d-flex">

                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-warning-subtle text-warning">
                                    <i class="bx bx-shield-quarter fs-4"></i>
                                </span>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">
                                One Profile Per Staff
                            </h6>

                            <p class="text-muted mb-0">
                                A staff member can have only one instructor profile.
                            </p>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Staff Count --}}
            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-info-subtle text-info">
                                <i class="bx bx-group fs-4"></i>
                            </span>
                        </div>

                        <div class="ms-3">

                            <h5 class="mb-1">
                                {{ $staff->count() }}
                            </h5>

                            <p class="text-muted mb-0">
                                Staff available for instructor assignment
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection