@extends('backend.layout.default')

@section('content')

<div class="container">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="mb-1">
                Edit Attendance
            </h1>

            <p class="text-muted mb-0">
                Update the attendance status and remarks.
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.attendance.show', $attendance) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bx bx-arrow-back me-1"></i>
                Back to Details
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

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

        </div>

    @endif


    <div class="row">

        {{-- ===================================================== --}}
        {{-- ATTENDANCE INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="col-lg-6 mb-4">

            <div class="card h-100">

                <div class="card-header">

                    <h5 class="mb-0">
                        Attendance Information
                    </h5>

                </div>

                <div class="card-body">

                    {{-- Student --}}

                    <div class="mb-4">

                        <div class="text-muted small">
                            Student
                        </div>

                        <strong class="fs-5">

                            {{ $attendance->student?->full_name ?? 'Student unavailable' }}

                        </strong>

                    </div>


                    {{-- Student Number --}}

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Student Number
                        </div>

                        <div class="col-7">

                            {{ $attendance->student?->student_number ?? '-' }}

                        </div>

                    </div>


                    {{-- Date --}}

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Attendance Date
                        </div>

                        <div class="col-7">

                            {{ $attendance->attendance_date?->format('d M Y') ?? '-' }}

                        </div>

                    </div>


                    {{-- Class --}}

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Class
                        </div>

                        <div class="col-7">

                            {{ $attendance->classModel?->name ?? '-' }}

                        </div>

                    </div>


                    {{-- Section --}}

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Section
                        </div>

                        <div class="col-7">

                            {{ $attendance->section?->name ?? '-' }}

                        </div>

                    </div>


                    {{-- Type --}}

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Attendance Type
                        </div>

                        <div class="col-7">

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

                    @if($attendance->course_id)

                        <div class="row mb-3">

                            <div class="col-5 text-muted">
                                Subject / Course
                            </div>

                            <div class="col-7">

                                {{ $attendance->course?->name ?? '-' }}

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- EDIT FORM --}}
        {{-- ===================================================== --}}

        <div class="col-lg-6 mb-4">

            <div class="card h-100">

                <div class="card-header">

                    <h5 class="mb-0">
                        Update Attendance
                    </h5>

                </div>

                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.attendance.update', $attendance) }}"
                    >

                        @csrf

                        @method('PUT')


                        {{-- Status --}}

                        <div class="mb-4">

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
                                style="width: 100%; min-width: 180px; left: 5%; top: 25%;" 
                                class="form-select attendance-status-filter @error('status') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Status
                                </option>

                                <option
                                    value="present"
                                    @selected(
                                        old(
                                            'status',
                                            $attendance->status
                                        ) === 'present'
                                    )
                                >
                                    Present
                                </option>

                                <option
                                    value="absent"
                                    @selected(
                                        old(
                                            'status',
                                            $attendance->status
                                        ) === 'absent'
                                    )
                                >
                                    Absent
                                </option>

                                @if($attendanceAllowLate)

                                    <option
                                        value="late"
                                        @selected(
                                            old(
                                                'status',
                                                $attendance->status
                                            ) === 'late'
                                        )
                                    >
                                        Late
                                    </option>

                                @endif


                                @if($attendanceAllowExcused)

                                    <option
                                        value="excused"
                                        @selected(
                                            old(
                                                'status',
                                                $attendance->status
                                            ) === 'excused'
                                        )
                                    >
                                        Excused
                                    </option>

                                @endif

                            </select>

                            @error('status')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Remarks --}}

                        <div class="mb-4">

                            <label
                                for="remarks"
                                class="form-label"
                            >
                                Remarks
                            </label>

                            <textarea
                                id="remarks"
                                name="remarks"
                                rows="5"
                                maxlength="1000"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter any relevant remarks..."
                            >{{ old('remarks', $attendance->remarks) }}</textarea>

                            @error('remarks')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div class="form-text">
                                Maximum 1000 characters.
                            </div>

                        </div>


                        {{-- Buttons --}}

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('admin.attendance.show', $attendance) }}"
                                class="btn btn-outline-secondary"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bx bx-save me-1"></i>
                                Update Attendance
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection