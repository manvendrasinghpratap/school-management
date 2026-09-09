@extends('backend.layout.default')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1>System Settings</h1>

        <a
            href="{{ route('admin.school.edit', $school) }}"
            class="btn btn-secondary"
        >
            School Profile
        </a>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form
        method="POST"
        action="{{ route('admin.school.settings.update', $school) }}"
    >

        @csrf
        @method('PUT')


        {{-- ========================================================= --}}
        {{-- GENERAL SETTINGS --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>General Settings</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Timezone --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="timezone"
                            class="form-label"
                        >
                            Timezone
                        </label>

                        <select
                            id="timezone"
                            name="timezone"
                            class="form-select @error('timezone') is-invalid @enderror"
                        >

                            @foreach([
                                'UTC',
                                'Asia/Kolkata',
                                'Africa/Lagos',
                                'Africa/Nairobi',
                                'America/New_York',
                                'Europe/London'
                            ] as $timezone)

                                <option
                                    value="{{ $timezone }}"
                                    @selected(
                                        old(
                                            'timezone',
                                            $settings['timezone'] ?? 'UTC'
                                        ) === $timezone
                                    )
                                >
                                    {{ $timezone }}
                                </option>

                            @endforeach

                        </select>

                        @error('timezone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Date Format --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="date_format"
                            class="form-label"
                        >
                            Date Format
                        </label>

                        <select
                            id="date_format"
                            name="date_format"
                            class="form-select @error('date_format') is-invalid @enderror"
                        >

                            @foreach([
                                'Y-m-d' => 'YYYY-MM-DD',
                                'd-m-Y' => 'DD-MM-YYYY',
                                'm/d/Y' => 'MM/DD/YYYY',
                                'd/m/Y' => 'DD/MM/YYYY'
                            ] as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'date_format',
                                            $settings['date_format'] ?? 'Y-m-d'
                                        ) === $value
                                    )
                                >
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                        @error('date_format')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Currency --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="currency"
                            class="form-label"
                        >
                            Currency
                        </label>

                        <input
                            type="text"
                            id="currency"
                            name="currency"
                            class="form-control @error('currency') is-invalid @enderror"
                            value="{{ old('currency', $settings['currency'] ?? 'NGN') }}"
                            maxlength="10"
                        >

                        @error('currency')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Language --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="language"
                            class="form-label"
                        >
                            Language
                        </label>

                        <input
                            type="text"
                            id="language"
                            name="language"
                            class="form-control @error('language') is-invalid @enderror"
                            value="{{ old('language', $settings['language'] ?? 'en') }}"
                            maxlength="10"
                        >

                        @error('language')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- MODULE SETTINGS --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Module Settings</strong>
            </div>

            <div class="card-body">


                {{-- ================================================= --}}
                {{-- ATTENDANCE SETTINGS --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <h5 class="mb-3">
                        Attendance
                    </h5>


                    {{-- Attendance Enabled --}}
                    <div class="form-check mb-3">

                        <input
                            type="hidden"
                            name="attendance_enabled"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            name="attendance_enabled"
                            value="1"
                            class="form-check-input @error('attendance_enabled') is-invalid @enderror"
                            id="attendance_enabled"
                            @checked(
                                old(
                                    'attendance_enabled',
                                    filter_var(
                                        $settings['attendance_enabled'] ?? true,
                                        FILTER_VALIDATE_BOOLEAN
                                    )
                                )
                            )
                        >

                        <label
                            for="attendance_enabled"
                            class="form-check-label"
                        >
                            Enable Attendance Module
                        </label>

                        @error('attendance_enabled')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Attendance Configuration --}}
                    <div
                        id="attendance_configuration"
                        class="ms-4"
                    >


                        {{-- ----------------------------------------- --}}
                        {{-- Attendance Mode --}}
                        {{-- ----------------------------------------- --}}

                        <div class="mb-3">

                            <label
                                for="attendance_mode"
                                class="form-label"
                            >
                                Attendance Mode
                            </label>

                            <select
                                id="attendance_mode"
                                name="attendance_mode"
                                class="form-select @error('attendance_mode') is-invalid @enderror"
                            >

                                <option
                                    value="daily"
                                    @selected(
                                        old(
                                            'attendance_mode',
                                            $settings['attendance_mode'] ?? 'both'
                                        ) === 'daily'
                                    )
                                >
                                    Daily / Class Attendance
                                </option>

                                <option
                                    value="subject"
                                    @selected(
                                        old(
                                            'attendance_mode',
                                            $settings['attendance_mode'] ?? 'both'
                                        ) === 'subject'
                                    )
                                >
                                    Subject / Course Attendance
                                </option>

                                <option
                                    value="both"
                                    @selected(
                                        old(
                                            'attendance_mode',
                                            $settings['attendance_mode'] ?? 'both'
                                        ) === 'both'
                                    )
                                >
                                    Both
                                </option>

                            </select>

                            @error('attendance_mode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Choose whether attendance is recorded daily,
                                by subject/course, or using both methods.
                            </div>

                        </div>


                        {{-- ----------------------------------------- --}}
                        {{-- Allow Late Attendance --}}
                        {{-- ----------------------------------------- --}}

                        <div class="form-check mb-3">

                            <input
                                type="hidden"
                                name="attendance_allow_late"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="attendance_allow_late"
                                value="1"
                                class="form-check-input @error('attendance_allow_late') is-invalid @enderror"
                                id="attendance_allow_late"
                                @checked(
                                    old(
                                        'attendance_allow_late',
                                        filter_var(
                                            $settings['attendance_allow_late'] ?? true,
                                            FILTER_VALIDATE_BOOLEAN
                                        )
                                    )
                                )
                            >

                            <label
                                for="attendance_allow_late"
                                class="form-check-label"
                            >
                                Allow Late Attendance
                            </label>

                            @error('attendance_allow_late')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Allow teachers or authorized staff to mark
                                a student as late.
                            </div>

                        </div>


                        {{-- ----------------------------------------- --}}
                        {{-- Allow Excused Attendance --}}
                        {{-- ----------------------------------------- --}}

                        <div class="form-check mb-3">

                            <input
                                type="hidden"
                                name="attendance_allow_excused"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="attendance_allow_excused"
                                value="1"
                                class="form-check-input @error('attendance_allow_excused') is-invalid @enderror"
                                id="attendance_allow_excused"
                                @checked(
                                    old(
                                        'attendance_allow_excused',
                                        filter_var(
                                            $settings['attendance_allow_excused'] ?? true,
                                            FILTER_VALIDATE_BOOLEAN
                                        )
                                    )
                                )
                            >

                            <label
                                for="attendance_allow_excused"
                                class="form-check-label"
                            >
                                Allow Excused Attendance
                            </label>

                            @error('attendance_allow_excused')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Allow authorized staff to mark a student
                                as excused.
                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- GRADING SETTINGS --}}
                {{-- ================================================= --}}

                <div class="form-check">

                    <input
                        type="hidden"
                        name="grading_enabled"
                        value="0"
                    >

                    <input
                        type="checkbox"
                        name="grading_enabled"
                        value="1"
                        class="form-check-input @error('grading_enabled') is-invalid @enderror"
                        id="grading_enabled"
                        @checked(
                            old(
                                'grading_enabled',
                                filter_var(
                                    $settings['grading_enabled'] ?? true,
                                    FILTER_VALIDATE_BOOLEAN
                                )
                            )
                        )
                    >

                    <label
                        for="grading_enabled"
                        class="form-check-label"
                    >
                        Enable Grading System
                    </label>

                    @error('grading_enabled')
                        <div class="text-danger small">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="d-flex gap-2 mb-4">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Settings
            </button>

            <a
                href="{{ route('admin.school.edit', $school) }}"
                class="btn btn-outline-secondary"
            >
                Back to School Profile
            </a>

        </div>

    </form>

</div>


{{-- =============================================================== --}}
{{-- ATTENDANCE CONFIGURATION TOGGLE --}}
{{-- =============================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const attendanceEnabled = document.getElementById(
        'attendance_enabled'
    );

    const attendanceConfiguration = document.getElementById(
        'attendance_configuration'
    );

    const attendanceMode = document.getElementById(
        'attendance_mode'
    );

    const attendanceAllowLate = document.getElementById(
        'attendance_allow_late'
    );

    const attendanceAllowExcused = document.getElementById(
        'attendance_allow_excused'
    );


    function toggleAttendanceConfiguration() {

        if (
            !attendanceEnabled ||
            !attendanceConfiguration
        ) {
            return;
        }


        if (attendanceEnabled.checked) {

            attendanceConfiguration.style.display = '';


            if (attendanceMode) {
                attendanceMode.disabled = false;
            }


            if (attendanceAllowLate) {
                attendanceAllowLate.disabled = false;
            }


            if (attendanceAllowExcused) {
                attendanceAllowExcused.disabled = false;
            }


        } else {

            attendanceConfiguration.style.display = 'none';


            if (attendanceMode) {
                attendanceMode.disabled = true;
            }


            if (attendanceAllowLate) {
                attendanceAllowLate.disabled = true;
            }


            if (attendanceAllowExcused) {
                attendanceAllowExcused.disabled = true;
            }

        }

    }


    attendanceEnabled.addEventListener(
        'change',
        toggleAttendanceConfiguration
    );


    toggleAttendanceConfiguration();

});

</script>

@endsection