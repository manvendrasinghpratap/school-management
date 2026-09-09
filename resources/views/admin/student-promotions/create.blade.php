@extends('backend.layout.default')

@section('title', 'Create Student Promotion')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        PAGE HEADER
    ============================================================ --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Create Student Promotion
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.student-promotions.index') }}">
                                Student Promotions
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create Promotion
                        </li>

                    </ol>
                </div>

            </div>

        </div>
    </div>


    {{-- ============================================================
        VALIDATION ERRORS
    ============================================================ --}}
    @if ($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
        SESSION ERROR
    ============================================================ --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(!$currentAcademicYear)
        <div class="alert alert-warning">
            <i class="bx bx-error-circle me-1"></i>
            No current active academic year is configured.
        </div>
    @elseif(!$nextAcademicYear)
        <div class="alert alert-warning">
            <i class="bx bx-error-circle me-1"></i>
            No next active academic year is configured. A promotion cannot be created yet.
        </div>
    @endif

    {{-- ============================================================
        MAIN LAYOUT
    ============================================================ --}}
    <div class="row">

        {{-- ========================================================
            LEFT COLUMN
        ========================================================= --}}
        <div class="col-xl-8">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>
                            <h4 class="card-title mb-1">
                                Student Promotion
                            </h4>

                            <p class="card-title-desc mb-0">
                                Select the student's current academic placement,
                                then choose the destination class and section.
                            </p>
                        </div>

                        <div>
                            <a href="{{ route('admin.student-promotions.index') }}"
                               class="btn btn-light">

                                <i class="bx bx-arrow-back me-1"></i>
                                Back

                            </a>
                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <form
                        action="{{ route('admin.student-promotions.store') }}"
                        method="POST"
                        id="promotionForm"
                    >
@csrf


                        {{-- ==================================================
                            CURRENT PLACEMENT FILTER
                        =================================================== --}}
                        <div class="card border mb-4">

                            <div class="card-header bg-transparent">

                                <h4 class="card-title mb-1">
                                    Current Student Placement
                                </h4>

                                <p class="text-muted mb-0">
                                    Select the academic year, current class and
                                    section to find students currently enrolled
                                    in that placement.
                                </p>

                            </div>


                            <div class="card-body">

                                <div class="row">

                                    {{-- Academic Year --}}
                                    <div class="col-md-4">

                                        <div class="mb-3">

                                            <label for="filter_academic_year_id"
                                                   class="form-label">

                                                Academic Year

                                                <span class="text-danger">*</span>

                                            </label>

                                            <select
                                                name="academic_year_id"
                                                id="filter_academic_year_id"
                                                class="form-select @error('academic_year_id') is-invalid @enderror"
                                                required
                                            >

                                                <option value="">
                                                    Select Academic Year
                                                </option>

                                                @foreach($academicYears as $year)

                                                    <option
                                                        value="{{ $year->id }}"
                                                        {{ (int) old('academic_year_id', optional($currentAcademicYear)->id) === (int) $year->id ? 'selected' : '' }}
                                                    >

                                                        {{ $year->name }}

                                                        @if($year->is_current)
                                                            — Current
                                                        @endif

                                                    </option>

                                                @endforeach

                                            </select>

                                            @error('academic_year_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>

                                    </div>


                                    {{-- Current Class --}}
                                    <div class="col-md-4">

                                        <div class="mb-3">

                                            <label for="filter_class_id"
                                                   class="form-label">

                                                Current Class

                                                <span class="text-danger">*</span>

                                            </label>

                                            <select
                                                id="filter_class_id"
                                                class="form-select"
                                                required
                                                disabled
                                            >

                                                <option value="">
                                                    Select Current Class
                                                </option>

                                                @foreach($classes as $class)

                                                    <option
                                                        value="{{ $class->id }}"
                                                    >
                                                        {{ $class->name }}

                                                        @if($class->level)
                                                            — {{ $class->level->name }}
                                                        @endif

                                                    </option>

                                                @endforeach

                                            </select>

                                        </div>

                                    </div>


                                    {{-- Current Section --}}
                                    <div class="col-md-4">

                                        <div class="mb-3">

                                            <label for="filter_section_id"
                                                   class="form-label">

                                                Current Section

                                                <span class="text-danger">*</span>

                                            </label>

                                            <select
                                                id="filter_section_id"
                                                class="form-select"
                                                required
                                                disabled
                                            >

                                                <option value="">
                                                    Select Current Section
                                                </option>

                                            </select>

                                        </div>

                                    </div>

                                </div>


                                {{-- Filter Help --}}
                                <div class="alert alert-info mb-0">

                                    <div class="d-flex">

                                        <i class="bx bx-info-circle font-size-18 me-2"></i>

                                        <div>
                                            <strong>How student selection works</strong>

                                            <p class="mb-0 mt-1">
                                                Only active students with an active
                                                enrollment in the selected academic
                                                year, class and section will appear
                                                below.
                                            </p>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ==================================================
                            STUDENT
                        =================================================== --}}
                        <div class="mb-4">

                            <label for="student_id"
                                   class="form-label">

                                Student

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="student_id"
                                id="student_id"
                                class="form-select @error('student_id') is-invalid @enderror"
                                required
                                disabled
                            >

                                <option value="">
                                    Select Academic Year, Class and Section First
                                </option>

                            </select>

                            @error('student_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div id="studentLoading"
                                 class="form-text d-none">

                                <i class="bx bx-loader-alt bx-spin me-1"></i>
                                Loading students...

                            </div>

                            <div id="studentEmpty"
                                 class="alert alert-warning mt-2 d-none">

                                <i class="bx bx-info-circle me-1"></i>

                                No active students were found for the selected
                                academic placement.

                            </div>

                        </div>


                        {{-- ==================================================
                            CURRENT ENROLLMENT
                        =================================================== --}}
                        <div class="card border mb-4">

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    <div class="avatar-md me-4">

                                        <div class="avatar-title rounded-circle bg-primary text-white font-size-24">

                                            <i class="bx bx-user-check"></i>

                                        </div>

                                    </div>


                                    <div class="flex-grow-1">

                                        <h4 class="mb-4">
                                            Current Enrollment
                                        </h4>

                                        <div class="row">

                                            {{-- Academic Year --}}
                                            <div class="col-md-4">

                                                <label class="text-muted d-block mb-1">
                                                    Academic Year
                                                </label>

                                                <h5 id="currentAcademicYear"
                                                    class="mb-0">
                                                    —
                                                </h5>

                                            </div>


                                            {{-- Class --}}
                                            <div class="col-md-4">

                                                <label class="text-muted d-block mb-1">
                                                    Current Class
                                                </label>

                                                <h5 id="currentClass"
                                                    class="mb-0">
                                                    —
                                                </h5>

                                            </div>


                                            {{-- Section --}}
                                            <div class="col-md-4">

                                                <label class="text-muted d-block mb-1">
                                                    Current Section
                                                </label>

                                                <h5 id="currentSection"
                                                    class="mb-0">
                                                    —
                                                </h5>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ==================================================
                            HIDDEN CURRENT VALUES
                            These are informational/form values only.
                            The service re-verifies the actual enrollment.
                        =================================================== --}}
                        <input type="hidden"
                               name="from_class_id"
                               id="from_class_id"
                               value="">

                        <input type="hidden"
                               name="from_section_id"
                               id="from_section_id"
                               value="">


                        {{-- ==================================================
                            DESTINATION
                        =================================================== --}}
                        <div class="card bg-light border mb-4">

                            <div class="card-header bg-transparent">

                                <h4 class="card-title mb-1">
                                    Destination
                                </h4>

                                <p class="text-muted mb-0">
                                    The destination must be the immediate next
                                    class in the student's academic progression.
                                </p>

                            </div>


                            <div class="card-body">

                                <div class="row">

                                    {{-- Destination Academic Year --}}


                                    <div class="col-12">


                                        <div class="mb-3">


                                            <label class="form-label">Destination Academic Year</label>


                                            <div class="form-control bg-light">


                                                @if($nextAcademicYear)


                                                    <span class="fw-semibold">{{ $nextAcademicYear->name }}</span>


                                                    <span class="badge bg-info-subtle text-info ms-2">Automatic Next Year</span>


                                                @else


                                                    <span class="text-danger">No next active academic year is configured.</span>


                                                @endif


                                            </div>


                                            <div class="form-text">


                                                Calculated automatically from the current academic year; it cannot be selected manually.


                                            </div>


                                        </div>


                                    </div>



                                    {{-- Destination Class --}}
                                    <div class="col-md-6">

                                        <div class="mb-3">

                                            <label for="to_class_id"
                                                   class="form-label">

                                                Destination Class

                                                <span class="text-danger">*</span>

                                            </label>

                                            <select
                                                name="to_class_id"
                                                id="to_class_id"
                                                class="form-select @error('to_class_id') is-invalid @enderror"
                                                required
                                                disabled
                                            >

                                                <option value="">
                                                    Select Student First
                                                </option>

                                                @foreach($classes as $class)

                                                    <option
                                                        value="{{ $class->id }}"
                                                        data-level-id="{{ $class->level_id }}"
                                                        data-class-name="{{ $class->name }}"
                                                    >
                                                        {{ $class->name }}

                                                        @if($class->level)
                                                            — {{ $class->level->name }}
                                                        @endif

                                                    </option>

                                                @endforeach

                                            </select>

                                            @error('to_class_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>

                                    </div>


                                    {{-- Destination Section --}}
                                    <div class="col-md-6">

                                        <div class="mb-3">

                                            <label for="to_section_id"
                                                   class="form-label">

                                                Destination Section

                                                <span class="text-danger">*</span>

                                            </label>

                                            <select
                                                name="to_section_id"
                                                id="to_section_id"
                                                class="form-select @error('to_section_id') is-invalid @enderror"
                                                required
                                                disabled
                                            >

                                                <option value="">
                                                    Select Destination Class First
                                                </option>

                                            </select>

                                            @error('to_section_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                            <div class="form-text">
                                                Only active sections belonging to
                                                the selected destination class
                                                will be displayed.
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ==================================================
                            PROMOTION DETAILS
                        =================================================== --}}
                        <div class="card border mb-4">

                            <div class="card-header bg-transparent">

                                <h4 class="card-title mb-0">
                                    Promotion Details
                                </h4>

                            </div>


                            <div class="card-body">

                                <div class="row">

                                    {{-- Promotion Date --}}
                                    <div class="col-md-6">

                                        <div class="mb-3">

                                            <label for="promotion_date"
                                                   class="form-label">

                                                Promotion Date

                                                <span class="text-danger">*</span>

                                            </label>

                                            <input
                                                type="date"
                                                name="promotion_date"
                                                id="promotion_date"
                                                class="form-control @error('promotion_date') is-invalid @enderror"
                                                value="{{ old('promotion_date', now()->format('Y-m-d')) }}"
                                                required
                                            >

                                            @error('promotion_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>

                                    </div>


                                    {{-- Initial Status --}}
                                    <div class="col-md-6">

                                        <div class="mb-3">

                                            <label class="form-label">
                                                Initial Status
                                            </label>

                                            <div class="form-control bg-light">

                                                <span class="badge bg-warning-subtle text-warning">
                                                    Pending
                                                </span>

                                                <span class="text-muted ms-2">
                                                    Awaiting approval
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- Remarks --}}
                                    <div class="col-12">

                                        <div class="mb-3">

                                            <label for="remarks"
                                                   class="form-label">

                                                Remarks

                                                <span class="text-muted">
                                                    (Optional)
                                                </span>

                                            </label>

                                            <textarea
                                                name="remarks"
                                                id="remarks"
                                                rows="4"
                                                maxlength="5000"
                                                class="form-control @error('remarks') is-invalid @enderror"
                                                placeholder="Enter any remarks or notes about this promotion..."
                                            >{{ old('remarks') }}</textarea>

                                            @error('remarks')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ==================================================
                            FORM ACTIONS
                        =================================================== --}}
                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.student-promotions.index') }}"
                               class="btn btn-light">

                                <i class="bx bx-x me-1"></i>
                                Cancel

                            </a>

                            <button
                                type="submit"
                                id="submitPromotion"
                                class="btn btn-primary"
                                disabled
                            >

                                <i class="bx bx-save me-1"></i>
                                Create Promotion

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- ========================================================
            RIGHT COLUMN
        ========================================================= --}}
        <div class="col-xl-4">

            {{-- Workflow --}}
            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">
                        Promotion Workflow
                    </h4>

                </div>


                <div class="card-body">

                    <div class="d-flex mb-4">

                        <div class="avatar-sm me-3">

                            <div class="avatar-title rounded-circle bg-primary text-white">
                                1
                            </div>

                        </div>

                        <div>

                            <h5 class="font-size-14 mb-1">
                                Select Current Placement
                            </h5>

                            <p class="text-muted mb-0">
                                Choose the academic year, current class and
                                section.
                            </p>

                        </div>

                    </div>


                    <div class="d-flex mb-4">

                        <div class="avatar-sm me-3">

                            <div class="avatar-title rounded-circle bg-primary text-white">
                                2
                            </div>

                        </div>

                        <div>

                            <h5 class="font-size-14 mb-1">
                                Select Student
                            </h5>

                            <p class="text-muted mb-0">
                                Only actively enrolled students in that
                                placement are shown.
                            </p>

                        </div>

                    </div>


                    <div class="d-flex mb-4">

                        <div class="avatar-sm me-3">

                            <div class="avatar-title rounded-circle bg-primary text-white">
                                3
                            </div>

                        </div>

                        <div>

                            <h5 class="font-size-14 mb-1">
                                Select Destination
                            </h5>

                            <p class="text-muted mb-0">
                                Choose the student's immediate next class and
                                destination section.
                            </p>

                        </div>

                    </div>


                    <div class="d-flex">

                        <div class="avatar-sm me-3">

                            <div class="avatar-title rounded-circle bg-warning text-white">
                                4
                            </div>

                        </div>

                        <div>

                            <h5 class="font-size-14 mb-1">
                                Submit for Approval
                            </h5>

                            <p class="text-muted mb-0">
                                The request starts as
                                <strong>Pending</strong>.
                                Approval changes the enrollment.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Important --}}
            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">
                        Important
                    </h4>

                </div>

                <div class="card-body">

                    <div class="alert alert-info mb-0">

                        <div class="d-flex">

                            <i class="bx bx-info-circle font-size-18 me-2"></i>

                            <div>

                                <strong>
                                    Current enrollment is protected.
                                </strong>

                                <p class="mb-0 mt-1">
                                    Creating a promotion does not change the
                                    student's enrollment. The existing
                                    enrollment remains active until the
                                    promotion is approved.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Status --}}
            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm me-3">

                            <div class="avatar-title rounded-circle bg-warning-subtle text-warning">

                                <i class="bx bx-time-five font-size-20"></i>

                            </div>

                        </div>

                        <div>

                            <h5 class="mb-1">
                                Initial Status
                            </h5>

                            <span class="badge bg-warning-subtle text-warning">
                                Pending
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================================================================
     JAVASCRIPT
================================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const academicYearSelect =
        document.getElementById('filter_academic_year_id');

    const classSelect =
        document.getElementById('filter_class_id');

    const sectionSelect =
        document.getElementById('filter_section_id');

    const studentSelect =
        document.getElementById('student_id');
const fromClassInput =
        document.getElementById('from_class_id');

    const fromSectionInput =
        document.getElementById('from_section_id');

    const currentAcademicYear =
        document.getElementById('currentAcademicYear');

    const currentClass =
        document.getElementById('currentClass');

    const currentSection =
        document.getElementById('currentSection');

    const destinationClass =
        document.getElementById('to_class_id');

    const destinationSection =
        document.getElementById('to_section_id');

    const submitButton =
        document.getElementById('submitPromotion');

    const studentLoading =
        document.getElementById('studentLoading');

    const studentEmpty =
        document.getElementById('studentEmpty');

    const promotionForm =
        document.getElementById('promotionForm');

    const promotionDate =
        document.getElementById('promotion_date');

    const filterStudentsUrl =
        @json(route('admin.student-promotions.filter-students'));

    const filterSectionsUrl =
        @json(route('admin.student-promotions.filter-sections'));


    /*
    |--------------------------------------------------------------------------
    | Reset Current Enrollment Display
    |--------------------------------------------------------------------------
    */

    function resetCurrentEnrollment() {

        currentAcademicYear.textContent = '—';
        currentClass.textContent = '—';
        currentSection.textContent = '—';

        fromClassInput.value = '';
        fromSectionInput.value = '';

        if (destinationClass) {
            destinationClass.value = '';
            destinationClass.disabled = true;
        }

        if (destinationSection) {
            destinationSection.innerHTML =
                '<option value="">Select Destination Class First</option>';

            destinationSection.value = '';
            destinationSection.disabled = true;
        }

        updateSubmitButton();
    }


    /*
    |--------------------------------------------------------------------------
    | Reset Students
    |--------------------------------------------------------------------------
    */

    function resetStudents(message) {

        studentSelect.innerHTML =
            '<option value="">' +
            message +
            '</option>';

        studentSelect.disabled = true;

        if (studentEmpty) {
            studentEmpty.classList.add('d-none');
        }

        updateSubmitButton();
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Form State
    |--------------------------------------------------------------------------
    */

    function updateSubmitButton() {

        if (!submitButton) {
            return;
        }

        const academicYear =
            academicYearSelect.value;

        const fromClass =
            fromClassInput.value;

        const fromSection =
            fromSectionInput.value;

        const student =
            studentSelect.value;

        const toClass =
            destinationClass.value;

        const toSection =
            destinationSection.value;

        const date =
            promotionDate ? promotionDate.value : '';

        const ready =
            academicYear !== '' &&
            fromClass !== '' &&
            fromSection !== '' &&
            student !== '' &&
            toClass !== '' &&
            toSection !== '' &&
            date !== '';

        submitButton.disabled = !ready;
    }


    /*
    |--------------------------------------------------------------------------
    | Load Current Sections
    |--------------------------------------------------------------------------
    */

    async function loadSections() {

        const classId =
            classSelect.value;

        resetStudents(
            'Select Current Section First'
        );

        resetCurrentEnrollment();

        sectionSelect.innerHTML =
            '<option value="">Loading Sections...</option>';

        sectionSelect.disabled = true;

        if (!classId) {

            sectionSelect.innerHTML =
                '<option value="">Select Current Section</option>';

            return;
        }

        try {

            const url =
                filterSectionsUrl +
                '?class_id=' +
                encodeURIComponent(classId);

            const response =
                await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

            if (!response.ok) {
                throw new Error(
                    'Unable to load sections.'
                );
            }

            const data =
                await response.json();

            const sections =
                Array.isArray(data)
                    ? data
                    : (data.sections || []);

            sectionSelect.innerHTML =
                '<option value="">Select Current Section</option>';

            sections.forEach(function (section) {

                const option =
                    document.createElement('option');

                option.value =
                    section.id;

                option.textContent =
                    section.code
                        ? section.name + ' (' + section.code + ')'
                        : section.name;

                sectionSelect.appendChild(option);
            });

            sectionSelect.disabled =
                sections.length === 0;

            if (sections.length === 0) {

                sectionSelect.innerHTML =
                    '<option value="">No Sections Available</option>';
            }

        } catch (error) {

            console.error(error);

            sectionSelect.innerHTML =
                '<option value="">Unable to load sections</option>';

            sectionSelect.disabled = true;
        }

        updateSubmitButton();
    }


    /*
    |--------------------------------------------------------------------------
    | Load Students
    |--------------------------------------------------------------------------
    */

    async function loadStudents() {

        const academicYearId =
            academicYearSelect.value;

        const classId =
            classSelect.value;

        const sectionId =
            sectionSelect.value;

        resetCurrentEnrollment();

        if (
            !academicYearId ||
            !classId ||
            !sectionId
        ) {

            resetStudents(
                'Select Academic Year, Class and Section First'
            );

            return;
        }

        studentSelect.innerHTML =
            '<option value="">Loading Students...</option>';

        studentSelect.disabled = true;

        if (studentLoading) {
            studentLoading.classList.remove('d-none');
        }

        if (studentEmpty) {
            studentEmpty.classList.add('d-none');
        }

        try {

            const params =
                new URLSearchParams({
                    academic_year_id: academicYearId,
                    class_id: classId,
                    section_id: sectionId
                });

            const response =
                await fetch(
                    filterStudentsUrl + '?' + params.toString(),
                    {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

            if (!response.ok) {
                throw new Error(
                    'Unable to load students.'
                );
            }

            const data =
                await response.json();

            const students =
                Array.isArray(data)
                    ? data
                    : (data.students || []);

            studentSelect.innerHTML =
                '<option value="">Select Student</option>';

            students.forEach(function (student) {

                const option =
                    document.createElement('option');

                option.value =
                    student.id;

                const middle =
                    student.middle_name
                        ? ' ' + student.middle_name
                        : '';

                option.textContent =
                    student.student_number +
                    ' - ' +
                    student.first_name +
                    middle +
                    ' ' +
                    student.last_name;

                /*
                 * Store authoritative enrollment data returned
                 * by the filter endpoint.
                 */
                if (student.academic_year_id) {
                    option.dataset.academicYearId =
                        student.academic_year_id;
                }

                if (student.academic_year_name) {
                    option.dataset.academicYearName =
                        student.academic_year_name;
                }

                if (student.class_id) {
                    option.dataset.classId =
                        student.class_id;
                }

                if (student.class_name) {
                    option.dataset.className =
                        student.class_name;
                }

                if (student.section_id) {
                    option.dataset.sectionId =
                        student.section_id;
                }

                if (student.section_name) {
                    option.dataset.sectionName =
                        student.section_name;
                }

                studentSelect.appendChild(option);
            });

            studentSelect.disabled =
                students.length === 0;

            if (students.length === 0) {

                studentSelect.innerHTML =
                    '<option value="">No Students Available</option>';

                if (studentEmpty) {
                    studentEmpty.classList.remove('d-none');
                }
            }

        } catch (error) {

            console.error(error);

            studentSelect.innerHTML =
                '<option value="">Unable to load students</option>';

            studentSelect.disabled = true;

        } finally {

            if (studentLoading) {
                studentLoading.classList.add('d-none');
            }
        }

        updateSubmitButton();
    }


    /*
    |--------------------------------------------------------------------------
    | Student Changed
    |--------------------------------------------------------------------------
    */

    function studentChanged() {

        const option =
            studentSelect.options[
                studentSelect.selectedIndex
            ];

        if (
            !option ||
            !option.value
        ) {

            resetCurrentEnrollment();

            return;
        }

        const academicYearId =
            option.dataset.academicYearId ||
            academicYearSelect.value ||
            '';

        const academicYearName =
            option.dataset.academicYearName ||
            academicYearSelect.options[
                academicYearSelect.selectedIndex
            ]?.textContent.trim() ||
            '';

        const classId =
            option.dataset.classId ||
            classSelect.value ||
            '';

        const className =
            option.dataset.className ||
            classSelect.options[
                classSelect.selectedIndex
            ]?.textContent.trim() ||
            '';

        const sectionId =
            option.dataset.sectionId ||
            sectionSelect.value ||
            '';

        const sectionName =
            option.dataset.sectionName ||
            sectionSelect.options[
                sectionSelect.selectedIndex
            ]?.textContent.trim() ||
            '';

        currentAcademicYear.textContent =
            academicYearName || '—';

        currentClass.textContent =
            className || '—';

        currentSection.textContent =
            sectionName || '—';

        fromClassInput.value =
            classId;

        fromSectionInput.value =
            sectionId;

        /*
         * Student is now selected, so destination class
         * can be selected.
         */
        destinationClass.disabled = false;

        destinationClass.value = '';

        destinationSection.innerHTML =
            '<option value="">Select Destination Class First</option>';

        destinationSection.value = '';
        destinationSection.disabled = true;

        updateSubmitButton();
    }


    /*
    |--------------------------------------------------------------------------
    | Load Destination Sections
    |--------------------------------------------------------------------------
    */

    async function loadDestinationSections() {

        const classId =
            destinationClass.value;

        destinationSection.innerHTML =
            '<option value="">Loading Sections...</option>';

        destinationSection.disabled = true;

        updateSubmitButton();

        if (!classId) {

            destinationSection.innerHTML =
                '<option value="">Select Destination Class First</option>';

            return;
        }

        try {

            const params =
                new URLSearchParams({
                    class_id: classId
                });

            const response =
                await fetch(
                    filterSectionsUrl + '?' + params.toString(),
                    {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

            if (!response.ok) {
                throw new Error(
                    'Unable to load destination sections.'
                );
            }

            const data =
                await response.json();

            const sections =
                Array.isArray(data)
                    ? data
                    : (data.sections || []);

            destinationSection.innerHTML =
                '<option value="">Select Destination Section</option>';

            sections.forEach(function (section) {

                const option =
                    document.createElement('option');

                option.value =
                    section.id;

                option.textContent =
                    section.code
                        ? section.name + ' (' + section.code + ')'
                        : section.name;

                destinationSection.appendChild(option);
            });

            destinationSection.disabled =
                sections.length === 0;

            if (sections.length === 0) {

                destinationSection.innerHTML =
                    '<option value="">No Sections Available</option>';
            }

        } catch (error) {

            console.error(error);

            destinationSection.innerHTML =
                '<option value="">Unable to load sections</option>';

            destinationSection.disabled = true;
        }

        updateSubmitButton();
    }


    /*
    |--------------------------------------------------------------------------
    | Academic Year Changed
    |--------------------------------------------------------------------------
    */

    academicYearSelect.addEventListener(
        'change',
        function () {

            classSelect.value = '';

            classSelect.disabled =
                !academicYearSelect.value;

            sectionSelect.innerHTML =
                '<option value="">Select Current Section</option>';

            sectionSelect.value = '';
            sectionSelect.disabled = true;

            resetStudents(
                'Select Current Class First'
            );

            resetCurrentEnrollment();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Current Class Changed
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener(
        'change',
        function () {

            loadSections();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Current Section Changed
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
    | Student Changed
    |--------------------------------------------------------------------------
    */

    studentSelect.addEventListener(
        'change',
        function () {

            studentChanged();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Destination Class Changed
    |--------------------------------------------------------------------------
    */

    destinationClass.addEventListener(
        'change',
        function () {

            loadDestinationSections();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Destination Section Changed
    |--------------------------------------------------------------------------
    */

    destinationSection.addEventListener(
        'change',
        function () {

            updateSubmitButton();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Promotion Date Changed
    |--------------------------------------------------------------------------
    */

    if (promotionDate) {

        promotionDate.addEventListener(
            'change',
            function () {

                updateSubmitButton();
            }
        );

        promotionDate.addEventListener(
            'input',
            function () {

                updateSubmitButton();
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    if (promotionForm) {

        promotionForm.addEventListener(
            'submit',
            function (event) {

                /*
                 * Final client-side validation.
                 * The academic-year select itself is submitted as
                 * academic_year_id. Server-side validation remains
                 * authoritative.
                 */
                if (!academicYearSelect.value) {
                    event.preventDefault();
                    academicYearSelect.focus();
                    updateSubmitButton();
                    return;
                }

                updateSubmitButton();

                if (submitButton.disabled) {
                    event.preventDefault();
                    return;
                }

                submitButton.disabled = true;

                submitButton.innerHTML =
                    '<i class="bx bx-loader-alt bx-spin me-1"></i>' +
                    ' Creating Promotion...';
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Initial State
    |--------------------------------------------------------------------------
    */

    resetCurrentEnrollment();

    /*
     * The academic year is preselected by the server.
     * Enable the class selector immediately.
     */
    if (academicYearSelect.value) {

        classSelect.disabled = false;
    } else {
        const currentYearId = @json(optional($currentAcademicYear)->id);

        if (currentYearId) {
            academicYearSelect.value = currentYearId;
            classSelect.disabled = false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Restore Old Validation Input
    |--------------------------------------------------------------------------
    */

    @if(old('academic_year_id') && (int) old('academic_year_id') === (int) optional($currentAcademicYear)->id)

        academicYearSelect.value =
            @json(old('academic_year_id'));

        classSelect.disabled = false;

        loadSections().then(function () {

            @if(old('from_section_id'))

                sectionSelect.value =
                    @json(old('from_section_id'));

                loadStudents();

            @endif

        });

    @endif


    /*
    |--------------------------------------------------------------------------
    | Initial Button State
    |--------------------------------------------------------------------------
    */

    updateSubmitButton();

});
</script>

@endsection