@extends('backend.layout.default')

@section('title', 'Staff Attendance Report')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Staff Attendance Report</h4>
            <p class="text-muted mb-0">
                Staff attendance records by date, staff member and status.
            </p>
        </div>

        {{-- Export Buttons --}}
        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.reports.staff.pdf', request()->query()) }}"
                class="btn btn-outline-danger"
            >
                <i class="ri-file-pdf-line me-1"></i>
                PDF
            </a>

            <a
                href="{{ route('admin.reports.staff.excel', request()->query()) }}"
                class="btn btn-outline-success"
            >
                <i class="ri-file-excel-line me-1"></i>
                Excel
            </a>

        </div>
    </div>


    {{-- Filters --}}
    <div class="card mb-3">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reports.staff') }}"
                class="row g-2"
            >

                {{-- From --}}
                <div class="col-md-2">

                    <label class="form-label">
                        Start Date
                    </label>

                    <input
                        type="date"
                        name="from"
                        class="form-control"
                        value="{{ request('from', $from) }}"
                    >

                </div>


                {{-- To --}}
                <div class="col-md-2">

                    <label class="form-label">
                        End Date
                    </label>

                    <input
                        type="date"
                        name="to"
                        class="form-control"
                        value="{{ request('to', $to) }}"
                    >

                </div>


                {{-- Staff Search --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Staff
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Staff number or name"
                        value="{{ request('search') }}"
                    >

                </div>


                {{-- Status --}}
                <div class="col-md-2">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            All statuses
                        </option>

                        @foreach([
                            'present',
                            'absent',
                            'late',
                            'half_day',
                            'leave'
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(request('status') === $status)
                            >
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Filter --}}
                <div class="col-md-1 d-flex align-items-end">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        <i class="ri-filter-line me-1"></i>
                        Go
                    </button>

                </div>


                {{-- Reset --}}
                <div class="col-md-1 d-flex align-items-end">

                    <a
                        href="{{ route('admin.reports.staff') }}"
                        class="btn btn-light w-100"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- Summary --}}
    <div class="row g-3 mb-3">

        @php
            $totalRecords = $records->total();

            $present = $summary
                ->firstWhere('status', 'present')
                ->total ?? 0;

            $absent = $summary
                ->firstWhere('status', 'absent')
                ->total ?? 0;

            $late = $summary
                ->firstWhere('status', 'late')
                ->total ?? 0;

            $halfDay = $summary
                ->firstWhere('status', 'half_day')
                ->total ?? 0;

            $leave = $summary
                ->firstWhere('status', 'leave')
                ->total ?? 0;

            $attendanceRate = $totalRecords > 0
                ? ($present / $totalRecords) * 100
                : 0;
        @endphp


        {{-- Total --}}
        <div class="col-md-2">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Total Records
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($totalRecords) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Present --}}
        <div class="col-md-2">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Present
                    </div>

                    <h4 class="mb-0 text-success">
                        {{ number_format($present) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Absent --}}
        <div class="col-md-2">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Absent
                    </div>

                    <h4 class="mb-0 text-danger">
                        {{ number_format($absent) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Late --}}
        <div class="col-md-2">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Late
                    </div>

                    <h4 class="mb-0 text-warning">
                        {{ number_format($late) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Half Day --}}
        <div class="col-md-2">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Half Day
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($halfDay) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Leave --}}
        <div class="col-md-2">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Leave
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($leave) }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Attendance Rate --}}
    <div class="row mb-3">

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Attendance Rate
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($attendanceRate, 2) }}%
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Report Table --}}
    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                Staff Attendance Report
            </h5>

        </div>


        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Staff</th>

                        <th>Attendance Date</th>

                        <th>Status</th>

                        <th>Check In</th>

                        <th>Check Out</th>

                        <th>Recorded By</th>

                        <th>Remarks</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($records as $record)

                        <tr>

                            <td>
                                {{ $records->firstItem() + $loop->index }}
                            </td>


                            <td>

                                <strong>
                                    {{ $record->staff?->first_name }}
                                    {{ $record->staff?->middle_name }}
                                    {{ $record->staff?->last_name }}
                                </strong>

                                @if($record->staff?->staff_number)

                                    <div class="text-muted small">
                                        {{ $record->staff->staff_number }}
                                    </div>

                                @endif

                            </td>


                            <td>
                                {{ optional($record->attendance_date)->format('d M Y') }}
                            </td>


                            <td>

                                @php
                                    $statusClass = match ($record->status) {
                                        'present' => 'bg-success',
                                        'absent' => 'bg-danger',
                                        'late' => 'bg-warning text-dark',
                                        'half_day' => 'bg-info',
                                        'leave' => 'bg-secondary',
                                        default => 'bg-light text-dark',
                                    };
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                </span>

                            </td>


                            <td>
                                {{ $record->check_in ?? '-' }}
                            </td>


                            <td>
                                {{ $record->check_out ?? '-' }}
                            </td>


                            <td>
                                {{ $record->recordedBy?->name ?? '-' }}
                            </td>


                            <td>
                                {{ $record->remarks ?? '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center text-muted py-4"
                            >
                                No staff attendance records found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>


            {{-- Pagination --}}
            @if($records->hasPages())

                <div class="mt-3">

                    {{ $records->links() }}

                </div>

            @endif


            <div class="text-muted mt-3">

                Showing
                {{ $records->count() }}
                staff attendance record(s).

            </div>

        </div>

    </div>

</div>
@endsection