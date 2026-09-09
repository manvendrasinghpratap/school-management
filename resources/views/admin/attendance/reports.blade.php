@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">
                    Attendance Reports
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
                            Reports
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
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
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
                Attendance Report Filters
            </h4>

            <p class="text-muted mb-0">
                Select the criteria you want to include in the attendance report.
            </p>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.attendance.reports') }}">

                <div class="row g-3">

                    {{-- Date From --}}
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">
                            Date From
                        </label>

                        <input
                            type="date"
                            id="date_from"
                            name="date_from"
                            class="form-control"
                            value="{{ $dateFrom }}"
                        >
                    </div>


                    {{-- Date To --}}
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">
                            Date To
                        </label>

                        <input
                            type="date"
                            id="date_to"
                            name="date_to"
                            class="form-control"
                            value="{{ $dateTo }}"
                        >
                    </div>


                    {{-- Class --}}
                    <div class="col-md-3">
                        <label for="class_id" class="form-label">
                            Class
                        </label>

                        <select
                            id="class_id"
                            name="class_id"
                            class="form-select"
                        >
                            <option value="">
                                All Classes
                            </option>

                            @foreach($classes as $class)
                                <option
                                    value="{{ $class->id }}"
                                    {{ (string) $classId === (string) $class->id ? 'selected' : '' }}
                                >
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Section --}}
                    <div class="col-md-3">
                        <label for="section_id" class="form-label">
                            Section
                        </label>

                        <select
                            id="section_id"
                            name="section_id"
                            class="form-select"
                        >
                            <option value="">
                                All Sections
                            </option>

                            @foreach($sections as $section)
                                <option
                                    value="{{ $section->id }}"
                                    {{ (string) $sectionId === (string) $section->id ? 'selected' : '' }}
                                >
                                     {{ $section->class_name }} - {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Attendance Type --}}
                    <div class="col-md-3">
                        <label for="type" class="form-label">
                            Attendance Type
                        </label>

                        <select
                            id="type"
                            name="type"
                            class="form-select"
                        >
                            <option value="all"
                                {{ $type === 'all' ? 'selected' : '' }}>
                                All Types
                            </option>

                            <option value="daily"
                                {{ $type === 'daily' ? 'selected' : '' }}>
                                Daily / Class
                            </option>

                            <option value="subject"
                                {{ $type === 'subject' ? 'selected' : '' }}>
                                Subject
                            </option>
                        </select>
                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">
                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-select"
                            style="width: 100%; min-width: 180px; left: 6%; top: 70%;"
                        >
                            <option value="all"
                                {{ $status === 'all' ? 'selected' : '' }}>
                                All Statuses
                            </option>

                            <option value="present"
                                {{ $status === 'present' ? 'selected' : '' }}>
                                Present
                            </option>

                            <option value="absent"
                                {{ $status === 'absent' ? 'selected' : '' }}>
                                Absent
                            </option>

                            <option value="late"
                                {{ $status === 'late' ? 'selected' : '' }}>
                                Late
                            </option>

                            <option value="excused"
                                {{ $status === 'excused' ? 'selected' : '' }}>
                                Excused
                            </option>
                        </select>
                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-6 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2"
                        >
                            <i class="bx bx-search-alt me-1"></i>
                            Generate Report
                        </button>

                        <a
                            href="{{ route('admin.attendance.reports') }}"
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


    {{-- Summary Cards --}}
    <div class="row">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-calendar-check"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">

                            <p class="text-muted mb-1">
                                Total Records
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($total) }}
                            </h4>

                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Present --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-success-subtle text-success font-size-20">
                                <i class="bx bx-check-circle"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">

                            <p class="text-muted mb-1">
                                Present
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($present) }}
                            </h4>

                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Absent --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-20">
                                <i class="bx bx-x-circle"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">

                            <p class="text-muted mb-1">
                                Absent
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($absent) }}
                            </h4>

                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Percentage --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-info-subtle text-info font-size-20">
                                <i class="bx bx-bar-chart-alt-2"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-3">

                            <p class="text-muted mb-1">
                                Attendance Rate
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($attendancePercentage, 2) }}%
                            </h4>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- Additional Status Summary --}}
    <div class="card">

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-3">
                    <p class="text-muted mb-1">
                        Present
                    </p>

                    <h5 class="text-success">
                        {{ number_format($present) }}
                    </h5>
                </div>

                <div class="col-md-3">
                    <p class="text-muted mb-1">
                        Absent
                    </p>

                    <h5 class="text-danger">
                        {{ number_format($absent) }}
                    </h5>
                </div>

                <div class="col-md-3">
                    <p class="text-muted mb-1">
                        Late
                    </p>

                    <h5 class="text-warning">
                        {{ number_format($late) }}
                    </h5>
                </div>

                <div class="col-md-3">
                    <p class="text-muted mb-1">
                        Excused
                    </p>

                    <h5 class="text-info">
                        {{ number_format($excused) }}
                    </h5>
                </div>

            </div>

        </div>

    </div>


    {{-- Student Summary --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex align-items-center justify-content-between">

                <div>
                    <h4 class="card-title mb-1">
                        Student Attendance Summary
                    </h4>

                    <p class="text-muted mb-0">
                        Attendance performance for the selected criteria.
                    </p>
                </div>

            </div>

        </div>

        <div class="card-body">

            @if($studentSummary->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th style="width: 60px;">
                                    #
                                </th>

                                <th>
                                    Student
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Present
                                </th>

                                <th>
                                    Absent
                                </th>

                                <th>
                                    Late
                                </th>

                                <th>
                                    Excused
                                </th>

                                <th>
                                    Attendance %
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($studentSummary as $summary)

                                @php
                                    $student = $summary['student'];
                                @endphp

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ trim(
                                                ($student->first_name ?? '')
                                                . ' '
                                                . ($student->middle_name ?? '')
                                                . ' '
                                                . ($student->last_name ?? '')
                                            ) }}
                                        </strong>

                                        @if(!empty($student->student_number))
                                            <div class="text-muted small">
                                                {{ $student->student_number }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        {{ number_format($summary['total']) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-success">
                                            {{ number_format($summary['present']) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-danger">
                                            {{ number_format($summary['absent']) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            {{ number_format($summary['late']) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-info">
                                            {{ number_format($summary['excused']) }}
                                        </span>
                                    </td>

                                    <td>

                                        @if($summary['percentage'] >= 75)

                                            <span class="badge bg-success">
                                                {{ number_format($summary['percentage'], 2) }}%
                                            </span>

                                        @elseif($summary['percentage'] >= 50)

                                            <span class="badge bg-warning text-dark">
                                                {{ number_format($summary['percentage'], 2) }}%
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                {{ number_format($summary['percentage'], 2) }}%
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bx bx-calendar-x"
                           style="font-size: 48px;"></i>
                    </div>

                    <h5>
                        No Attendance Records Found
                    </h5>

                    <p class="text-muted mb-0">
                        No attendance records match the selected filters.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Detailed Records --}}
    <div class="card">

        <div class="card-header">

            <h4 class="card-title mb-1">
                Detailed Attendance Records
            </h4>

            <p class="text-muted mb-0">
                Individual attendance records matching the selected filters.
            </p>

        </div>

        <div class="card-body">

            @if($records->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Student
                                </th>

                                <th>
                                    Class
                                </th>

                                <th>
                                    Section
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Recorded By
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($records as $record)

                                <tr>

                                    <td>
                                        {{ optional($record->attendance_date)->format('Y-m-d') }}
                                    </td>

                                    <td>

                                        <strong>
                                            {{ trim(
                                                ($record->student->first_name ?? '')
                                                . ' '
                                                . ($record->student->middle_name ?? '')
                                                . ' '
                                                . ($record->student->last_name ?? '')
                                            ) }}
                                        </strong>

                                        @if(!empty($record->student->student_number))
                                            <div class="text-muted small">
                                                {{ $record->student->student_number }}
                                            </div>
                                        @endif

                                    </td>

                                    <td>
                                        {{ $record->classModel->name ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $record->section->name ?? '—' }}
                                    </td>

                                    <td>

                                        @if($record->course_id)

                                            <span class="badge bg-info">
                                                Subject
                                            </span>

                                        @else

                                            <span class="badge bg-primary">
                                                Daily / Class
                                            </span>

                                        @endif

                                    </td>

                                    <td>
                                        {{ $record->course->name ?? '—' }}
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

                                            @case('excused')
                                                <span class="badge bg-info">
                                                    Excused
                                                </span>
                                                @break

                                            @default
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst($record->status) }}
                                                </span>

                                        @endswitch

                                    </td>

                                    <td>
                                        {{ $record->recordedBy->name ?? '—' }}
                                    </td>

                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.attendance.show',
                                                $record
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="bx bx-show"></i>
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-4 text-muted">
                    No detailed attendance records available.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection