@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Staff Attendance
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            Attendance
                        </li>

                        <li class="breadcrumb-item active">
                            Staff Attendance
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


    {{-- Error Message --}}
    @if($errors->any())
        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- Filters --}}
    <div class="card">

        <div class="card-header">

            <h4 class="card-title mb-1">
                Staff Attendance Filters
            </h4>

            <p class="text-muted mb-0">
                Filter staff attendance records.
            </p>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.staff-attendance.index') }}"
            >

                <div class="row g-3">

                    {{-- Date --}}
                    <div class="col-md-3">

                        <label
                            for="date"
                            class="form-label"
                        >
                            Date
                        </label>

                        <input
                            type="date"
                            id="date"
                            name="date"
                            class="form-control"
                            value="{{ $date }}"
                        >

                    </div>


                    {{-- Staff --}}
                    <div class="col-md-3">

                        <label
                            for="staff_id"
                            class="form-label"
                        >
                            Staff
                        </label>

                        <select
                            id="staff_id"
                            name="staff_id"
                            class="form-select"
                        >

                            <option value="">
                                All Staff
                            </option>

                            @foreach($staff as $member)

                                <option
                                    value="{{ $member->id }}"
                                    {{ (string) $staffId === (string) $member->id ? 'selected' : '' }}
                                >
                                    {{ trim(
                                        ($member->first_name ?? '')
                                        . ' '
                                        . ($member->middle_name ?? '')
                                        . ' '
                                        . ($member->last_name ?? '')
                                    ) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-select staff-attendance-status-filter"
                            style="left: 7%; top: 73%;"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            <option
                                value="present"
                                {{ $status === 'present' ? 'selected' : '' }}
                            >
                                Present
                            </option>

                            <option
                                value="absent"
                                {{ $status === 'absent' ? 'selected' : '' }}
                            >
                                Absent
                            </option>

                            <option
                                value="late"
                                {{ $status === 'late' ? 'selected' : '' }}
                            >
                                Late
                            </option>

                            <option
                                value="half_day"
                                {{ $status === 'half_day' ? 'selected' : '' }}
                            >
                                Half Day
                            </option>

                            <option
                                value="leave"
                                {{ $status === 'leave' ? 'selected' : '' }}
                            >
                                Leave
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-3 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2"
                        >
                            <i class="bx bx-search-alt me-1"></i>
                            Filter
                        </button>

                        <a
                            href="{{ route('admin.staff-attendance.index') }}"
                            class="btn btn-light"
                        >
                            <i class="bx bx-reset me-1"></i>
                            Clear
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Attendance Listing --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex align-items-center justify-content-between">

                <div>

                    <h4 class="card-title mb-1">
                        Staff Attendance Records
                    </h4>

                    <p class="text-muted mb-0">
                        View and manage staff attendance.
                    </p>

                </div>

                <div>

                    @can('staff-attendance.mark')

                        <a
                            href="{{ route('admin.staff-attendance.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="bx bx-plus me-1"></i>
                            Mark Attendance
                        </a>

                    @endcan

                </div>

            </div>

        </div>


        <div class="card-body">

            @if($attendanceRecords->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th style="width: 60px;">
                                    #
                                </th>

                                <th>
                                    Staff
                                </th>

                                <th>
                                    Date
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

                                <th style="width: 180px;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($attendanceRecords as $record)

                                @php
                                    $member = $record->staff;

                                    $staffName = trim(
                                        ($member->first_name ?? '')
                                        . ' '
                                        . ($member->middle_name ?? '')
                                        . ' '
                                        . ($member->last_name ?? '')
                                    );
                                @endphp

                                <tr>

                                    {{-- Number --}}
                                    <td>
                                        {{ $attendanceRecords->firstItem() + $loop->index }}
                                    </td>


                                    {{-- Staff --}}
                                    <td>

                                        <strong>
                                            {{ $staffName ?: '—' }}
                                        </strong>

                                        @if(!empty($member?->employee_number))

                                            <div class="text-muted small">
                                                {{ $member->employee_number }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        {{ optional($record->attendance_date)->format('Y-m-d') }}

                                    </td>


                                    {{-- Status --}}
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

                                                <span class="badge bg-dark">
                                                    {{ ucfirst($record->status) }}
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Check In --}}
                                    <td>

                                        {{ $record->check_in ?: '—' }}

                                    </td>


                                    {{-- Check Out --}}
                                    <td>

                                        {{ $record->check_out ?: '—' }}

                                    </td>


                                    {{-- Recorded By --}}
                                    <td>

                                        {{ $record->recordedBy->name ?? '—' }}

                                    </td>


                                    {{-- Remarks --}}
                                    <td>

                                        @if($record->remarks)

                                            <span
                                                title="{{ $record->remarks }}"
                                            >
                                                {{ \Illuminate\Support\Str::limit(
                                                    $record->remarks,
                                                    40
                                                ) }}
                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td>

                                        <div class="d-flex gap-1">

                                            @can('staff-attendance.view')

                                                <a
                                                    href="{{ route(
                                                        'admin.staff-attendance.show',
                                                        $record
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="View"
                                                >
                                                    <i class="bx bx-show"></i>
                                                </a>

                                            @endcan


                                            @can('staff-attendance.update')

                                                <a
                                                    href="{{ route(
                                                        'admin.staff-attendance.edit',
                                                        $record
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="Edit"
                                                >
                                                    <i class="bx bx-edit"></i>
                                                </a>


                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.staff-attendance.destroy',
                                                        $record
                                                    ) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this attendance record?');"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete"
                                                    >
                                                        <i class="bx bx-trash"></i>
                                                    </button>

                                                </form>

                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                <div class="mt-3">

                    {{ $attendanceRecords->links() }}

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bx bx-calendar-x"
                            style="font-size: 48px;"
                        ></i>

                    </div>

                    <h5>
                        No Staff Attendance Records Found
                    </h5>

                    <p class="text-muted mb-3">
                        There are no staff attendance records matching the selected filters.
                    </p>

                    @can('staff-attendance.mark')

                        <a
                            href="{{ route('admin.staff-attendance.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="bx bx-plus me-1"></i>
                            Mark Staff Attendance
                        </a>

                    @endcan

                </div>

            @endif

        </div>

    </div>

</div>


<style>
    .staff-attendance-status-filter {
        width: 100% !important;
        min-width: 180px !important;
    }
</style>

@endsection