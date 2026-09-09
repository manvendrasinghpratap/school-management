@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Edit Staff Attendance
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
                            Edit
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

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

                {{-- Staff --}}
                <div class="col-md-6 mb-3">

                    <label class="text-muted d-block">
                        Staff Member
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

                    @if(!empty($staffAttendance->staff->employee_number))

                        <div class="text-muted small mt-1">
                            Employee Number:
                            {{ $staffAttendance->staff->employee_number }}
                        </div>

                    @endif

                </div>


                {{-- Date --}}
                <div class="col-md-6 mb-3">

                    <label class="text-muted d-block">
                        Attendance Date
                    </label>

                    <strong>
                        {{ optional($staffAttendance->attendance_date)->format('Y-m-d') }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Edit Attendance --}}
    <div class="card">

        <div class="card-header">

            <h4 class="card-title mb-1">
                Attendance Details
            </h4>

            <p class="text-muted mb-0">
                Update the attendance status and time information.
            </p>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.staff-attendance.update',
                    $staffAttendance
                ) }}"
            >

                @csrf

                @method('PUT')


                <div class="row g-3">

                    {{-- Status --}}
                    <div class="col-md-6">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Attendance Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            style="top: 72%;left: 5%; width: 97%;"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Status
                            </option>

                            <option
                                value="present"
                                {{ old('status', $staffAttendance->status) === 'present' ? 'selected' : '' }}
                            >
                                Present
                            </option>

                            <option
                                value="absent"
                                {{ old('status', $staffAttendance->status) === 'absent' ? 'selected' : '' }}
                            >
                                Absent
                            </option>

                            <option
                                value="late"
                                {{ old('status', $staffAttendance->status) === 'late' ? 'selected' : '' }}
                            >
                                Late
                            </option>

                            <option
                                value="half_day"
                                {{ old('status', $staffAttendance->status) === 'half_day' ? 'selected' : '' }}
                            >
                                Half Day
                            </option>

                            <option
                                value="leave"
                                {{ old('status', $staffAttendance->status) === 'leave' ? 'selected' : '' }}
                            >
                                Leave
                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Check In --}}
                    <div class="col-md-3">

                        <label
                            for="check_in"
                            class="form-label"
                        >
                            Check In
                        </label>

                        <input
                            type="time"
                            id="check_in"
                            name="check_in"
                            class="form-control @error('check_in') is-invalid @enderror"
                            value="{{ old(
                                'check_in',
                                $staffAttendance->check_in
                                    ? substr($staffAttendance->check_in, 0, 5)
                                    : ''
                            ) }}"
                        >

                        @error('check_in')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Check Out --}}
                    <div class="col-md-3">

                        <label
                            for="check_out"
                            class="form-label"
                        >
                            Check Out
                        </label>

                        <input
                            type="time"
                            id="check_out"
                            name="check_out"
                            class="form-control @error('check_out') is-invalid @enderror"
                            value="{{ old(
                                'check_out',
                                $staffAttendance->check_out
                                    ? substr($staffAttendance->check_out, 0, 5)
                                    : ''
                            ) }}"
                        >

                        @error('check_out')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Remarks --}}
                    <div class="col-md-12">

                        <label
                            for="remarks"
                            class="form-label"
                        >
                            Remarks
                        </label>

                        <textarea
                            id="remarks"
                            name="remarks"
                            rows="4"
                            maxlength="1000"
                            class="form-control @error('remarks') is-invalid @enderror"
                            placeholder="Optional remarks..."
                        >{{ old('remarks', $staffAttendance->remarks) }}</textarea>

                        @error('remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-12">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bx bx-save me-1"></i>
                                Update Attendance
                            </button>

                            <a
                                href="{{ route(
                                    'admin.staff-attendance.show',
                                    $staffAttendance
                                ) }}"
                                class="btn btn-light"
                            >
                                <i class="bx bx-x me-1"></i>
                                Cancel
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection