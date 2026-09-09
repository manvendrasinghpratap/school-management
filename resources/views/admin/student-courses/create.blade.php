@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Register Student Course
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
                            Register Student
                        </li>

                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
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

    {{-- Information --}}
    <div class="alert alert-info">

        <i class="mdi mdi-information-outline me-2"></i>

        Select the academic year, term, class and section first.
        The student list will then be filtered to students who have
        an active enrollment matching those selections.

    </div>

    {{-- Main Card --}}
    <div class="card">

        <div class="card-header">

            <h4 class="card-title mb-1">
                Course Registration Details
            </h4>

            <p class="text-muted mb-0">
                Register an enrolled student for an academic course.
            </p>

        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.student-courses.store') }}"
                  id="studentCourseForm">

                @csrf

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
                                    {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>

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
                                class="form-select @error('term_id') is-invalid @enderror"
                                disabled>

                            <option value="">
                                Select Term
                            </option>

                        </select>

                        <div class="form-text">
                            Select an academic year first.
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
                                disabled
                                required>

                            <option value="">
                                Select Class
                            </option>

                        </select>

                        <div class="form-text">
                            Select an academic year first.
                        </div>

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
                                class="form-select @error('section_id') is-invalid @enderror"
                                disabled>

                            <option value="">
                                All / No Section
                            </option>

                        </select>

                        <div class="form-text">
                            Select a class first.
                        </div>

                        @error('section_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Student --}}
                    <div class="col-md-6">

                        <label for="student_id"
                               class="form-label">

                            Student
                            <span class="text-danger">*</span>

                        </label>

                        <select name="student_id"
                                id="student_id"
                                class="form-select @error('student_id') is-invalid @enderror"
                                disabled
                                required>

                            <option value="">
                                Select Academic Year, Term and Class first
                            </option>

                        </select>

                        <div class="form-text">
                            Only students with a matching active enrollment
                            will appear here.
                        </div>

                        @error('student_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Course --}}
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
                                    {{ old('course_id') == $course->id ? 'selected' : '' }}>

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


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label for="status"
                               class="form-label">

                            Registration Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status" 
                                style="width: 100%; min-width: 180px;"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="enrolled"
                                {{ old('status', 'enrolled') === 'enrolled' ? 'selected' : '' }}>
                                Enrolled
                            </option>

                            <option value="completed"
                                {{ old('status') === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="dropped"
                                {{ old('status') === 'dropped' ? 'selected' : '' }}>
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


                {{-- Selected Criteria Summary --}}
                <div class="row mt-4">

                    <div class="col-12">

                        <div class="card border shadow-none mb-0">

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

                    </div>

                </div>


                {{-- Rule --}}
                <div class="row mt-4">

                    <div class="col-12">

                        <div class="alert alert-warning mb-0">

                            <i class="mdi mdi-alert-outline me-2"></i>

                            <strong>Important:</strong>

                            A student must have an active enrollment matching
                            the selected academic year, term, class and section.
                            The system will perform the same validation again
                            when the form is submitted.

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="row mt-4">

                    <div class="col-12 d-flex flex-wrap gap-2">

                        <button type="submit"
                                class="btn btn-primary"
                                id="registerButton">

                            <i class="mdi mdi-content-save-outline me-1"></i>

                            Register Course

                        </button>

                        <a href="{{ route('admin.student-courses.index') }}"
                           class="btn btn-light">

                            <i class="mdi mdi-arrow-left me-1"></i>

                            Cancel

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection



<script>
document.addEventListener('DOMContentLoaded', function () {

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


    const termsUrlTemplate =
        @json(route('admin.student-courses.data.terms', ['academicYear' => '__ID__']));

    const classesUrlTemplate =
        @json(route('admin.student-courses.data.classes', ['academicYear' => '__ID__']));

    const sectionsUrlTemplate =
        @json(route('admin.student-courses.data.sections', ['class' => '__ID__']));

    const studentsUrl =
        @json(route('admin.student-courses.data.students'));


    function resetSelect(select, placeholder) {

        select.innerHTML =
            '<option value="">' +
            placeholder +
            '</option>';

    }


    function setLoading(select, text) {

        select.innerHTML =
            '<option value="">' +
            text +
            '</option>';

    }


    function updateSummary() {

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


    function resetFromTerm() {

        resetSelect(
            termSelect,
            'Select Term'
        );

        termSelect.disabled = true;

        resetFromClass();

        updateSummary();
    }


    function resetFromClass() {

        resetSelect(
            classSelect,
            'Select Class'
        );

        classSelect.disabled = true;

        resetFromSection();

    }


    function resetFromSection() {

        resetSelect(
            sectionSelect,
            'All / No Section'
        );

        sectionSelect.disabled = true;

        resetFromStudent();

    }


    function resetFromStudent() {

        resetSelect(
            studentSelect,
            'Select Academic Year, Term and Class first'
        );

        studentSelect.disabled = true;

    }


    async function loadTerms(academicYearId) {

        resetFromTerm();

        if (!academicYearId) {
            return;
        }

        setLoading(
            termSelect,
            'Loading terms...'
        );

        termSelect.disabled = true;

        try {

            const url =
                termsUrlTemplate.replace(
                    '__ID__',
                    academicYearId
                );

            const response =
                await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

            if (!response.ok) {
                throw new Error('Unable to load terms.');
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

                option.value = term.id;
                option.textContent = term.name;

                termSelect.appendChild(option);

            });

            termSelect.disabled = false;

        } catch (error) {

            resetSelect(
                termSelect,
                'Unable to load terms'
            );

            console.error(error);

        }

        updateSummary();
    }


    async function loadClasses(academicYearId) {

        resetFromClass();

        if (!academicYearId) {
            return;
        }

        setLoading(
            classSelect,
            'Loading classes...'
        );

        classSelect.disabled = true;

        try {

            const url =
                classesUrlTemplate.replace(
                    '__ID__',
                    academicYearId
                );

            const response =
                await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

            if (!response.ok) {
                throw new Error('Unable to load classes.');
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

                option.value = item.id;

                option.textContent =
                    item.code
                        ? item.name + ' — ' + item.code
                        : item.name;

                classSelect.appendChild(option);

            });

            classSelect.disabled = false;

        } catch (error) {

            resetSelect(
                classSelect,
                'Unable to load classes'
            );

            console.error(error);

        }

        updateSummary();
    }


    async function loadSections(classId) {

        resetFromSection();

        if (!classId) {
            return;
        }

        setLoading(
            sectionSelect,
            'Loading sections...'
        );

        sectionSelect.disabled = true;

        try {

            const url =
                sectionsUrlTemplate.replace(
                    '__ID__',
                    classId
                );

            const response =
                await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

            if (!response.ok) {
                throw new Error('Unable to load sections.');
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

                option.value = section.id;

                option.textContent =
                    section.code
                        ? section.name + ' — ' + section.code
                        : section.name;

                sectionSelect.appendChild(option);

            });

            sectionSelect.disabled = false;

        } catch (error) {

            resetSelect(
                sectionSelect,
                'Unable to load sections'
            );

            console.error(error);

        }

        updateSummary();
    }


    async function loadStudents() {

        resetFromStudent();

        const academicYearId =
            academicYearSelect.value;

        const termId =
            termSelect.value;

        const classId =
            classSelect.value;

        const sectionId =
            sectionSelect.value;


        if (
            !academicYearId ||
            !classId
        ) {
            return;
        }


        setLoading(
            studentSelect,
            'Loading students...'
        );

        studentSelect.disabled = true;


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
                    studentsUrl + '?' + params.toString(),
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

                option.value = student.id;

                option.textContent =
                    student.student_number +
                    ' — ' +
                    student.name;

                studentSelect.appendChild(option);

            });


            studentSelect.disabled =
                students.length === 0;


        } catch (error) {

            resetSelect(
                studentSelect,
                'Unable to load students'
            );

            console.error(error);

        }

    }


    academicYearSelect.addEventListener(
        'change',
        async function () {

            resetFromTerm();

            updateSummary();

            if (!this.value) {
                return;
            }

            await Promise.all([
                loadTerms(this.value),
                loadClasses(this.value)
            ]);

        }
    );


    termSelect.addEventListener(
        'change',
        function () {

            resetFromSection();

            updateSummary();

            if (
                academicYearSelect.value &&
                classSelect.value
            ) {

                loadStudents();

            }

        }
    );


    classSelect.addEventListener(
        'change',
        async function () {

            resetFromSection();

            updateSummary();

            if (!this.value) {
                return;
            }

            await loadSections(this.value);

        }
    );


    sectionSelect.addEventListener(
        'change',
        function () {

            updateSummary();

            loadStudents();

        }
    );


    updateSummary();

});
</script>
