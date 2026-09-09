@extends('backend.layout.default')

@section('content')

<div class="container">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="mb-1">
                Mark Student Attendance
            </h1>

            <p class="text-muted mb-0">
                Record attendance for students by class, section,
                and date.
            </p>
        </div>

        <a
            href="{{ route('admin.attendance.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bx bx-arrow-back me-1"></i>
            Back to Attendance
        </a>

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


    {{-- ========================================================= --}}
    {{-- ATTENDANCE FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route('admin.attendance.store') }}"
        id="attendance_form"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- ATTENDANCE SELECTION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Attendance Selection
                </h5>

            </div>

            <div class="card-body">

                <div class="row">


                    {{-- ================================================= --}}
                    {{-- ATTENDANCE DATE --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4 mb-3">

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
                            class="form-control"
                            value="{{ old('attendance_date', now()->format('Y-m-d')) }}"
                            required
                        >

                        <div class="form-text">
                            Select the date for which attendance
                            is being recorded.
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- CLASS --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4 mb-3">

                        <label
                            for="class_id"
                            class="form-label"
                        >
                            Class
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="class_id"
                            name="class_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    @selected(old('class_id') == $class->id)
                                >
                                    {{ $class->name }}

                                    @if(!empty($class->code))
                                        ({{ $class->code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Select the class first.
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- SECTION --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4 mb-3">

                        <label
                            for="section_id"
                            class="form-label"
                        >
                            Section
                        </label>

                        <select
                            id="section_id"
                            name="section_id"
                            class="form-select"
                            disabled
                        >

                            <option value="">
                                Select Section
                            </option>

                        </select>

                        <div
                            id="section_help"
                            class="form-text"
                        >
                            Sections will be loaded after
                            selecting a class.
                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ATTENDANCE TYPE --}}
                {{-- ================================================= --}}

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="attendance_type"
                            class="form-label"
                        >
                            Attendance Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="attendance_type"
                            name="attendance_type"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Attendance Type
                            </option>


                            {{-- ------------------------------------- --}}
                            {{-- DAILY ATTENDANCE --}}
                            {{-- ------------------------------------- --}}

                            @if(in_array($attendanceMode, ['daily', 'both']))

                                <option
                                    value="daily"
                                    @selected(
                                        old('attendance_type') === 'daily'
                                    )
                                >
                                    Daily / Class Attendance
                                </option>

                            @endif


                            {{-- ------------------------------------- --}}
                            {{-- SUBJECT ATTENDANCE --}}
                            {{-- ------------------------------------- --}}

                            @if(in_array($attendanceMode, ['subject', 'both']))

                                <option
                                    value="subject"
                                    @selected(
                                        old('attendance_type') === 'subject'
                                    )
                                >
                                    Subject / Course Attendance
                                </option>

                            @endif

                        </select>

                        <div class="form-text">
                            Attendance type is controlled by the
                            school's Attendance Mode setting.
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- COURSE --}}
                    {{-- ================================================= --}}

                    <div
                        class="col-md-6 mb-3"
                        id="course_container"
                        style="display: none;"
                    >

                        <label
                            for="course_id"
                            class="form-label"
                        >
                            Subject / Course
                        </label>

                        <select
                            id="course_id"
                            name="course_id"
                            class="form-select"
                            disabled
                        >

                            <option value="">
                                Select Subject / Course
                            </option>

                            @foreach($courses as $course)

                                <option
                                    value="{{ $course->id }}"
                                    @selected(
                                        old('course_id') == $course->id
                                    )
                                >
                                    {{ $course->name }}

                                    @if(!empty($course->course_code))
                                        ({{ $course->course_code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Required when recording subject/course
                            attendance.
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- STUDENT ATTENDANCE --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        Student Attendance
                    </h5>

                    <span
                        class="badge bg-secondary"
                        id="student_count"
                    >
                        0 Students
                    </span>

                </div>

            </div>


            <div class="card-body">


                {{-- ================================================= --}}
                {{-- LOADING STATE --}}
                {{-- ================================================= --}}

                <div
                    id="students_loading_state"
                    class="text-center py-5"
                    style="display: none;"
                >

                    <div class="mb-3">

                        <div
                            class="spinner-border text-primary"
                            role="status"
                        >

                            <span class="visually-hidden">
                                Loading...
                            </span>

                        </div>

                    </div>

                    <h5>
                        Loading Students
                    </h5>

                    <p class="text-muted mb-0">
                        Please wait while the enrolled students
                        are loaded.
                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div
                    id="students_empty_state"
                    class="text-center py-5"
                >

                    <div class="mb-3">

                        <i
                            class="bx bx-group"
                            style="font-size: 48px;"
                        ></i>

                    </div>

                    <h5 id="students_empty_title">
                        Select Class and Section
                    </h5>

                    <p
                        class="text-muted mb-0"
                        id="students_empty_message"
                    >
                        Students will appear here after selecting
                        the attendance class and section.
                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- ERROR STATE --}}
                {{-- ================================================= --}}

                <div
                    id="students_error_state"
                    class="alert alert-danger"
                    style="display: none;"
                >

                    <strong>
                        Unable to load students.
                    </strong>

                    <div
                        id="students_error_message"
                        class="mt-1"
                    >
                        Please try again.
                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- STUDENT TABLE --}}
                {{-- ================================================= --}}

                <div
                    id="students_table_container"
                    class="table-responsive"
                    style="display: none;"
                >

                    <table class="table table-bordered align-middle">

                        <thead>

                            <tr>

                                <th style="width: 60px;">
                                    #
                                </th>

                                <th>
                                    Student
                                </th>

                                <th>
                                    Student Number
                                </th>

                                <th style="width: 220px;">
                                    Status
                                </th>

                                <th>
                                    Remarks
                                </th>

                            </tr>

                        </thead>

                        <tbody id="students_table_body">

                            {{-- Students inserted by JavaScript --}}

                        </tbody>

                    </table>

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
                id="save_attendance"
                disabled
            >
                <i class="bx bx-save me-1"></i>
                Save Attendance
            </button>

            <a
                href="{{ route('admin.attendance.index') }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

        </div>

    </form>

</div>


{{-- =============================================================== --}}
{{-- ATTENDANCE PAGE JAVASCRIPT --}}
{{-- =============================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const classSelect =
        document.getElementById('class_id');

    const sectionSelect =
        document.getElementById('section_id');

    const attendanceType =
        document.getElementById('attendance_type');

    const courseSelect =
        document.getElementById('course_id');

    const courseContainer =
        document.getElementById('course_container');

    const studentsLoadingState =
        document.getElementById('students_loading_state');

    const studentsEmptyState =
        document.getElementById('students_empty_state');

    const studentsEmptyTitle =
        document.getElementById('students_empty_title');

    const studentsEmptyMessage =
        document.getElementById('students_empty_message');

    const studentsErrorState =
        document.getElementById('students_error_state');

    const studentsErrorMessage =
        document.getElementById('students_error_message');

    const studentsTableContainer =
        document.getElementById('students_table_container');

    const studentsTableBody =
        document.getElementById('students_table_body');

    const studentCount =
        document.getElementById('student_count');

    const saveAttendance =
        document.getElementById('save_attendance');

    const sectionHelp =
        document.getElementById('section_help');


    /*
    |--------------------------------------------------------------------------
    | Section Data
    |--------------------------------------------------------------------------
    */

    const sections = {!! json_encode(
        $sections->map(function ($section) {
            return [
                'id' => $section->id,
                'class_id' => $section->class_id,
                'name' => $section->name,
                'code' => $section->code,
            ];
        })->values()->toArray()
    ) !!};


    /*
    |--------------------------------------------------------------------------
    | Initial State
    |--------------------------------------------------------------------------
    */

    sectionSelect.disabled = true;

    courseContainer.style.display = 'none';

    courseSelect.disabled = true;

    studentsLoadingState.style.display = 'none';

    studentsEmptyState.style.display = '';

    studentsErrorState.style.display = 'none';

    studentsTableContainer.style.display = 'none';

    studentsTableBody.innerHTML = '';

    studentCount.textContent =
        '0 Students';

    saveAttendance.disabled = true;


    /*
    |--------------------------------------------------------------------------
    | Attendance Type
    |--------------------------------------------------------------------------
    */

    function updateAttendanceType() {

        if (attendanceType.value === 'subject') {

            courseContainer.style.display = '';

            courseSelect.disabled = false;

        } else {

            courseContainer.style.display = 'none';

            courseSelect.disabled = true;

            courseSelect.value = '';

        }

    }


    attendanceType.addEventListener(
        'change',
        updateAttendanceType
    );


    /*
    |--------------------------------------------------------------------------
    | Initialize Attendance Type
    |--------------------------------------------------------------------------
    */

    updateAttendanceType();


    /*
    |--------------------------------------------------------------------------
    | Reset Student Area
    |--------------------------------------------------------------------------
    */

    function resetStudentArea() {

        studentsLoadingState.style.display =
            'none';

        studentsErrorState.style.display =
            'none';

        studentsTableContainer.style.display =
            'none';

        studentsEmptyState.style.display =
            '';

        studentsEmptyTitle.textContent =
            'Select Class and Section';

        studentsEmptyMessage.textContent =
            'Students will appear here after selecting the attendance class and section.';

        studentsTableBody.innerHTML =
            '';

        studentCount.textContent =
            '0 Students';

        saveAttendance.disabled =
            true;

    }


    /*
    |--------------------------------------------------------------------------
    | Loading State
    |--------------------------------------------------------------------------
    */

    function showStudentLoading() {

        studentsLoadingState.style.display =
            '';

        studentsEmptyState.style.display =
            'none';

        studentsErrorState.style.display =
            'none';

        studentsTableContainer.style.display =
            'none';

        studentsTableBody.innerHTML =
            '';

        studentCount.textContent =
            'Loading...';

        saveAttendance.disabled =
            true;

    }


    /*
    |--------------------------------------------------------------------------
    | Error State
    |--------------------------------------------------------------------------
    */

    function showStudentError(message) {

        studentsLoadingState.style.display =
            'none';

        studentsEmptyState.style.display =
            'none';

        studentsTableContainer.style.display =
            'none';

        studentsErrorState.style.display =
            '';

        studentsErrorMessage.textContent =
            message;

        studentCount.textContent =
            '0 Students';

        saveAttendance.disabled =
            true;

    }


    /*
    |--------------------------------------------------------------------------
    | No Students
    |--------------------------------------------------------------------------
    */

    function showNoStudents() {

        studentsLoadingState.style.display =
            'none';

        studentsErrorState.style.display =
            'none';

        studentsTableContainer.style.display =
            'none';

        studentsEmptyState.style.display =
            '';

        studentsEmptyTitle.textContent =
            'No Students Found';

        studentsEmptyMessage.textContent =
            'There are no active enrolled students in the selected class and section.';

        studentCount.textContent =
            '0 Students';

        saveAttendance.disabled =
            true;

    }


    /*
    |--------------------------------------------------------------------------
    | Display Students
    |--------------------------------------------------------------------------
    */

    function displayStudents(students) {

        studentsLoadingState.style.display =
            'none';

        studentsErrorState.style.display =
            'none';

        studentsEmptyState.style.display =
            'none';

        studentsTableContainer.style.display =
            '';

        studentsTableBody.innerHTML =
            '';


        students.forEach(
            function (student, index) {

                const row =
                    document.createElement('tr');


                /*
                |--------------------------------------------------------------------------
                | Number
                |--------------------------------------------------------------------------
                */

                const numberCell =
                    document.createElement('td');

                numberCell.textContent =
                    index + 1;


                /*
                |--------------------------------------------------------------------------
                | Student Name
                |--------------------------------------------------------------------------
                */

                const nameCell =
                    document.createElement('td');

                const nameStrong =
                    document.createElement('strong');

                nameStrong.textContent =
                    student.full_name || '-';

                nameCell.appendChild(
                    nameStrong
                );


                /*
                |--------------------------------------------------------------------------
                | Student Number
                |--------------------------------------------------------------------------
                */

                const studentNumberCell =
                    document.createElement('td');

                studentNumberCell.textContent =
                    student.student_number || '-';


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                const statusCell =
                    document.createElement('td');

                const statusSelect =
                    document.createElement('select');

                statusSelect.className =
                    'form-select attendance-status';

                statusSelect.name =
                    `attendance[${student.id}][status]`;

                statusSelect.dataset.studentId =
                    student.id;

                statusSelect.required =
                    true;


                /*
                |--------------------------------------------------------------------------
                | Select Status
                |--------------------------------------------------------------------------
                */

                const defaultOption =
                    document.createElement('option');

                defaultOption.value =
                    '';

                defaultOption.textContent =
                    'Select Status';

                statusSelect.appendChild(
                    defaultOption
                );


                /*
                |--------------------------------------------------------------------------
                | Present
                |--------------------------------------------------------------------------
                */

                const presentOption =
                    document.createElement('option');

                presentOption.value =
                    'present';

                presentOption.textContent =
                    'Present';

                statusSelect.appendChild(
                    presentOption
                );


                /*
                |--------------------------------------------------------------------------
                | Absent
                |--------------------------------------------------------------------------
                */

                const absentOption =
                    document.createElement('option');

                absentOption.value =
                    'absent';

                absentOption.textContent =
                    'Absent';

                statusSelect.appendChild(
                    absentOption
                );


                /*
                |--------------------------------------------------------------------------
                | Late
                |--------------------------------------------------------------------------
                */

                @if($attendanceAllowLate)

                    const lateOption =
                        document.createElement('option');

                    lateOption.value =
                        'late';

                    lateOption.textContent =
                        'Late';

                    statusSelect.appendChild(
                        lateOption
                    );

                @endif


                /*
                |--------------------------------------------------------------------------
                | Excused
                |--------------------------------------------------------------------------
                */

                @if($attendanceAllowExcused)

                    const excusedOption =
                        document.createElement('option');

                    excusedOption.value =
                        'excused';

                    excusedOption.textContent =
                        'Excused';

                    statusSelect.appendChild(
                        excusedOption
                    );

                @endif


                statusCell.appendChild(
                    statusSelect
                );


                /*
                |--------------------------------------------------------------------------
                | Remarks
                |--------------------------------------------------------------------------
                */

                const remarksCell =
                    document.createElement('td');

                const remarksInput =
                    document.createElement('input');

                remarksInput.type =
                    'text';

                remarksInput.className =
                    'form-control';

                remarksInput.name =
                    `attendance[${student.id}][remarks]`;

                remarksInput.placeholder =
                    'Optional remarks';

                remarksInput.maxLength =
                    1000;

                remarksCell.appendChild(
                    remarksInput
                );


                /*
                |--------------------------------------------------------------------------
                | Hidden Student ID
                |--------------------------------------------------------------------------
                */

                const hiddenStudentId =
                    document.createElement('input');

                hiddenStudentId.type =
                    'hidden';

                hiddenStudentId.name =
                    `attendance[${student.id}][student_id]`;

                hiddenStudentId.value =
                    student.id;

                remarksCell.appendChild(
                    hiddenStudentId
                );


                /*
                |--------------------------------------------------------------------------
                | Build Row
                |--------------------------------------------------------------------------
                */

                row.appendChild(
                    numberCell
                );

                row.appendChild(
                    nameCell
                );

                row.appendChild(
                    studentNumberCell
                );

                row.appendChild(
                    statusCell
                );

                row.appendChild(
                    remarksCell
                );


                studentsTableBody.appendChild(
                    row
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Student Count
        |--------------------------------------------------------------------------
        */

        studentCount.textContent =
            students.length === 1
                ? '1 Student'
                : `${students.length} Students`;


        /*
        |--------------------------------------------------------------------------
        | Validate Statuses
        |--------------------------------------------------------------------------
        */

        validateAttendanceStatuses();

    }


    /*
    |--------------------------------------------------------------------------
    | Validate Attendance Statuses
    |--------------------------------------------------------------------------
    */

    function validateAttendanceStatuses() {

        const statusFields =
            studentsTableBody.querySelectorAll(
                '.attendance-status'
            );


        /*
        | No students.
        */

        if (statusFields.length === 0) {

            saveAttendance.disabled =
                true;

            return;

        }


        let allSelected =
            true;


        statusFields.forEach(
            function (field) {

                if (!field.value) {

                    allSelected =
                        false;

                }

            }
        );


        saveAttendance.disabled =
            !allSelected;

    }


    /*
    |--------------------------------------------------------------------------
    | Load Students
    |--------------------------------------------------------------------------
    */

    async function loadStudents() {

        const classId =
            classSelect.value;

        const sectionId =
            sectionSelect.value;


        /*
        | Both Class and Section are required.
        */

        if (!classId || !sectionId) {

            resetStudentArea();

            return;

        }


        showStudentLoading();


        /*
        |--------------------------------------------------------------------------
        | Build Request URL
        |--------------------------------------------------------------------------
        */

        const url =
            new URL(
                @json(route('admin.attendance.students')),
                window.location.origin
            );


        url.searchParams.set(
            'class_id',
            classId
        );

        url.searchParams.set(
            'section_id',
            sectionId
        );


        try {

            const response =
                await fetch(
                    url.toString(),
                    {
                        method: 'GET',

                        headers: {
                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'
                        }
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | HTTP Error
            |--------------------------------------------------------------------------
            */

            if (!response.ok) {

                let errorMessage =
                    'Unable to load students. Please try again.';


                try {

                    const errorData =
                        await response.json();

                    if (errorData.message) {

                        errorMessage =
                            errorData.message;

                    }

                } catch (error) {

                    // Response was not JSON.

                }


                throw new Error(
                    errorMessage
                );

            }


            /*
            |--------------------------------------------------------------------------
            | JSON Response
            |--------------------------------------------------------------------------
            */

            const data =
                await response.json();


            /*
            |--------------------------------------------------------------------------
            | Validate Response
            |--------------------------------------------------------------------------
            */

            if (
                !data ||
                data.success !== true
            ) {

                throw new Error(
                    'The server returned an invalid student response.'
                );

            }


            const students =
                Array.isArray(data.students)
                    ? data.students
                    : [];


            /*
            |--------------------------------------------------------------------------
            | No Students
            |--------------------------------------------------------------------------
            */

            if (students.length === 0) {

                showNoStudents();

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Display Students
            |--------------------------------------------------------------------------
            */

            displayStudents(
                students
            );

        } catch (error) {

            console.error(
                'Attendance student loading error:',
                error
            );


            showStudentError(
                error.message ||
                'Unable to load students. Please try again.'
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Load Sections
    |--------------------------------------------------------------------------
    */

    function loadSections(classId) {

        sectionSelect.innerHTML = `
            <option value="">
                Select Section
            </option>
        `;


        /*
        | Changing Class resets the Student list.
        */

        resetStudentArea();


        if (!classId) {

            sectionSelect.disabled =
                true;

            sectionHelp.textContent =
                'Sections will be loaded after selecting a class.';

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Find Sections
        |--------------------------------------------------------------------------
        */

        const classSections =
            sections.filter(
                function (section) {

                    return String(
                        section.class_id
                    ) === String(
                        classId
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | No Sections
        |--------------------------------------------------------------------------
        */

        if (classSections.length === 0) {

            sectionSelect.disabled =
                true;

            sectionSelect.innerHTML = `
                <option value="">
                    No Sections Available
                </option>
            `;

            sectionHelp.textContent =
                'There are no active sections for this class.';

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Populate Sections
        |--------------------------------------------------------------------------
        */

        classSections.forEach(
            function (section) {

                const option =
                    document.createElement('option');

                option.value =
                    section.id;

                option.textContent =
                    section.code
                        ? `${section.name} (${section.code})`
                        : section.name;

                sectionSelect.appendChild(
                    option
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Restore Previous Section
        |--------------------------------------------------------------------------
        */

        const oldSectionId =
            @json(old('section_id'));


        if (oldSectionId) {

            const matchingSection =
                classSections.find(
                    function (section) {

                        return String(
                            section.id
                        ) === String(
                            oldSectionId
                        );

                    }
                );


            if (matchingSection) {

                sectionSelect.value =
                    oldSectionId;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Enable Section
        |--------------------------------------------------------------------------
        */

        sectionSelect.disabled =
            false;

        sectionHelp.textContent =
            'Select the section for this attendance record.';


        /*
        |--------------------------------------------------------------------------
        | Automatically Load Students
        |--------------------------------------------------------------------------
        |
        | This happens when an old Section is restored after
        | validation.
        |
        */

        if (sectionSelect.value) {

            loadStudents();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Class Change
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener(
        'change',
        function () {

            loadSections(
                this.value
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Section Change
    |--------------------------------------------------------------------------
    */

    sectionSelect.addEventListener(
        'change',
        function () {

            loadStudents();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Status Change
    |--------------------------------------------------------------------------
    */

    studentsTableBody.addEventListener(
        'change',
        function (event) {

            if (
                event.target.classList.contains(
                    'attendance-status'
                )
            ) {

                validateAttendanceStatuses();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Restore Class
    |--------------------------------------------------------------------------
    */

    if (classSelect.value) {

        loadSections(
            classSelect.value
        );

    }

});

</script>

@endsection