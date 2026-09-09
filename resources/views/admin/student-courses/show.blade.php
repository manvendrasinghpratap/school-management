@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Student Course Registration
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Academic
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.student-courses.index') }}">
                                Student Course Registration
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            View Registration
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="mdi mdi-check-circle-outline me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- Main Card --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

                <div>

                    <h4 class="card-title mb-1">
                        Course Registration Details
                    </h4>

                    <p class="text-muted mb-0">
                        View the complete student course registration.
                    </p>

                </div>


                <div class="d-flex flex-wrap gap-2">

                    @can('student-courses.update')

                        <a href="{{ route('admin.student-courses.edit', $studentCourse) }}"
                           class="btn btn-primary">

                            <i class="mdi mdi-pencil-outline me-1"></i>

                            Edit

                        </a>

                    @endcan


                    <a href="{{ route('admin.student-courses.index') }}"
                       class="btn btn-light">

                        <i class="mdi mdi-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>


        <div class="card-body">


            {{-- Registration Status --}}
            <div class="row mb-4">

                <div class="col-12">

                    <div class="alert
                        @if($studentCourse->status === 'enrolled')
                            alert-success
                        @elseif($studentCourse->status === 'completed')
                            alert-primary
                        @elseif($studentCourse->status === 'dropped')
                            alert-danger
                        @else
                            alert-secondary
                        @endif
                        mb-0">

                        <div class="d-flex align-items-center">

                            <i class="mdi mdi-book-check-outline fs-4 me-2"></i>

                            <div>

                                <strong>
                                    Registration Status:
                                </strong>

                                @if($studentCourse->status === 'enrolled')

                                    Enrolled

                                @elseif($studentCourse->status === 'completed')

                                    Completed

                                @elseif($studentCourse->status === 'dropped')

                                    Dropped

                                @else

                                    {{ ucfirst($studentCourse->status) }}

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Student Information --}}
            <div class="card border shadow-none mb-4">

                <div class="card-header bg-light">

                    <h5 class="card-title mb-0">

                        <i class="mdi mdi-account-school-outline me-1"></i>

                        Student Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Student Name
                            </div>

                            <div class="fw-semibold font-size-15">

                                {{ $studentCourse->student?->full_name ?? '—' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Student Number
                            </div>

                            <div class="fw-semibold">

                                {{ $studentCourse->student?->student_number ?? '—' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Admission Number
                            </div>

                            <div class="fw-semibold">

                                {{ $studentCourse->student?->admission_number ?? '—' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Student Status
                            </div>

                            @if($studentCourse->student?->status === 'active')

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ ucfirst($studentCourse->student?->status ?? '—') }}
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- Academic Enrollment --}}
            <div class="card border shadow-none mb-4">

                <div class="card-header bg-light">

                    <h5 class="card-title mb-0">

                        <i class="mdi mdi-school-outline me-1"></i>

                        Academic Enrollment

                    </h5>

                </div>


                <div class="card-body">

                    @if($enrollment)

                        <div class="row g-4">

                            {{-- Academic Year --}}
                            <div class="col-md-3">

                                <div class="text-muted small mb-1">
                                    Academic Year
                                </div>

                                <div class="fw-semibold">
                                    {{ $studentCourse->academicYear?->name ?? '—' }}
                                </div>

                            </div>


                            {{-- Term --}}
                            <div class="col-md-3">

                                <div class="text-muted small mb-1">
                                    Term
                                </div>

                                <div class="fw-semibold">
                                    {{ $studentCourse->term?->name ?? 'All Terms' }}
                                </div>

                            </div>


                            {{-- Class --}}
                            <div class="col-md-3">

                                <div class="text-muted small mb-1">
                                    Class
                                </div>

                                <div class="fw-semibold">

                                    {{ $enrollment->class?->name ?? '—' }}

                                    @if($enrollment->class?->code)

                                        <span class="text-muted">
                                            — {{ $enrollment->class->code }}
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- Section --}}
                            <div class="col-md-3">

                                <div class="text-muted small mb-1">
                                    Section
                                </div>

                                <div class="fw-semibold">

                                    @if($enrollment->section)

                                        {{ $enrollment->section->name }}

                                        @if($enrollment->section->code)

                                            <span class="text-muted">
                                                — {{ $enrollment->section->code }}
                                            </span>

                                        @endif

                                    @else

                                        All / No Section

                                    @endif

                                </div>

                            </div>


                            {{-- Enrollment Number --}}
                            <div class="col-md-4">

                                <div class="text-muted small mb-1">
                                    Enrollment Number
                                </div>

                                <div class="fw-semibold">

                                    {{ $enrollment->enrollment_number }}

                                </div>

                            </div>


                            {{-- Enrollment Date --}}
                            <div class="col-md-4">

                                <div class="text-muted small mb-1">
                                    Enrollment Date
                                </div>

                                <div class="fw-semibold">

                                    {{ $enrollment->enrollment_date?->format('d M Y') ?? '—' }}

                                </div>

                            </div>


                            {{-- Enrollment Status --}}
                            <div class="col-md-4">

                                <div class="text-muted small mb-1">
                                    Enrollment Status
                                </div>

                                <span class="badge bg-success">
                                    {{ ucfirst($enrollment->status) }}
                                </span>

                            </div>

                        </div>

                    @else

                        <div class="alert alert-warning mb-0">

                            <i class="mdi mdi-alert-outline me-2"></i>

                            No matching active enrollment was found for this
                            course registration.

                        </div>

                    @endif

                </div>

            </div>


            {{-- Course Information --}}
            <div class="card border shadow-none mb-4">

                <div class="card-header bg-light">

                    <h5 class="card-title mb-0">

                        <i class="mdi mdi-book-open-page-variant-outline me-1"></i>

                        Course Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Course Code
                            </div>

                            <div class="fw-semibold">
                                {{ $studentCourse->course?->course_code ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Course Name
                            </div>

                            <div class="fw-semibold">
                                {{ $studentCourse->course?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Department
                            </div>

                            <div class="fw-semibold">
                                {{ $studentCourse->course?->department?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Description
                            </div>

                            <div>

                                {{ $studentCourse->course?->description ?? 'No description available.' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Compulsory
                            </div>

                            @if($studentCourse->course?->is_compulsory)

                                <span class="badge bg-primary">
                                    Yes
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    No
                                </span>

                            @endif

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Course Status
                            </div>

                            @if($studentCourse->course?->is_active)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- Registration Information --}}
            <div class="card border shadow-none">

                <div class="card-header bg-light">

                    <h5 class="card-title mb-0">

                        <i class="mdi mdi-information-outline me-1"></i>

                        Registration Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Registration ID
                            </div>

                            <div class="fw-semibold">
                                #{{ $studentCourse->id }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Created
                            </div>

                            <div class="fw-semibold">

                                {{ $studentCourse->created_at?->format('d M Y H:i') ?? '—' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Last Updated
                            </div>

                            <div class="fw-semibold">

                                {{ $studentCourse->updated_at?->format('d M Y H:i') ?? '—' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Bottom Actions --}}
            <div class="mt-4 d-flex flex-wrap gap-2">

                @can('student-courses.update')

                    <a href="{{ route('admin.student-courses.edit', $studentCourse) }}"
                       class="btn btn-primary">

                        <i class="mdi mdi-pencil-outline me-1"></i>

                        Edit Registration

                    </a>

                @endcan


                <a href="{{ route('admin.student-courses.index') }}"
                   class="btn btn-light">

                    <i class="mdi mdi-arrow-left me-1"></i>

                    Back to Registrations

                </a>

            </div>


        </div>

    </div>

</div>

@endsection