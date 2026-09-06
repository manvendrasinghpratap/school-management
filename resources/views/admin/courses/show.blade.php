@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">{{ $course->name }}</h4>
            <p class="text-muted mb-0">
                Course Code: {{ $course->course_code }}
            </p>
        </div>

        <div class="d-flex gap-2">
            <a
                href="{{ route('admin.courses.edit', $course) }}"
                class="btn btn-warning"
            >
                <i class="mdi mdi-pencil"></i>
                Edit
            </a>

            <a
                href="{{ route('admin.courses.index') }}"
                class="btn btn-light"
            >
                <i class="mdi mdi-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        <div class="col-lg-8">

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Subject / Course Information</h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <div class="text-muted small">Course Code</div>
                            <div class="fw-semibold">
                                {{ $course->course_code }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="text-muted small">Name</div>
                            <div class="fw-semibold">
                                {{ $course->name }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="text-muted small">Department</div>
                            <div class="fw-semibold">
                                {{ $course->department?->name ?? 'Not assigned' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="text-muted small">Credit Hours</div>
                            <div class="fw-semibold">
                                {{ $course->credit_hours ?? 'Not specified' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="text-muted small">Compulsory</div>

                            @if($course->is_compulsory)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="text-muted small">Status</div>

                            @if($course->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <div class="text-muted small mb-2">Description</div>

                            <div>
                                {{ $course->description ?: 'No description provided.' }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Enrollment</h5>
                </div>

                <div class="card-body text-center">

                    <h2 class="mb-1">
                        {{ $course->students()->count() }}
                    </h2>

                    <p class="text-muted mb-0">
                        Enrolled Students
                    </p>

                </div>
            </div>

        </div>

    </div>

</div>
@endsection