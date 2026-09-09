@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Staff Attendance Details
                </h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            Staff Attendance
                        </li>

                        <li class="breadcrumb-item active">
                            Details
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Staff Information --}}
    <div class="card">

        <div class="card-header">

            <h4 class="card-title mb-0">
                Staff Information
            </h4>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="text-muted d-block">
                        Staff Name
                    </label>

                    <strong>
                        {{ trim(
                            ($staffAttendance->staff->first_name ?? '')
                            . ' '
                            . ($staffAttendance->staff->middle_name ?? '')
                            . ' '
                            . ($staffAttendance->staff->last_name ?? '')
                        ) ?: '—' }}
                    </strong>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="text-muted d-block">
                        Employee Number
                    </label>

                    <strong>
                        {{ $staffAttendance->staff->employee_number ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Attendance Information --}}
    <div class="card">

        <div class="card-header">

            <h4 class="card-title mb-0">
                Attendance Information
            </h4>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Date --}}
                <div class="col-md-4 mb-4">

                    <label class="text-muted d-block">
                        Attendance Date
                    </label>

                    <strong>
                        {{ optional($staffAttendance->attendance_date)->format('Y-m-d') }}
                    </strong>

                </div>


                {{-- Status --}}
                <div class="col-md-4 mb-4">

                    <label class="text-muted d-block">
                        Status
                    </label>

                    @switch($staffAttendance->status)

                        @case('present')

                            <span class="badge bg-success">
                                Present
                            </span>

                            @break

                        @case('absent')

                            <span class="badge bg-danger">
                                Absent
                            </span>

                            @break

                        @case('late')

                            <span class="badge bg-warning text-dark">
                                Late
                            </span>

                            @break

                        @case('half_day')

                            <span class="badge bg-info">
                                Half Day
                            </span>

                            @break

                        @case('leave')

                            <span class="badge bg-secondary">
                                Leave
                            </span>

                            @break

                        @default

                            <span class="badge bg-dark">
                                {{ ucfirst($staffAttendance->status) }}
                            </span>

                    @endswitch

                </div>


                {{-- School --}}
                <div class="col-md-4 mb-4">

                    <label class="text-muted d-block">
                        School ID
                    </label>

                    <strong>
                        {{ $staffAttendance->school_id }}
                    </strong>

                </div>


                {{-- Check In --}}
                <div class="col-md-4 mb-4">

                    <label class="text-muted d-block">
                        Check In
                    </label>

                    <strong>
                        {{ $staffAttendance->check_in ?: '—' }}
                    </strong>

                </div>


                {{-- Check Out --}}
                <div class="col-md-4 mb-4">

                    <label class="text-muted d-block">
                        Check Out
                    </label>

                    <strong>
                        {{ $staffAttendance->check_out ?: '—' }}
                    </strong>

                </div>


                {{-- Remarks --}}
                <div class="col-md-12 mb-3">

                    <label class="text-muted d-block">
                        Remarks
                    </label>

                    <div class="border rounded p-3 bg-light">

                        {{ $staffAttendance->remarks ?: 'No remarks provided.' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Record Information --}}
    <div class="card">

        <div class="card-header">

            <h4 class="card-title mb-0">
                Record Information
            </h4>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="text-muted d-block">
                        Recorded By
                    </label>

                    <strong>
                        {{ $staffAttendance->recordedBy->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted d-block">
                        Created At
                    </label>

                    <strong>
                        {{ optional($staffAttendance->created_at)->format('Y-m-d H:i:s') }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="text-muted d-block">
                        Last Updated
                    </label>

                    <strong>
                        {{ optional($staffAttendance->updated_at)->format('Y-m-d H:i:s') }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="card">

        <div class="card-body">

            <div class="d-flex gap-2">

                <a
                    href="{{ route('admin.staff-attendance.index') }}"
                    class="btn btn-light"
                >
                    <i class="bx bx-arrow-back me-1"></i>
                    Back to Attendance
                </a>


                @can('staff-attendance.update')

                    <a
                        href="{{ route(
                            'admin.staff-attendance.edit',
                            $staffAttendance
                        ) }}"
                        class="btn btn-warning"
                    >
                        <i class="bx bx-edit me-1"></i>
                        Edit Attendance
                    </a>

                @endcan

            </div>

        </div>

    </div>

</div>

@endsection