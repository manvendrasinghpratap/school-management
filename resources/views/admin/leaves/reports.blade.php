@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">
                    Leave Reports
                </h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.leaves.index') }}"
                       class="btn btn-light">

                        <i class="mdi mdi-arrow-left me-1"></i>

                        Back to Leave Requests
                    </a>
                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="mdi mdi-check-circle-outline me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- =========================================================
         REPORT FILTERS
    ========================================================== --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">

                <i class="mdi mdi-filter-outline me-1"></i>

                Report Filters

            </h5>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.leaves.reports') }}">

                <div class="row g-3">


                    {{-- Start Date --}}
                    <div class="col-xl-3 col-md-6">

                        <label for="start_date"
                               class="form-label">

                            Start Date

                        </label>

                        <input type="date"
                               name="start_date"
                               id="start_date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date', request('start_date')) }}">

                        @error('start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- End Date --}}
                    <div class="col-xl-3 col-md-6">

                        <label for="end_date"
                               class="form-label">

                            End Date

                        </label>

                        <input type="date"
                               name="end_date"
                               id="end_date"
                               class="form-control @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date', request('end_date')) }}">

                        @error('end_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Staff --}}
                    <div class="col-xl-3 col-md-6">

                        <label for="staff_id"
                               class="form-label">

                            Staff

                        </label>

                        <select name="staff_id"
                                id="staff_id"
                                class="form-select @error('staff_id') is-invalid @enderror">

                            <option value="">
                                All Staff
                            </option>

                            @foreach($staff as $member)

                                <option value="{{ $member->id }}"
                                    {{ (string) request('staff_id') === (string) $member->id ? 'selected' : '' }}>

                                    {{ $member->first_name }}
                                    {{ $member->middle_name }}
                                    {{ $member->last_name }}

                                    @if($member->employee_number)
                                        — {{ $member->employee_number }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('staff_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-xl-3 col-md-6">

                        <label for="status"
                               class="form-label">

                            Status

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror">

                            <option value="">
                                All Statuses
                            </option>

                            <option value="pending"
                                {{ request('status') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="approved"
                                {{ request('status') === 'approved' ? 'selected' : '' }}>
                                Approved
                            </option>

                            <option value="rejected"
                                {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                            <option value="cancelled"
                                {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Buttons --}}
                    <div class="col-12">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="mdi mdi-filter me-1"></i>

                            Generate Report

                        </button>


                        <a href="{{ route('admin.leaves.reports') }}"
                           class="btn btn-light">

                            <i class="mdi mdi-refresh me-1"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         SUMMARY CARDS
    ========================================================== --}}
    <div class="row">


        {{-- Total Requests --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-1">
                                Total Requests
                            </p>

                            <h4 class="mb-0">
                                {{ $summary['total'] }}
                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title bg-primary-subtle text-primary rounded">

                                <i class="mdi mdi-calendar-multiple-check font-size-24"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-1">
                                Pending
                            </p>

                            <h4 class="mb-0">
                                {{ $summary['pending'] }}
                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title bg-warning-subtle text-warning rounded">

                                <i class="mdi mdi-clock-outline font-size-24"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-1">
                                Approved
                            </p>

                            <h4 class="mb-0">
                                {{ $summary['approved'] }}
                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title bg-success-subtle text-success rounded">

                                <i class="mdi mdi-check-circle-outline font-size-24"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Rejected --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-1">
                                Rejected
                            </p>

                            <h4 class="mb-0">
                                {{ $summary['rejected'] }}
                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title bg-danger-subtle text-danger rounded">

                                <i class="mdi mdi-close-circle-outline font-size-24"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Cancelled --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-1">
                                Cancelled
                            </p>

                            <h4 class="mb-0">
                                {{ $summary['cancelled'] }}
                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title bg-secondary-subtle text-secondary rounded">

                                <i class="mdi mdi-cancel font-size-24"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Leave Days --}}
        <div class="col-xl-3 col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="flex-grow-1">

                            <p class="text-muted mb-1">
                                Total Leave Days
                            </p>

                            <h4 class="mb-0">
                                {{ $summary['total_days'] }}
                            </h4>

                        </div>

                        <div class="avatar-sm">

                            <span class="avatar-title bg-info-subtle text-info rounded">

                                <i class="mdi mdi-calendar-range font-size-24"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         REPORT TABLE
    ========================================================== --}}
    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Leave Report Details
            </h5>

        </div>


        <div class="card-body">

            @if($leaves->count())


                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Staff
                                </th>

                                <th>
                                    Leave Type
                                </th>

                                <th>
                                    Start Date
                                </th>

                                <th>
                                    End Date
                                </th>

                                <th>
                                    Days
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Approved / Rejected By
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($leaves as $leave)

                                @php

                                    $days = $leave->start_date->diffInDays(
                                        $leave->end_date
                                    ) + 1;

                                @endphp


                                <tr>


                                    {{-- Number --}}
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- Staff --}}
                                    <td>

                                        <strong>

                                            {{ $leave->staff->first_name ?? '' }}

                                            {{ $leave->staff->middle_name ?? '' }}

                                            {{ $leave->staff->last_name ?? '' }}

                                        </strong>


                                        @if($leave->staff?->employee_number)

                                            <div class="text-muted small">

                                                {{ $leave->staff->employee_number }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Leave Type --}}
                                    <td>

                                        {{ $leave->leave_type }}

                                    </td>


                                    {{-- Start Date --}}
                                    <td>

                                        {{ $leave->start_date?->format('Y-m-d') }}

                                    </td>


                                    {{-- End Date --}}
                                    <td>

                                        {{ $leave->end_date?->format('Y-m-d') }}

                                    </td>


                                    {{-- Days --}}
                                    <td>

                                        <strong>
                                            {{ $days }}
                                        </strong>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @switch($leave->status)


                                            @case('pending')

                                                <span class="badge bg-warning text-dark">

                                                    Pending

                                                </span>

                                                @break


                                            @case('approved')

                                                <span class="badge bg-success">

                                                    Approved

                                                </span>

                                                @break


                                            @case('rejected')

                                                <span class="badge bg-danger">

                                                    Rejected

                                                </span>

                                                @break


                                            @case('cancelled')

                                                <span class="badge bg-secondary">

                                                    Cancelled

                                                </span>

                                                @break


                                            @default

                                                <span class="badge bg-dark">

                                                    {{ ucfirst($leave->status) }}

                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Approved / Rejected By --}}
                                    <td>

                                        {{ $leave->approvedBy?->name ?? '—' }}

                                    </td>


                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Report Footer --}}
                <div class="mt-3">

                    <div class="text-muted">

                        Showing
                        <strong>{{ $leaves->count() }}</strong>
                        leave request(s).

                        Total leave days:
                        <strong>{{ $summary['total_days'] }}</strong>.

                    </div>

                </div>


            @else


                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="mdi mdi-file-chart-outline"
                           style="font-size: 48px;">
                        </i>

                    </div>


                    <h5>
                        No Leave Records Found
                    </h5>


                    <p class="text-muted mb-0">

                        No leave requests match the selected
                        report filters.

                    </p>

                </div>


            @endif

        </div>

    </div>

</div>

@endsection