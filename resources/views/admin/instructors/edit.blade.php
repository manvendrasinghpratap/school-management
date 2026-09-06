@extends('backend.layout.default')

@section('title', 'Edit Instructor')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-sm-0">Edit Instructor</h4>
                    <p class="text-muted mb-0">
                        Update the instructor's teaching profile.
                    </p>
                </div>

                <div class="page-title-right">
                    <a href="{{ route('admin.instructors.show', $instructor) }}"
                       class="btn btn-light me-2">
                        <i class="bx bx-show me-1"></i>
                        View
                    </a>

                    <a href="{{ route('admin.instructors.index') }}"
                       class="btn btn-light">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back
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
                        Update specialization and qualification information.
                    </p>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.instructors.update', $instructor) }}">

                        @csrf
                        @method('PUT')

                        {{-- Staff Member --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Staff Member
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bx bx-user"></i>
                                </span>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $instructor->staff?->full_name }} — {{ $instructor->staff?->staff_number }}"
                                       readonly>

                            </div>

                            <div class="form-text">
                                Staff information is managed from the Staff module.
                            </div>

                        </div>

                        {{-- Department --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Department
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bx bx-building"></i>
                                </span>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $instructor->staff?->department?->name ?? 'Not assigned' }}"
                                       readonly>

                            </div>

                        </div>

                        {{-- Specialization --}}
                        <div class="mb-4">

                            <label for="specialization" class="form-label">
                                Specialization
                            </label>

                            <input type="text"
                                   name="specialization"
                                   id="specialization"
                                   value="{{ old('specialization', $instructor->specialization) }}"
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
                                      rows="5"
                                      class="form-control @error('qualification') is-invalid @enderror"
                                      placeholder="e.g. B.Ed Mathematics, M.Sc Physics">{{ old('qualification', $instructor->qualification) }}</textarea>

                            @error('qualification')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Enter the instructor's relevant academic or professional qualifications.
                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="border-top pt-3">

                            <div class="d-flex justify-content-end gap-2">

                                <a href="{{ route('admin.instructors.show', $instructor) }}"
                                   class="btn btn-light">
                                    Cancel
                                </a>

                                <button type="submit"
                                        class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i>
                                    Update Instructor
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-xl-4">

            {{-- Staff Profile --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Staff Profile
                    </h5>
                </div>

                <div class="card-body text-center">

                    @if($instructor->staff?->photo)

                        <img src="{{ asset('storage/' . $instructor->staff->photo) }}"
                             alt="{{ $instructor->staff->full_name }}"
                             class="rounded-circle avatar-xl img-thumbnail mb-3">

                    @else

                        <div class="avatar-xl mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-1">
                                {{ strtoupper(substr($instructor->staff?->first_name ?? 'I', 0, 1)) }}
                            </span>
                        </div>

                    @endif

                    <h5 class="mb-1">
                        {{ $instructor->staff?->full_name ?? 'N/A' }}
                    </h5>

                    <p class="text-muted mb-3">
                        {{ $instructor->staff?->staff_number ?? 'N/A' }}
                    </p>

                    @if($instructor->staff?->status === 'active')

                        <span class="badge bg-success-subtle text-success">
                            <i class="bx bx-check-circle me-1"></i>
                            Active
                        </span>

                    @elseif($instructor->staff?->status === 'inactive')

                        <span class="badge bg-warning-subtle text-warning">
                            <i class="bx bx-pause-circle me-1"></i>
                            Inactive
                        </span>

                    @else

                        <span class="badge bg-danger-subtle text-danger">
                            {{ ucfirst($instructor->staff?->status ?? 'Unknown') }}
                        </span>

                    @endif

                </div>

            </div>

            {{-- Current Teaching Profile --}}
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Current Teaching Profile
                    </h5>
                </div>

                <div class="card-body">

                    <div class="mb-4">

                        <label class="text-muted small d-block mb-1">
                            Current Specialization
                        </label>

                        <span class="fw-medium">
                            {{ $instructor->specialization ?: 'Not specified' }}
                        </span>

                    </div>

                    <div>

                        <label class="text-muted small d-block mb-1">
                            Current Qualification
                        </label>

                        <div>
                            {!! nl2br(e($instructor->qualification ?: 'Not specified')) !!}
                        </div>

                    </div>

                </div>

            </div>

            {{-- Important Note --}}
            <div class="alert alert-info">

                <div class="d-flex">

                    <i class="bx bx-info-circle fs-4 me-2"></i>

                    <div>
                        <strong>Note</strong>

                        <p class="mb-0 mt-1">
                            Changes on this page affect only the instructor
                            profile. To change personal information,
                            employment details, department, or staff status,
                            use the Staff module.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection