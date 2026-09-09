@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Mark Staff Attendance
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
                            Mark Attendance
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


    {{-- Form --}}
    <div class="card">

        <div class="card-header">

            <h4 class="card-title mb-1">
                Staff Attendance
            </h4>

            <p class="text-muted mb-0">
                Record attendance for a staff member.
            </p>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.staff-attendance.store') }}"
            >

                @csrf


                <div class="row g-3">

                    {{-- Staff --}}
                    <div class="col-md-6">

                        <label
                            for="staff_id"
                            class="form-label"
                        >
                            Staff Member
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="staff_id"
                            name="staff_id"
                            class="form-select @error('staff_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Staff Member
                            </option>

                            @foreach($staff as $member)

                                @php
                                    $staffName = trim(
                                        ($member->first_name ?? '')
                                        . ' '
                                        . ($member->middle_name ?? '')
                                        . ' '
                                        . ($member->last_name ?? '')
                                    );
                                @endphp

                                <option
                                    value="{{ $member->id }}"
                                    {{ old('staff_id') == $member->id ? 'selected' : '' }}
                                >
                                    {{ $staffName ?: 'Staff #' . $member->id }}
                                    @if(!empty($member->employee_number))
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


                    {{-- Date --}}
                    <div class="col-md-6">

                        <label
                            for="attendance_date"
                            class="form-label"
                        >
                            Attendance Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            id="attendance_date"
                            name="attendance_date"
                            class="form-control @error('attendance_date') is-invalid @enderror"
                            value="{{ old('attendance_date', now()->format('Y-m-d')) }}"
                            required
                        >

                        @error('attendance_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


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
                                {{ old('status') === 'present' ? 'selected' : '' }}
                            >
                                Present
                            </option>

                            <option
                                value="absent"
                                {{ old('status') === 'absent' ? 'selected' : '' }}
                            >
                                Absent
                            </option>

                            <option
                                value="late"
                                {{ old('status') === 'late' ? 'selected' : '' }}
                            >
                                Late
                            </option>

                            <option
                                value="half_day"
                                {{ old('status') === 'half_day' ? 'selected' : '' }}
                            >
                                Half Day
                            </option>

                            <option
                                value="leave"
                                {{ old('status') === 'leave' ? 'selected' : '' }}
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
                            value="{{ old('check_in') }}"
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
                            value="{{ old('check_out') }}"
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
                        >{{ old('remarks') }}</textarea>

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
                                <i class="bx bx-check me-1"></i>
                                Save Attendance
                            </button>

                            <a
                                href="{{ route('admin.staff-attendance.index') }}"
                                class="btn btn-light"
                            >
                                <i class="bx bx-arrow-back me-1"></i>
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