@extends('backend.layout.default')

@section('content')

<div class="container">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="mb-1">
                Student Attendance
            </h1>

            <p class="text-muted mb-0">
                View and manage student attendance records.
            </p>

        </div>


        <div class="d-flex gap-2">

            @can('attendance.reports')

                <a
                    href="{{ route('admin.attendance.reports') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bx bx-bar-chart-alt-2 me-1"></i>
                    Attendance Reports
                </a>

            @endcan


            @can('attendance.mark')

                <a
                    href="{{ route('admin.attendance.create') }}"
                    class="btn btn-primary"
                >
                    <i class="bx bx-check-circle me-1"></i>
                    Mark Attendance
                </a>

            @endcan

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERROR MESSAGE --}}
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


    {{-- ========================================================= --}}
    {{-- FILTERS --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Attendance Filters
            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.attendance.index') }}"
            >

                <div class="row">


                    {{-- Date --}}

                    <div class="col-md-2 mb-3">

                        <label
                            for="attendance_date"
                            class="form-label"
                        >
                            Date
                        </label>

                        <input
                            type="date"
                            id="attendance_date"
                            name="attendance_date"
                            class="form-control"
                            value="{{ request('attendance_date') }}"
                        >

                    </div>


                    {{-- Class --}}

                    <div class="col-md-2 mb-3">

                        <label
                            for="filter_class_id"
                            class="form-label"
                        >
                            Class
                        </label>

                        <select
                            id="filter_class_id"
                            name="class_id"
                            class="form-select"
                        >

                            <option value="">
                                All Classes
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    @selected(
                                        request('class_id') == $class->id
                                    )
                                >
                                    {{ $class->name }}

                                    @if($class->code)
                                        ({{ $class->code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Section --}}

                    <div class="col-md-2 mb-3">

                        <label
                            for="filter_section_id"
                            class="form-label"
                        >
                            Section
                        </label>

                        <select
                            id="filter_section_id"
                            name="section_id"
                            class="form-select"
                        >

                            <option value="">
                                All Sections
                            </option>

                            @foreach($sections as $section)

                                <option
                                    value="{{ $section->id }}"
                                    data-class-id="{{ $section->class_id }}"
                                    @selected(
                                        request('section_id') == $section->id
                                    )
                                >
                                    {{ $section->name }}

                                    @if($section->code)
                                        ({{ $section->code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Attendance Type --}}

                    <div class="col-md-2 mb-3">

                        <label
                            for="attendance_type"
                            class="form-label"
                        >
                            Type
                        </label>

                        <select
                            id="attendance_type"
                            name="attendance_type"
                            class="form-select"
                        >

                            <option value="">
                                All Types
                            </option>

                            <option
                                value="daily"
                                @selected(
                                    request('attendance_type') === 'daily'
                                )
                            >
                                Daily / Class
                            </option>

                            <option
                                value="subject"
                                @selected(
                                    request('attendance_type') === 'subject'
                                )
                            >
                                Subject / Course
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-2 mb-3">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-select attendance-status-filter"
                            style="width: 100%; min-width: 180px; left: 10%; top: 74%;" 
                        >

                            <option value="">
                                All Statuses
                            </option>

                            <option
                                value="present"
                                @selected(
                                    request('status') === 'present'
                                )
                            >
                                Present
                            </option>

                            <option
                                value="absent"
                                @selected(
                                    request('status') === 'absent'
                                )
                            >
                                Absent
                            </option>

                            <option
                                value="late"
                                @selected(
                                    request('status') === 'late'
                                )
                            >
                                Late
                            </option>

                            <option
                                value="excused"
                                @selected(
                                    request('status') === 'excused'
                                )
                            >
                                Excused
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-md-2 mb-3">

                        <label class="form-label d-block">
                            &nbsp;
                        </label>

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bx bx-filter-alt me-1"></i>
                                Filter
                            </button>

                            <a
                                href="{{ route('admin.attendance.index') }}"
                                class="btn btn-outline-secondary"
                                title="Clear Filters"
                            >
                                <i class="bx bx-reset"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ATTENDANCE RECORDS --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Attendance Records
                </h5>

                <span class="text-muted">

                    {{ $attendance->total() }}

                    {{ $attendance->total() === 1
                        ? 'record'
                        : 'records'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($attendance->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0">

                        <thead>

                            <tr>

                                <th style="width: 60px;">
                                    #
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Student
                                </th>

                                <th>
                                    Student Number
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
                                    Course
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Recorded By
                                </th>

                                <th style="width: 120px;">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($attendance as $record)

                                <tr>

                                    {{-- Number --}}

                                    <td>
                                        {{ $attendance->firstItem() + $loop->index }}
                                    </td>


                                    {{-- Date --}}

                                    <td>
                                        {{ $record->attendance_date?->format('d M Y') }}
                                    </td>


                                    {{-- Student --}}

                                    <td>

                                        @if($record->student)

                                            <strong>
                                                {{ $record->student->full_name }}
                                            </strong>

                                        @else

                                            <span class="text-muted">
                                                Student unavailable
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Student Number --}}

                                    <td>
                                        {{ $record->student?->student_number ?? '-' }}
                                    </td>


                                    {{-- Class --}}

                                    <td>
                                        {{ $record->classModel?->name ?? '-' }}
                                    </td>


                                    {{-- Section --}}

                                    <td>
                                        {{ $record->section?->name ?? '-' }}
                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        @if($record->course_id)

                                            <span class="badge bg-info">
                                                Subject
                                            </span>

                                        @else

                                            <span class="badge bg-primary">
                                                Daily
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Course --}}

                                    <td>
                                        {{ $record->course?->name ?? '-' }}
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

                                            @case('excused')

                                                <span class="badge bg-secondary">
                                                    Excused
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-dark">
                                                    {{ ucfirst($record->status) }}
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Recorded By --}}

                                    <td>
                                        {{ $record->recordedBy?->name ?? '-' }}
                                    </td>


                                    {{-- Actions --}}

                                    <td>

                                        <div class="d-flex gap-1">

                                            @can('attendance.view')

                                                <a
                                                    href="{{ route(
                                                        'admin.attendance.show',
                                                        $record
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="View"
                                                >
                                                    <i class="bx bx-show"></i>
                                                </a>

                                            @endcan


                                            @can('attendance.update')

                                                <a
                                                    href="{{ route(
                                                        'admin.attendance.edit',
                                                        $record
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="Edit"
                                                >
                                                    <i class="bx bx-edit"></i>
                                                </a>

                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- ================================================= --}}
                {{-- PAGINATION --}}
                {{-- ================================================= --}}

                <div class="p-3">

                    {{ $attendance->links() }}

                </div>

            @else

                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bx bx-calendar-x"
                            style="font-size: 48px;"
                        ></i>

                    </div>

                    <h5>
                        No Attendance Records Found
                    </h5>

                    <p class="text-muted mb-4">

                        @if(
                            request()->hasAny([
                                'attendance_date',
                                'class_id',
                                'section_id',
                                'attendance_type',
                                'status'
                            ])
                        )

                            No attendance records match
                            your selected filters.

                        @else

                            No student attendance has been
                            recorded yet.

                        @endif

                    </p>


                    @can('attendance.mark')

                        <a
                            href="{{ route('admin.attendance.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="bx bx-check-circle me-1"></i>
                            Mark Student Attendance
                        </a>

                    @endcan

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =============================================================== --}}
{{-- ATTENDANCE LISTING JAVASCRIPT --}}
{{-- =============================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect =
        document.getElementById('filter_class_id');

    const sectionSelect =
        document.getElementById('filter_section_id');


    if (!classSelect || !sectionSelect) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Filter Sections by Class
    |--------------------------------------------------------------------------
    */

    function filterSections() {

        const classId =
            classSelect.value;

        const currentSection =
            sectionSelect.value;


        Array.from(
            sectionSelect.options
        ).forEach(
            function (option, index) {

                /*
                | Always show "All Sections".
                */

                if (index === 0) {

                    option.hidden =
                        false;

                    return;

                }


                const sectionClassId =
                    option.dataset.classId;


                /*
                | No Class selected:
                | show all sections.
                */

                if (!classId) {

                    option.hidden =
                        false;

                    return;

                }


                /*
                | Show only sections belonging
                | to selected class.
                */

                option.hidden =
                    String(sectionClassId) !==
                    String(classId);

            }
        );


        /*
        | If currently selected section does not
        | belong to selected class, clear it.
        */

        const selectedOption =
            sectionSelect.options[
                sectionSelect.selectedIndex
            ];


        if (
            selectedOption &&
            selectedOption.hidden
        ) {

            sectionSelect.value = '';

        }

    }


    classSelect.addEventListener(
        'change',
        filterSections
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Filtering
    |--------------------------------------------------------------------------
    */

    filterSections();

});

</script>

@endsection