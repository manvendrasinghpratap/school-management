@extends('backend.layout.default')

@section('title', 'Edit Student Promotion')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        PAGE HEADER
    ============================================================ --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Edit Student Promotion
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
                                Promotions
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- ============================================================
        VALIDATION ERRORS
    ============================================================ --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

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


    {{-- ============================================================
        SESSION SUCCESS
    ============================================================ --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
        MAIN FORM
    ============================================================ --}}
    <form method="POST"
          action="{{ route('admin.student-promotions.update', $promotion) }}"
          id="promotionEditForm">

        @csrf

        @method('PUT')


        <div class="row">

            {{-- ========================================================
                LEFT COLUMN
            ========================================================= --}}
            <div class="col-lg-8">

                {{-- ====================================================
                    PROMOTION INFORMATION
                ===================================================== --}}
                <div class="card">

                    <div class="card-header">

                        <h4 class="card-title mb-1">
                            Promotion Information
                        </h4>

                        <p class="text-muted mb-0">
                            Update the pending student promotion.
                        </p>

                    </div>


                    <div class="card-body">


                        {{-- =================================================
                            STUDENT
                        ================================================== --}}
                        <div class="card border mb-4">

                            <div class="card-header bg-transparent">

                                <h5 class="card-title mb-0">
                                    Student
                                </h5>

                            </div>


                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-8">

                                        <label class="form-label">
                                            Student
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            value="{{ trim(($promotion->student?->student_number ?? '') . ' - ' . ($promotion->student?->first_name ?? '') . ' ' . ($promotion->student?->middle_name ?? '') . ' ' . ($promotion->student?->last_name ?? '')) }}"
                                            readonly
                                        >

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Promotion Number
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control bg-light"
                                            value="#{{ $promotion->id }}"
                                            readonly
                                        >

                                    </div>

                                </div>


                                <input
                                    type="hidden"
                                    name="student_id"
                                    value="{{ $promotion->student_id }}"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                            CURRENT ENROLLMENT
                        ================================================== --}}
                        <div class="card border mb-4">

                            <div class="card-header bg-transparent">

                                <h5 class="card-title mb-1">
                                    Current Enrollment
                                </h5>

                                <p class="text-muted mb-0">
                                    The student's current placement is used as
                                    the source for this promotion.
                                </p>

                            </div>


                            <div class="card-body">

                                <div class="row">

                                    {{-- Academic Year --}}
                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Academic Year
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control bg-light"
                                            value="{{ $promotion->academicYear?->name ?? '—' }}"
                                            readonly
                                        >

                                    </div>


                                    {{-- Current Class --}}
                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Current / From Class
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control bg-light"
                                            value="{{ $promotion->fromClass?->name ?? '—' }}"
                                            readonly
                                        >

                                    </div>


                                    {{-- Current Section --}}
                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Current / From Section
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control bg-light"
                                            value="{{ $promotion->fromSection?->name ?? '—' }}"
                                            readonly
                                        >

                                    </div>

                                </div>


                                <div class="alert alert-info mt-3 mb-0">

                                    <div class="d-flex">

                                        <i class="bx bx-info-circle font-size-18 me-2"></i>

                                        <div>

                                            <strong>
                                                Current enrollment is protected.
                                            </strong>

                                            <p class="mb-0 mt-1">
                                                The source academic year, class
                                                and section should not be changed
                                                when editing a promotion request.
                                                The server verifies the student's
                                                actual enrollment before processing
                                                the promotion.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            ACADEMIC YEAR / DATE
                        ================================================== --}}
                        <div class="row">

                            {{-- Academic Year --}}
                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label">

                                        Academic Year

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                    <select
                                        name="academic_year_id"
                                        class="form-select @error('academic_year_id') is-invalid @enderror"
                                        required
                                    >

                                        @foreach($academicYears as $year)

                                            <option
                                                value="{{ $year->id }}"
                                                {{ old('academic_year_id', $promotion->academic_year_id) == $year->id ? 'selected' : '' }}
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


                            {{-- Promotion Date --}}
                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label">

                                        Promotion Date

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                    <input
                                        type="date"
                                        name="promotion_date"
                                        class="form-control @error('promotion_date') is-invalid @enderror"
                                        value="{{ old('promotion_date', $promotion->promotion_date?->format('Y-m-d')) }}"
                                        required
                                    >

                                    @error('promotion_date')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            DESTINATION
                        ================================================== --}}
                        <div class="card border bg-light mb-4">

                            <div class="card-header bg-transparent">

                                <h5 class="card-title mb-1">
                                    Promotion Destination
                                </h5>

                                <p class="text-muted mb-0">
                                    Select the immediate next class and the
                                    destination section.
                                </p>

                            </div>


                            <div class="card-body">

                                <div class="row">

                                    {{-- Destination Class --}}
                                    <div class="col-md-6">

                                        <div class="mb-3">

                                            <label
                                                for="to_class_id"
                                                class="form-label"
                                            >

                                                Destination / To Class

                                                <span class="text-danger">
                                                    *
                                                </span>

                                            </label>


                                            <select
                                                name="to_class_id"
                                                id="to_class_id"
                                                class="form-select @error('to_class_id') is-invalid @enderror"
                                                required
                                            >

                                                <option value="">
                                                    Select Destination Class
                                                </option>

                                                @foreach($classes as $class)

                                                    <option
                                                        value="{{ $class->id }}"
                                                        data-level-id="{{ $class->level_id }}"
                                                        data-class-name="{{ $class->name }}"
                                                        {{ old('to_class_id', $promotion->to_class_id) == $class->id ? 'selected' : '' }}
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


                                            <div class="form-text">

                                                The destination must be the
                                                immediate next class.

                                            </div>

                                        </div>

                                    </div>


                                    {{-- Destination Section --}}
                                    <div class="col-md-6">

                                        <div class="mb-3">

                                            <label
                                                for="to_section_id"
                                                class="form-label"
                                            >

                                                Destination / To Section

                                                <span class="text-danger">
                                                    *
                                                </span>

                                            </label>


                                            <select
                                                name="to_section_id"
                                                id="to_section_id"
                                                class="form-select @error('to_section_id') is-invalid @enderror"
                                                required
                                            >

                                                <option value="">
                                                    Select Destination Section
                                                </option>

                                                @foreach($sections as $section)

                                                    <option
                                                        value="{{ $section->id }}"
                                                        data-class-id="{{ $section->class_id }}"
                                                        {{ old('to_section_id', $promotion->to_section_id) == $section->id ? 'selected' : '' }}
                                                    >

                                                        {{ $section->name }}

                                                        @if($section->code)
                                                            ({{ $section->code }})
                                                        @endif

                                                    </option>

                                                @endforeach

                                            </select>


                                            @error('to_section_id')

                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>

                                            @enderror


                                            <div class="form-text">

                                                <i class="bx bx-info-circle me-1"></i>

                                                Destination section is required
                                                and must belong to the selected
                                                destination class.

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- Destination Summary --}}
                                <div
                                    id="destinationSummary"
                                    class="alert alert-primary mb-0"
                                >

                                    <div class="d-flex">

                                        <i class="bx bx-transfer-alt font-size-20 me-2"></i>

                                        <div>

                                            <strong>
                                                Promotion:
                                            </strong>

                                            <span id="promotionSummaryText">
                                                Select a destination class and
                                                section.
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            STATUS
                        ================================================== --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                            >

                                <option
                                    value="pending"
                                    {{ old('status', $promotion->status) === 'pending' ? 'selected' : '' }}
                                >
                                    Pending
                                </option>

                                <option
                                    value="rejected"
                                    {{ old('status', $promotion->status) === 'rejected' ? 'selected' : '' }}
                                >
                                    Rejected
                                </option>

                            </select>


                            @error('status')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror


                            <small class="text-muted">

                                Approved promotions cannot be edited because
                                approval has already changed enrollment history.

                            </small>

                        </div>


                        {{-- =================================================
                            REMARKS
                        ================================================== --}}
                        <div class="mb-4">

                            <label
                                for="remarks"
                                class="form-label"
                            >

                                Remarks

                                <span class="text-muted">
                                    (Optional)
                                </span>

                            </label>


                            <textarea
                                name="remarks"
                                id="remarks"
                                class="form-control @error('remarks') is-invalid @enderror"
                                rows="4"
                                maxlength="5000"
                                placeholder="Enter any remarks or notes about this promotion..."
                            >{{ old('remarks', $promotion->remarks) }}</textarea>


                            @error('remarks')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                            FORM ACTIONS
                        ================================================== --}}
                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('admin.student-promotions.show', $promotion) }}"
                                class="btn btn-light"
                            >

                                <i class="bx bx-arrow-back me-1"></i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                id="updatePromotionButton"
                                class="btn btn-primary"
                            >

                                <i class="bx bx-save me-1"></i>

                                Update Promotion

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                RIGHT COLUMN
            ========================================================= --}}
            <div class="col-lg-4">

                {{-- ====================================================
                    CURRENT STATUS
                ===================================================== --}}
                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Current Status
                        </h5>

                    </div>


                    <div class="card-body text-center">

                        @if($promotion->status === 'pending')

                            <span
                                class="badge bg-warning font-size-14 px-3 py-2"
                            >

                                Pending

                            </span>


                            <p class="text-muted mt-3 mb-0">

                                This promotion is waiting for approval.

                            </p>

                        @elseif($promotion->status === 'approved')

                            <span
                                class="badge bg-success font-size-14 px-3 py-2"
                            >

                                Approved

                            </span>


                            <p class="text-muted mt-3 mb-0">

                                This promotion has already been approved.

                            </p>

                        @else

                            <span
                                class="badge bg-danger font-size-14 px-3 py-2"
                            >

                                Rejected

                            </span>


                            <p class="text-muted mt-3 mb-0">

                                This promotion has been rejected.

                            </p>

                        @endif

                    </div>

                </div>


                {{-- ====================================================
                    PROMOTION SUMMARY
                ===================================================== --}}
                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Promotion Summary
                        </h5>

                    </div>


                    <div class="card-body">

                        {{-- Student --}}
                        <div class="mb-3">

                            <label class="text-muted d-block mb-1">
                                Student
                            </label>

                            <h6 class="mb-0">

                                {{ $promotion->student?->first_name }}
                                {{ $promotion->student?->middle_name }}
                                {{ $promotion->student?->last_name }}

                            </h6>

                            <small class="text-muted">

                                {{ $promotion->student?->student_number }}

                            </small>

                        </div>


                        {{-- From --}}
                        <div class="mb-3">

                            <label class="text-muted d-block mb-1">
                                From
                            </label>

                            <h6 class="mb-0">

                                {{ $promotion->fromClass?->name ?? '—' }}

                                @if($promotion->fromSection)
                                    / {{ $promotion->fromSection->name }}
                                @endif

                            </h6>

                        </div>


                        {{-- To --}}
                        <div class="mb-3">

                            <label class="text-muted d-block mb-1">
                                To
                            </label>

                            <h6
                                id="sidebarDestination"
                                class="mb-0"
                            >

                                {{ $promotion->toClass?->name ?? '—' }}

                                @if($promotion->toSection)
                                    / {{ $promotion->toSection->name }}
                                @endif

                            </h6>

                        </div>


                        {{-- Academic Year --}}
                        <div class="mb-3">

                            <label class="text-muted d-block mb-1">
                                Academic Year
                            </label>

                            <h6 class="mb-0">

                                {{ $promotion->academicYear?->name ?? '—' }}

                            </h6>

                        </div>


                        {{-- Created --}}
                        <div>

                            <label class="text-muted d-block mb-1">
                                Created
                            </label>

                            <h6 class="mb-0">

                                {{ $promotion->created_at?->format('Y-m-d H:i') ?? '—' }}

                            </h6>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                    BUSINESS RULE
                ===================================================== --}}
                <div class="card">

                    <div class="card-body">

                        <div class="alert alert-info mb-0">

                            <div class="d-flex">

                                <i class="bx bx-info-circle font-size-18 me-2"></i>

                                <div>

                                    <strong>
                                        Promotion Rule
                                    </strong>

                                    <p class="mb-0 mt-1">

                                        A student can only be promoted to the
                                        immediate next class. The destination
                                        section must belong to that class.

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


@push('script')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const fromClassSelect =
        document.getElementById('from_class_id');

    const toClassSelect =
        document.getElementById('to_class_id');

    const toSectionSelect =
        document.getElementById('to_section_id');

    const promotionSummaryText =
        document.getElementById('promotionSummaryText');

    const sidebarDestination =
        document.getElementById('sidebarDestination');

    const form =
        document.getElementById('promotionEditForm');

    const submitButton =
        document.getElementById('updatePromotionButton');


    /*
    |--------------------------------------------------------------------------
    | Filter Destination Sections
    |--------------------------------------------------------------------------
    |
    | Sections do not have school_id.
    | They belong to classes through class_id.
    |
    */

    function filterDestinationSections() {

        if (!toClassSelect || !toSectionSelect) {
            return;
        }


        const classId =
            toClassSelect.value;


        let selectedSection =
            toSectionSelect.value;


        Array.from(
            toSectionSelect.options
        ).forEach(function (option) {

            if (!option.value) {

                option.hidden = false;

                return;
            }


            const optionClassId =
                option.getAttribute('data-class-id');


            option.hidden =
                optionClassId !== classId;

        });


        /*
         * If the selected section does not belong to the
         * selected destination class, clear it.
         */
        const selectedOption =
            toSectionSelect.options[
                toSectionSelect.selectedIndex
            ];


        if (
            selectedOption &&
            selectedOption.value &&
            selectedOption.getAttribute('data-class-id') !== classId
        ) {

            toSectionSelect.value = '';

        }


        /*
         * If there is no destination class selected,
         * disable the section selector.
         */
        toSectionSelect.disabled =
            classId === '';

    }


    /*
    |--------------------------------------------------------------------------
    | Update Destination Summary
    |--------------------------------------------------------------------------
    */

    function updateDestinationSummary() {

        if (
            !toClassSelect ||
            !toSectionSelect
        ) {
            return;
        }


        const classOption =
            toClassSelect.options[
                toClassSelect.selectedIndex
            ];


        const sectionOption =
            toSectionSelect.options[
                toSectionSelect.selectedIndex
            ];


        const className =
            classOption &&
            classOption.value
                ? classOption.textContent.trim()
                : '';


        const sectionName =
            sectionOption &&
            sectionOption.value
                ? sectionOption.textContent.trim()
                : '';


        if (
            className &&
            sectionName
        ) {

            const summary =
                className +
                ' / ' +
                sectionName;


            promotionSummaryText.textContent =
                summary;


            if (sidebarDestination) {

                sidebarDestination.textContent =
                    summary;

            }

        } else if (className) {

            promotionSummaryText.textContent =
                className +
                ' / Select Section';


            if (sidebarDestination) {

                sidebarDestination.textContent =
                    className +
                    ' / Select Section';

            }

        } else {

            promotionSummaryText.textContent =
                'Select a destination class and section.';


            if (sidebarDestination) {

                sidebarDestination.textContent =
                    '—';

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Prevent Same Current / Destination Class
    |--------------------------------------------------------------------------
    */

    function validateDestinationClass() {

        if (
            !fromClassSelect ||
            !toClassSelect
        ) {
            return;
        }


        const currentClassId =
            fromClassSelect.value;


        Array.from(
            toClassSelect.options
        ).forEach(function (option) {

            if (!option.value) {

                option.hidden = false;

                return;
            }


            /*
             * The current class cannot be selected as
             * the destination class.
             */
            option.disabled =
                option.value === currentClassId;

        });


        /*
         * If the current selection is now invalid,
         * clear it.
         */
        if (
            toClassSelect.value ===
            currentClassId
        ) {

            toClassSelect.value = '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Destination Class Changed
    |--------------------------------------------------------------------------
    */

    if (toClassSelect) {

        toClassSelect.addEventListener(
            'change',
            function () {

                filterDestinationSections();

                updateDestinationSummary();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Destination Section Changed
    |--------------------------------------------------------------------------
    */

    if (toSectionSelect) {

        toSectionSelect.addEventListener(
            'change',
            function () {

                updateDestinationSummary();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Initial Setup
    |--------------------------------------------------------------------------
    */

    validateDestinationClass();

    filterDestinationSections();

    updateDestinationSummary();


    /*
    |--------------------------------------------------------------------------
    | Prevent Invalid Submission
    |--------------------------------------------------------------------------
    */

    if (form) {

        form.addEventListener(
            'submit',
            function (event) {

                const destinationClass =
                    toClassSelect
                        ? toClassSelect.value
                        : '';


                const destinationSection =
                    toSectionSelect
                        ? toSectionSelect.value
                        : '';


                const currentClass =
                    fromClassSelect
                        ? fromClassSelect.value
                        : '';


                /*
                 * Destination class required.
                 */
                if (!destinationClass) {

                    event.preventDefault();

                    alert(
                        'Please select a destination class.'
                    );

                    return;

                }


                /*
                 * Destination cannot equal current class.
                 */
                if (
                    destinationClass ===
                    currentClass
                ) {

                    event.preventDefault();

                    alert(
                        'The destination class must be different from the current class.'
                    );

                    return;

                }


                /*
                 * Destination section required.
                 */
                if (!destinationSection) {

                    event.preventDefault();

                    alert(
                        'Please select a destination section.'
                    );

                    toSectionSelect.focus();

                    return;

                }


                /*
                 * Prevent double submission.
                 */
                if (submitButton) {

                    submitButton.disabled = true;

                    submitButton.innerHTML =
                        '<i class="bx bx-loader-alt bx-spin me-1"></i>' +
                        ' Updating Promotion...';

                }

            }
        );

    }

});

</script>

@endpush