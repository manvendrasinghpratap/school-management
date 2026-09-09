@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Page Header --}}
    {{-- ========================================================= --}}

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-flex align-items-center justify-content-between">

                <h4 class="mb-0">
                    STAFF ATTENDANCE REPORTS
                </h4>

                <a href="{{ route('admin.staff-attendance.index') }}"
                   class="btn btn-light">

                    <i class="bx bx-arrow-back me-1"></i>
                    Back to Staff Attendance

                </a>

            </div>

        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- Report Filters --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="bx bx-filter-alt me-2"></i>

                Report Filters

            </h5>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.staff-attendance.reports') }}">

                <div class="row g-3">

                    {{-- Start Date --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Start Date
                        </label>

                        <input type="date"
                               name="start_date"
                               class="form-control"
                               value="{{ request('start_date') }}">

                    </div>


                    {{-- End Date --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            End Date
                        </label>

                        <input type="date"
                               name="end_date"
                               class="form-control"
                               value="{{ request('end_date') }}">

                    </div>


                    {{-- Staff --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Staff
                        </label>

                        <select name="staff_id"
                                class="form-select">

                            <option value="">
                                All Staff
                            </option>

                            @foreach($staff as $member)

                                <option value="{{ $member->id }}"
                                    @selected(
                                        (string) request('staff_id') ===
                                        (string) $member->id
                                    )>

                                    {{ trim(
                                        $member->first_name . ' ' .
                                        ($member->middle_name ?? '') . ' ' .
                                        $member->last_name
                                    ) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            <option value="present"
                                @selected(request('status') === 'present')>
                                Present
                            </option>

                            <option value="absent"
                                @selected(request('status') === 'absent')>
                                Absent
                            </option>

                            <option value="late"
                                @selected(request('status') === 'late')>
                                Late
                            </option>

                            <option value="half_day"
                                @selected(request('status') === 'half_day')>
                                Half Day
                            </option>

                            <option value="leave"
                                @selected(request('status') === 'leave')>
                                Leave
                            </option>

                        </select>

                    </div>

                </div>


                <div class="mt-3">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bx bx-filter-alt me-1"></i>

                        Generate Report

                    </button>


                    <a href="{{ route('admin.staff-attendance.reports') }}"
                       class="btn btn-light ms-1">

                        <i class="bx bx-reset me-1"></i>

                        Reset

                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Summary Cards --}}
    {{-- ========================================================= --}}

    <div class="row">

        {{-- Total --}}
        <div class="col-xl-2 col-md-4">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Total Records
                    </p>

                    <h4 class="mb-0">
                        {{ $summary['total'] }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Present --}}
        <div class="col-xl-2 col-md-4">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Present
                    </p>

                    <h4 class="mb-0 text-success">
                        {{ $summary['present'] }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Absent --}}
        <div class="col-xl-2 col-md-4">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Absent
                    </p>

                    <h4 class="mb-0 text-danger">
                        {{ $summary['absent'] }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Late --}}
        <div class="col-xl-2 col-md-4">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Late
                    </p>

                    <h4 class="mb-0 text-warning">
                        {{ $summary['late'] }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Half Day --}}
        <div class="col-xl-2 col-md-4">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Half Day
                    </p>

                    <h4 class="mb-0">
                        {{ $summary['half_day'] }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Leave --}}
        <div class="col-xl-2 col-md-4">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Leave
                    </p>

                    <h4 class="mb-0">
                        {{ $summary['leave'] }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Attendance Rate --}}
    {{-- ========================================================= --}}

    <div class="row">

        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Attendance Rate
                    </p>

                    <h3 class="mb-0">
                        {{ number_format($attendanceRate, 2) }}%
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Report Details --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                Staff Attendance Report
            </h5>

        </div>


        <div class="card-body">

            @if($attendance->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Staff
                                </th>

                                <th>
                                    Attendance Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Check In
                                </th>

                                <th>
                                    Check Out
                                </th>

                                <th>
                                    Recorded By
                                </th>

                                <th>
                                    Remarks
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($attendance as $record)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <strong>

                                            {{ trim(
                                                $record->staff->first_name . ' ' .
                                                ($record->staff->middle_name ?? '') . ' ' .
                                                $record->staff->last_name
                                            ) }}

                                        </strong>

                                    </td>


                                    <td>

                                        {{ $record->attendance_date
                                            ? $record->attendance_date->format('Y-m-d')
                                            : '—'
                                        }}

                                    </td>


                                    <td>

                                        @switch($record->status)

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

                                                <span class="badge bg-light text-dark">
                                                    {{ ucfirst($record->status) }}
                                                </span>

                                        @endswitch

                                    </td>


                                    <td>
                                        {{ $record->check_in ?: '—' }}
                                    </td>


                                    <td>
                                        {{ $record->check_out ?: '—' }}
                                    </td>


                                    <td>
                                        {{ $record->recordedBy->name ?? '—' }}
                                    </td>


                                    <td>
                                        {{ $record->remarks ?: '—' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <div class="mt-3 text-muted">

                    Showing
                    <strong>{{ $attendance->count() }}</strong>
                    staff attendance record(s).

                </div>


            @else

                <div class="text-center py-5">

                    <i class="bx bx-calendar-x"
                       style="font-size: 48px;"></i>

                    <h5 class="mt-3">
                        No Staff Attendance Records Found
                    </h5>

                    <p class="text-muted mb-0">

                        There are no staff attendance records
                        matching the selected filters.

                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection