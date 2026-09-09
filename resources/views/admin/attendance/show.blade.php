@extends('backend.layout.default')

@section('content')

<div class="container">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="mb-1">
                Attendance Details
            </h1>

            <p class="text-muted mb-0">
                View the details of this attendance record.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.attendance.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bx bx-arrow-back me-1"></i>
                Back to Attendance
            </a>

            @can('attendance.update')

                <a
                    href="{{ route('admin.attendance.edit', $attendance) }}"
                    class="btn btn-warning"
                >
                    <i class="bx bx-edit me-1"></i>
                    Edit
                </a>

            @endcan

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERROR MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    <div class="row">

        {{-- ===================================================== --}}
        {{-- STUDENT INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="col-lg-5 mb-4">

            <div class="card h-100">

                <div class="card-header">

                    <h5 class="mb-0">
                        Student Information
                    </h5>

                </div>

                <div class="card-body">

                    @if($attendance->student)

                        <div class="text-center mb-4">

                            @if($attendance->student->photo)

                                <img
                                    src="{{ asset('storage/' . $attendance->student->photo) }}"
                                    alt="Student Photo"
                                    class="rounded-circle"
                                    style="
                                        width: 100px;
                                        height: 100px;
                                        object-fit: cover;
                                    "
                                >

                            @else

                                <div
                                    class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                    style="
                                        width: 100px;
                                        height: 100px;
                                    "
                                >
                                    <i
                                        class="bx bx-user text-muted"
                                        style="font-size: 48px;"
                                    ></i>
                                </div>

                            @endif

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Student Name
                            </div>

                            <strong class="fs-5">
                                {{ $attendance->student->full_name }}
                            </strong>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Student Number
                            </div>

                            <strong>
                                {{ $attendance->student->student_number ?? '-' }}
                            </strong>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Admission Number
                            </div>

                            <strong>
                                {{ $attendance->student->admission_number ?? '-' }}
                            </strong>

                        </div>


                        <div>

                            <div class="text-muted small">
                                Student Status
                            </div>

                            <span class="badge bg-success">
                                {{ ucfirst($attendance->student->status ?? 'Unknown') }}
                            </span>

                        </div>

                    @else

                        <div class="alert alert-warning mb-0">
                            Student information is no longer available.
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ATTENDANCE INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="col-lg-7 mb-4">

            <div class="card h-100">

                <div class="card-header">

                    <h5 class="mb-0">
                        Attendance Information
                    </h5>

                </div>

                <div class="card-body">

                    {{-- Date --}}

                    <div class="row mb-4">

                        <div class="col-sm-5 text-muted">
                            Attendance Date
                        </div>

                        <div class="col-sm-7">

                            <strong>
                                {{ $attendance->attendance_date?->format('d M Y') ?? '-' }}
                            </strong>

                        </div>

                    </div>


                    {{-- Class --}}

                    <div class="row mb-4">

                        <div class="col-sm-5 text-muted">
                            Class
                        </div>

                        <div class="col-sm-7">

                            <strong>
                                {{ $attendance->classModel?->name ?? '-' }}
                            </strong>

                            @if($attendance->classModel?->code)

                                <span class="text-muted">
                                    ({{ $attendance->classModel->code }})
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Section --}}

                    <div class="row mb-4">

                        <div class="col-sm-5 text-muted">
                            Section
                        </div>

                        <div class="col-sm-7">

                            <strong>
                                {{ $attendance->section?->name ?? '-' }}
                            </strong>

                            @if($attendance->section?->code)

                                <span class="text-muted">
                                    ({{ $attendance->section->code }})
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Attendance Type --}}

                    <div class="row mb-4">

                        <div class="col-sm-5 text-muted">
                            Attendance Type
                        </div>

                        <div class="col-sm-7">

                            @if($attendance->course_id)

                                <span class="badge bg-info">
                                    Subject / Course
                                </span>

                            @else

                                <span class="badge bg-primary">
                                    Daily / Class
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Course --}}

                    <div class="row mb-4">

                        <div class="col-sm-5 text-muted">
                            Subject / Course
                        </div>

                        <div class="col-sm-7">

                            @if($attendance->course)

                                <strong>
                                    {{ $attendance->course->name }}
                                </strong>

                                @if($attendance->course->course_code)

                                    <div class="text-muted small">
                                        {{ $attendance->course->course_code }}
                                    </div>

                                @endif

                            @else

                                <span class="text-muted">
                                    Not applicable — Daily Attendance
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Status --}}

                    <div class="row mb-4">

                        <div class="col-sm-5 text-muted">
                            Attendance Status
                        </div>

                        <div class="col-sm-7">

                            @switch($attendance->status)

                                @case('present')

                                    <span class="badge bg-success fs-6">
                                        Present
                                    </span>

                                    @break

                                @case('absent')

                                    <span class="badge bg-danger fs-6">
                                        Absent
                                    </span>

                                    @break

                                @case('late')

                                    <span class="badge bg-warning text-dark fs-6">
                                        Late
                                    </span>

                                    @break

                                @case('excused')

                                    <span class="badge bg-secondary fs-6">
                                        Excused
                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-dark fs-6">
                                        {{ ucfirst($attendance->status) }}
                                    </span>

                            @endswitch

                        </div>

                    </div>


                    {{-- Remarks --}}

                    <div class="row mb-4">

                        <div class="col-sm-5 text-muted">
                            Remarks
                        </div>

                        <div class="col-sm-7">

                            @if($attendance->remarks)

                                {{ $attendance->remarks }}

                            @else

                                <span class="text-muted">
                                    No remarks
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- RECORD INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Record Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Recorded By --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">
                                Recorded By
                            </div>

                            <strong>
                                {{ $attendance->recordedBy?->name ?? '-' }}
                            </strong>

                        </div>


                        {{-- Created --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">
                                Created At
                            </div>

                            <strong>
                                {{ $attendance->created_at?->format('d M Y, h:i A') ?? '-' }}
                            </strong>

                        </div>


                        {{-- Updated --}}

                        <div class="col-md-4 mb-3">

                            <div class="text-muted small">
                                Last Updated
                            </div>

                            <strong>
                                {{ $attendance->updated_at?->format('d M Y, h:i A') ?? '-' }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection