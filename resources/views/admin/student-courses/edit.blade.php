@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
         PAGE HEADER
    ================================================================= --}}

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Edit Student Course Registration
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Academic
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.student-courses.index') }}">
                                Student Course Registration
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Registration
                        </li>

                    </ol>
                </div>

            </div>

        </div>
    </div>


    {{-- ================================================================
         VALIDATION ERRORS
    ================================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ================================================================
         SUCCESS MESSAGE
    ================================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="mdi mdi-check-circle-outline me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ================================================================
         ERROR MESSAGE
    ================================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="mdi mdi-alert-circle-outline me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ================================================================
         REGISTRATION SUMMARY
    ================================================================= --}}

    <div class="alert alert-info">

        <i class="mdi mdi-information-outline me-2"></i>

        You are editing registration
        <strong>#{{ $studentCourse->id }}</strong>.

        The student must have an active enrollment matching the selected
        academic year, term, class and section.

    </div>


    {{-- ================================================================
         MAIN CARD
    ================================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

                <div>

                    <h4 class="card-title mb-1">
                        Course Registration Details
                    </h4>

                    <p class="text-muted mb-0">
                        Update the student's academic course registration.
                    </p>

                </div>

                <div>

                    <a href="{{ route('admin.student-courses.show', $studentCourse) }}"
                       class="btn btn-light">

                        <i class="mdi mdi-eye-outline me-1"></i>

                        View Registration

                    </a>

                </div>

            </div>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.student-courses.update', $studentCourse) }}"
                  id="studentCourseEditForm">

                @csrf

                @method('PUT')


                {{-- ====================================================
                     ACADEMIC PLACEMENT
                ===================================================== --}}

                <div class="row g-3">


                    {{-- Academic Year --}}

                    <div class="col-xl-3 col-lg-6 col-md-6">

                        <label for="academic_year_id"
                               class="form-label">

                            Academic Year
                            <span class="text-danger">*</span>

                        </label>

                        <select name="academic_year_id"
                                id="academic_year_id"
                                class="form-select @error('academic_year_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Academic Year
                            </option>

                            @foreach($academicYears as $year)

                                <option value="{{ $year->id }}"
                                    {{ (string) old(
                                        'academic_year_id',
                                        $studentCourse->academic_year_id
                                    ) === (string) $year->id ? 'selected' : '' }}>

                                    {{ $year->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('academic_year_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Term --}}

                    <div class="col-xl-3 col-lg-6 col-md-6">

                        <label for="term_id"
                               class="form-label">

                            Term

                        </label>

                        <select name="term_id"
                                id="term_id"
                                class="form-select @error('term_id') is-invalid @enderror">

                            <option value="">
                                Select Term
                            </option>

                            @foreach($terms as $term)

                                <option value="{{ $term->id }}"
                                    {{ (string) old(
                                        'term_id',
                                        $studentCourse->term_id
                                    ) === (string) $term->id ? 'selected' : '' }}>

                                    {{ $term->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Terms are filtered by academic year.
                        </div>

                        @error('term_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Class --}}

                    <div class="col-xl-3 col-lg-6 col-md-6">

                        <label for="class_id"
                               class="form-label">

                            Class
                            <span class="text-danger">*</span>

                        </label>

                        <select name="class_id"
                                id="class_id"
                                class="form-select @error('class_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ (string) old(
                                        'class_id',
                                        $currentEnrollment?->class_id
                                    ) === (string) $class->id ? 'selected' : '' }}>

                                    {{ $class->name }}

                                    @if($class->code)
                                        — {{ $class->code }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('class_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Section --}}

                    <div class="col-xl-3 col-lg-6 col-md-6">

                        <label for="section_id"
                               class="form-label">

                            Section

                        </label>

                        <select name="section_id"
                                id="section_id"
                                class="form-select @error('section_id') is-invalid @enderror">

                            <option value="">
                                All / No Section
                            </option>

                            @foreach($sections as $section)

                                <option value="{{ $section->id }}"
                                    {{ (string) old(
                                        'section_id',
                                        $currentEnrollment?->section_id
                                    ) === (string) $section->id ? 'selected' : '' }}>

                                    {{ $section->name }}

                                    @if($section->code)
                                        — {{ $section->code }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('section_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         STUDENT
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="student_id"
                               class="form-label">

                            Student
                            <span class="text-danger">*</span>

                        </label>

                        <select name="student_id"
                                id="student_id"
                                class="form-select @error('student_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Student
                            </option>

                            @foreach($students as $student)

                                <option value="{{ $student->id }}"
                                    {{ (string) old(
                                        'student_id',
                                        $studentCourse->student_id
                                    ) === (string) $student->id ? 'selected' : '' }}>

                                    {{ $student->student_number }}
                                    —
                                    {{ $student->full_name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Only students with a matching active enrollment
                            will appear after the academic placement is selected.
                        </div>

                        @error('student_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         COURSE
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="course_id"
                               class="form-label">

                            Course
                            <span class="text-danger">*</span>

                        </label>

                        <select name="course_id"
                                id="course_id"
                                class="form-select @error('course_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Course
                            </option>

                            @foreach($courses as $course)

                                <option value="{{ $course->id }}"
                                    {{ (string) old(
                                        'course_id',
                                        $studentCourse->course_id
                                    ) === (string) $course->id ? 'selected' : '' }}>

                                    {{ $course->course_code }}
                                    —
                                    {{ $course->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('course_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         STATUS
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="status"
                               class="form-label">

                            Registration Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select w-100 @error('status') is-invalid @enderror"
                                style="width:100%; min-width:180px;"
                                required>

                            <option value="enrolled"
                                {{ old('status', $studentCourse->status) === 'enrolled'
                                    ? 'selected'
                                    : '' }}>

                                Enrolled

                            </option>

                            <option value="completed"
                                {{ old('status', $studentCourse->status) === 'completed'
                                    ? 'selected'
                                    : '' }}>

                                Completed

                            </option>

                            <option value="dropped"
                                {{ old('status', $studentCourse->status) === 'dropped'
                                    ? 'selected'
                                    : '' }}>

                                Dropped

                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ====================================================
                     ENROLLMENT FILTER SUMMARY
                ===================================================== --}}

                <div class="card border shadow-none mt-4 mb-0">

                    <div class="card-body">

                        <h5 class="font-size-15 mb-3">

                            <i class="mdi mdi-filter-outline me-1"></i>

                            Enrollment Filter

                        </h5>


                        <div class="row">

                            <div class="col-md-3">

                                <div class="text-muted small">
                                    Academic Year
                                </div>

                                <div id="summaryAcademicYear"
                                     class="fw-semibold">
                                    —
                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="text-muted small">
                                    Term
                                </div>

                                <div id="summaryTerm"
                                     class="fw-semibold">
                                    —
                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="text-muted small">
                                    Class
                                </div>

                                <div id="summaryClass"
                                     class="fw-semibold">
                                    —
                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="text-muted small">
                                    Section
                                </div>

                                <div id="summarySection"
                                     class="fw-semibold">
                                    —
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     WARNING
                ===================================================== --}}

                <div class="alert alert-warning mt-4 mb-0">

                    <i class="mdi mdi-alert-outline me-2"></i>

                    <strong>Important:</strong>

                    Changing the academic year, term, class, section,
                    or student may cause the registration to fail validation
                    if there is no matching active enrollment.

                </div>


                {{-- ====================================================
                     ACTIONS
                ===================================================== --}}

                <div class="mt-4 d-flex flex-wrap gap-2">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="mdi mdi-content-save-outline me-1"></i>

                        Update Registration

                    </button>


                    <a href="{{ route('admin.student-courses.show', $studentCourse) }}"
                       class="btn btn-light">

                        <i class="mdi mdi-close me-1"></i>

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


{{-- ================================================================
     JAVASCRIPT
================================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const academicYearSelect =
        document.getElementById('academic_year_id');

    const termSelect =
        document.getElementById('term_id');

    const classSelect =
        document.getElementById('class_id');

    const sectionSelect =
        document.getElementById('section_id');

    const studentSelect =
        document.getElementById('student_id');


    const summaryAcademicYear =
        document.getElementById('summaryAcademicYear');

    const summaryTerm =
        document.getElementById('summaryTerm');

    const summaryClass =
        document.getElementById('summaryClass');

    const summarySection =
        document.getElementById('summarySection');


    /*
    |--------------------------------------------------------------------------
    | URL Templates
    |--------------------------------------------------------------------------
    */

    const termsUrlTemplate =
        @json(route(
            'admin.student-courses.data.terms',
            ['academicYear' => '__ID__']
        ));


    const classesUrlTemplate =
        @json(route(
            'admin.student-courses.data.classes',
            ['academicYear' => '__ID__']
        ));


    const sectionsUrlTemplate =
        @json(route(
            'admin.student-courses.data.sections',
            ['class' => '__ID__']
        ));


    const studentsUrl =
        @json(route(
            'admin.student-courses.data.students'
        ));


    /*
    |--------------------------------------------------------------------------
    | Initial Values
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Use $currentEnrollment, not $enrollment.
    |
    */

    const initialAcademicYear =
        @json(old(
            'academic_year_id',
            $studentCourse->academic_year_id
        ));


    const initialTerm =
        @json(old(
            'term_id',
            $studentCourse->term_id
        ));


    const initialClass =
        @json(old(
            'class_id',
            $currentEnrollment?->class_id
        ));


    const initialSection =
        @json(old(
            'section_id',
            $currentEnrollment?->section_id
        ));


    const initialStudent =
        @json(old(
            'student_id',
            $studentCourse->student_id
        ));


    /*
    |--------------------------------------------------------------------------
    | Utility Functions
    |--------------------------------------------------------------------------
    */

    function resetSelect(select, placeholder)
    {
        select.innerHTML =
            '<option value="">' +
            placeholder +
            '</option>';
    }


    function setLoading(select, text)
    {
        select.innerHTML =
            '<option value="">' +
            text +
            '</option>';
    }


    /*
    |--------------------------------------------------------------------------
    | Update Enrollment Summary
    |--------------------------------------------------------------------------
    */

    function updateSummary()
    {
        summaryAcademicYear.textContent =
            academicYearSelect.options[
                academicYearSelect.selectedIndex
            ]?.text || '—';


        summaryTerm.textContent =
            termSelect.options[
                termSelect.selectedIndex
            ]?.text || '—';


        summaryClass.textContent =
            classSelect.options[
                classSelect.selectedIndex
            ]?.text || '—';


        summarySection.textContent =
            sectionSelect.options[
                sectionSelect.selectedIndex
            ]?.text || '—';
    }


    /*
    |--------------------------------------------------------------------------
    | Load Terms
    |--------------------------------------------------------------------------
    */

    async function loadTerms(
        academicYearId,
        selectedTermId = null
    ) {

        if (!academicYearId) {

            resetSelect(
                termSelect,
                'Select Term'
            );

            updateSummary();

            return;
        }


        setLoading(
            termSelect,
            'Loading terms...'
        );


        try {

            const url =
                termsUrlTemplate.replace(
                    '__ID__',
                    academicYearId
                );


            const response =
                await fetch(
                    url,
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );


            if (!response.ok) {
                throw new Error(
                    'Unable to load terms.'
                );
            }


            const terms =
                await response.json();


            resetSelect(
                termSelect,
                'Select Term'
            );


            terms.forEach(function (term) {

                const option =
                    document.createElement('option');


                option.value =
                    term.id;


                option.textContent =
                    term.name;


                if (
                    selectedTermId !== null &&
                    String(term.id) ===
                    String(selectedTermId)
                ) {

                    option.selected = true;

                }


                termSelect.appendChild(option);

            });


        } catch (error) {

            resetSelect(
                termSelect,
                'Unable to load terms'
            );

            console.error(error);

        }


        updateSummary();
    }


    /*
    |--------------------------------------------------------------------------
    | Load Classes
    |--------------------------------------------------------------------------
    */

    async function loadClasses(
        academicYearId,
        selectedClassId = null
    ) {

        if (!academicYearId) {

            resetSelect(
                classSelect,
                'Select Class'
            );

            updateSummary();

            return;
        }


        setLoading(
            classSelect,
            'Loading classes...'
        );


        try {

            const url =
                classesUrlTemplate.replace(
                    '__ID__',
                    academicYearId
                );


            const response =
                await fetch(
                    url,
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );


            if (!response.ok) {
                throw new Error(
                    'Unable to load classes.'
                );
            }


            const classes =
                await response.json();


            resetSelect(
                classSelect,
                'Select Class'
            );


            classes.forEach(function (item) {

                const option =
                    document.createElement('option');


                option.value =
                    item.id;


                option.textContent =
                    item.code
                        ? item.name + ' — ' + item.code
                        : item.name;


                if (
                    selectedClassId !== null &&
                    String(item.id) ===
                    String(selectedClassId)
                ) {

                    option.selected = true;

                }


                classSelect.appendChild(option);

            });


        } catch (error) {

            resetSelect(
                classSelect,
                'Unable to load classes'
            );

            console.error(error);

        }


        updateSummary();
    }


    /*
    |--------------------------------------------------------------------------
    | Load Sections
    |--------------------------------------------------------------------------
    */

    async function loadSections(
        classId,
        selectedSectionId = null
    ) {

        resetSelect(
            sectionSelect,
            'All / No Section'
        );


        if (!classId) {

            updateSummary();

            return;
        }


        setLoading(
            sectionSelect,
            'Loading sections...'
        );


        try {

            const url =
                sectionsUrlTemplate.replace(
                    '__ID__',
                    classId
                );


            const response =
                await fetch(
                    url,
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );


            if (!response.ok) {
                throw new Error(
                    'Unable to load sections.'
                );
            }


            const sections =
                await response.json();


            resetSelect(
                sectionSelect,
                'All / No Section'
            );


            sections.forEach(function (section) {

                const option =
                    document.createElement('option');


                option.value =
                    section.id;


                option.textContent =
                    section.code
                        ? section.name + ' — ' + section.code
                        : section.name;


                if (
                    selectedSectionId !== null &&
                    String(section.id) ===
                    String(selectedSectionId)
                ) {

                    option.selected = true;

                }


                sectionSelect.appendChild(option);

            });


        } catch (error) {

            resetSelect(
                sectionSelect,
                'Unable to load sections'
            );

            console.error(error);

        }


        updateSummary();
    }


    /*
    |--------------------------------------------------------------------------
    | Load Students
    |--------------------------------------------------------------------------
    */

    async function loadStudents(
        selectedStudentId = null
    ) {

        const academicYearId =
            academicYearSelect.value;


        const termId =
            termSelect.value;


        const classId =
            classSelect.value;


        const sectionId =
            sectionSelect.value;


        /*
        | Student list requires academic year and class.
        */

        if (
            !academicYearId ||
            !classId
        ) {

            resetSelect(
                studentSelect,
                'Select Academic Year and Class first'
            );

            return;
        }


        setLoading(
            studentSelect,
            'Loading students...'
        );


        const params =
            new URLSearchParams();


        params.append(
            'academic_year_id',
            academicYearId
        );


        params.append(
            'class_id',
            classId
        );


        if (termId) {

            params.append(
                'term_id',
                termId
            );

        }


        if (sectionId) {

            params.append(
                'section_id',
                sectionId
            );

        }


        try {

            const response =
                await fetch(
                    studentsUrl +
                    '?' +
                    params.toString(),
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to load students.'
                );

            }


            const students =
                await response.json();


            resetSelect(
                studentSelect,
                students.length
                    ? 'Select Student'
                    : 'No matching students found'
            );


            students.forEach(function (student) {

                const option =
                    document.createElement('option');


                option.value =
                    student.id;


                option.textContent =
                    student.student_number +
                    ' — ' +
                    student.name;


                if (
                    selectedStudentId !== null &&
                    String(student.id) ===
                    String(selectedStudentId)
                ) {

                    option.selected = true;

                }


                studentSelect.appendChild(option);

            });


        } catch (error) {

            resetSelect(
                studentSelect,
                'Unable to load students'
            );

            console.error(error);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Academic Year Changed
    |--------------------------------------------------------------------------
    */

    academicYearSelect.addEventListener(
        'change',
        async function () {

            resetSelect(
                termSelect,
                'Select Term'
            );


            resetSelect(
                classSelect,
                'Select Class'
            );


            resetSelect(
                sectionSelect,
                'All / No Section'
            );


            resetSelect(
                studentSelect,
                'Select Academic Year, Term and Class first'
            );


            if (!this.value) {

                updateSummary();

                return;
            }


            await Promise.all([

                loadTerms(this.value),

                loadClasses(this.value)

            ]);


            updateSummary();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Class Changed
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener(
        'change',
        async function () {

            resetSelect(
                sectionSelect,
                'All / No Section'
            );


            resetSelect(
                studentSelect,
                'Select Section first'
            );


            if (!this.value) {

                updateSummary();

                return;
            }


            await loadSections(
                this.value
            );


            updateSummary();


            /*
            | If the class has no sections, students can still be loaded
            | using the class alone.
            */

            if (!sectionSelect.options.length ||
                sectionSelect.value === '') {

                await loadStudents();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Term Changed
    |--------------------------------------------------------------------------
    */

    termSelect.addEventListener(
        'change',
        function () {

            updateSummary();


            if (
                academicYearSelect.value &&
                classSelect.value
            ) {

                loadStudents();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Section Changed
    |--------------------------------------------------------------------------
    */

    sectionSelect.addEventListener(
        'change',
        function () {

            updateSummary();

            loadStudents();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Edit Form Load
    |--------------------------------------------------------------------------
    */

    async function initializeEditForm()
    {

        if (!initialAcademicYear) {

            updateSummary();

            return;
        }


        /*
        | Load academic-year dependent data.
        */

        await Promise.all([

            loadTerms(
                initialAcademicYear,
                initialTerm
            ),

            loadClasses(
                initialAcademicYear,
                initialClass
            )

        ]);


        /*
        | Load sections for the selected class.
        */

        if (initialClass) {

            await loadSections(
                initialClass,
                initialSection
            );

        }


        /*
        | Load students matching the current placement.
        */

        if (initialClass) {

            await loadStudents(
                initialStudent
            );

        }


        updateSummary();

    }


    /*
    |--------------------------------------------------------------------------
    | Start
    |--------------------------------------------------------------------------
    */

    initializeEditForm();

});

</script>