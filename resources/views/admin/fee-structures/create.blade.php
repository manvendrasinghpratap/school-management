@extends('backend.layout.default')

@section('title', 'Create Fee Structure')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Page Header --}}
    {{-- ========================================================= --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-sm-0">
                        Create Fee Structure
                    </h4>
                </div>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Finance
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.fee-structures.index') }}">
                                Fee Structures
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- Alerts --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="mdi mdi-check-circle-outline me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="mdi mdi-alert-circle-outline me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Validation Errors --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    <div class="row">

        {{-- ===================================================== --}}
        {{-- Main Form --}}
        {{-- ===================================================== --}}

        <div class="col-xl-8 col-lg-8">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm me-3">

                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">

                                <i class="bx bx-money font-size-20"></i>

                            </span>

                        </div>

                        <div>

                            <h5 class="card-title mb-1">
                                Fee Structure Information
                            </h5>

                            <p class="text-muted mb-0">
                                Define the amount and academic period for this fee.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.fee-structures.store') }}">

                        @csrf


                        {{-- ================================================= --}}
                        {{-- Fee Category --}}
                        {{-- ================================================= --}}

                        <div class="mb-3">

                            <label for="fee_category_id"
                                   class="form-label">

                                Fee Category

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <select
                                name="fee_category_id"
                                id="fee_category_id"
                                class="form-select @error('fee_category_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Fee Category
                                </option>

                                @foreach($feeCategories as $feeCategory)

                                    <option
                                        value="{{ $feeCategory->id }}"
                                        @selected(
                                            old('fee_category_id') == $feeCategory->id
                                        )
                                    >

                                        {{ $feeCategory->name }}

                                        @if($feeCategory->code)
                                            ({{ $feeCategory->code }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('fee_category_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div class="form-text">

                                Select the type of fee this structure represents.

                                Examples:
                                Tuition, Admission, Examination, Extra, Fine.

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Academic Year --}}
                        {{-- ================================================= --}}

                        <div class="mb-3">

                            <label for="academic_year_id"
                                   class="form-label">

                                Academic Year

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <select
                                name="academic_year_id"
                                id="academic_year_id"
                                class="form-select @error('academic_year_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Academic Year
                                </option>

                                @foreach($academicYears as $academicYear)

                                    <option
                                        value="{{ $academicYear->id }}"
                                        @selected(
                                            old('academic_year_id') == $academicYear->id
                                        )
                                    >

                                        {{ $academicYear->name }}

                                        @if($academicYear->is_current)
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

                            <div class="form-text">

                                Selecting an academic year will automatically
                                load its terms and mapped classes.

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Class --}}
                        {{-- ================================================= --}}

                        <div class="mb-3">

                            <label for="class_id"
                                   class="form-label">

                                Class

                            </label>

                            <select
                                name="class_id"
                                id="class_id"
                                class="form-select @error('class_id') is-invalid @enderror"
                                disabled
                            >

                                <option value="">
                                    Select Academic Year First
                                </option>

                            </select>

                            @error('class_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div id="class-loading"
                                 class="form-text text-primary d-none">

                                <i class="mdi mdi-loading mdi-spin me-1"></i>

                                Loading mapped classes...

                            </div>

                            <div id="class-help"
                                 class="form-text">

                                Only classes mapped to the selected
                                academic year will be displayed.

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Term --}}
                        {{-- ================================================= --}}

                        <div class="mb-3">

                            <label for="term_id"
                                   class="form-label">

                                Term

                            </label>

                            <select
                                name="term_id"
                                id="term_id"
                                class="form-select @error('term_id') is-invalid @enderror"
                                disabled
                            >

                                <option value="">
                                    Select Academic Year First
                                </option>

                            </select>

                            @error('term_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div id="term-loading"
                                 class="form-text text-primary d-none">

                                <i class="mdi mdi-loading mdi-spin me-1"></i>

                                Loading terms...

                            </div>

                            <div id="term-help"
                                 class="form-text">

                                Only terms belonging to the selected
                                academic year will be displayed.

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Amount --}}
                        {{-- ================================================= --}}

                        <div class="mb-3">

                            <label for="amount"
                                   class="form-label">

                                Amount

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₦
                                </span>

                                <input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount') }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                    required
                                >

                            </div>

                            @error('amount')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div class="form-text">

                                Enter the standard amount for this fee
                                structure.

                                Example:
                                ₦24,000.00 annual tuition.

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Due Date --}}
                        {{-- ================================================= --}}

                        <div class="mb-3">

                            <label for="due_date"
                                   class="form-label">

                                Due Date

                            </label>

                            <input
                                type="date"
                                name="due_date"
                                id="due_date"
                                class="form-control @error('due_date') is-invalid @enderror"
                                value="{{ old('due_date') }}"
                            >

                            @error('due_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div class="form-text">

                                Optional. Use this when the entire
                                fee structure has a specific due date.

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Status --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <div class="form-check form-switch">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    @checked(old('is_active', true))
                                >

                                <label class="form-check-label"
                                       for="is_active">

                                    Active Fee Structure

                                </label>

                            </div>

                            <div class="form-text">

                                Inactive fee structures will not normally
                                be available for new fee processing.

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Actions --}}
                        {{-- ================================================= --}}

                        <div class="d-flex flex-wrap gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bx bx-save me-1"></i>

                                Save Fee Structure

                            </button>


                            <a
                                href="{{ route('admin.fee-structures.index') }}"
                                class="btn btn-light"
                            >

                                <i class="bx bx-arrow-back me-1"></i>

                                Cancel

                            </a>

                        </div>


                    </form>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Information Panel --}}
        {{-- ===================================================== --}}

        <div class="col-xl-4 col-lg-4">

            {{-- Fee Structure Explanation --}}
            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="bx bx-info-circle me-2 text-primary"></i>

                        About Fee Structures

                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted">

                        A fee structure defines how much a particular
                        fee category costs for an academic period.

                    </p>


                    <div class="border rounded p-3 mb-3">

                        <h6 class="mb-2">
                            Example
                        </h6>

                        <div class="small text-muted">

                            <div class="mb-1">
                                <strong>Fee Category:</strong>
                                Tuition
                            </div>

                            <div class="mb-1">
                                <strong>Academic Year:</strong>
                                2026/2027
                            </div>

                            <div class="mb-1">
                                <strong>Class:</strong>
                                Primary 1
                            </div>

                            <div class="mb-1">
                                <strong>Term:</strong>
                                All Terms
                            </div>

                            <div>
                                <strong>Amount:</strong>
                                ₦24,000.00
                            </div>

                        </div>

                    </div>


                    <h6 class="mb-3">
                        Important Rules
                    </h6>

                    <ul class="text-muted small ps-3 mb-0">

                        <li class="mb-2">

                            Select the Academic Year first.

                        </li>

                        <li class="mb-2">

                            Terms are loaded only from the selected
                            Academic Year.

                        </li>

                        <li class="mb-2">

                            Classes are loaded only when they are
                            mapped to the selected Academic Year.

                        </li>

                        <li class="mb-2">

                            Leaving Class as
                            <strong>All Classes</strong>
                            creates a school-wide fee structure
                            for that academic year.

                        </li>

                        <li class="mb-2">

                            Leaving Term as
                            <strong>All Terms</strong>
                            creates a fee structure covering
                            the academic year.

                        </li>

                        <li>

                            Historical fee structures should not be
                            changed merely because a student changes
                            class later.

                        </li>

                    </ul>

                </div>

            </div>


            {{-- Finance Flow --}}
            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="bx bx-git-branch me-2 text-success"></i>

                        Finance Flow

                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex align-items-center mb-3">

                        <div class="avatar-xs me-3">

                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">

                                1

                            </span>

                        </div>

                        <div>

                            <h6 class="mb-0">
                                Fee Category
                            </h6>

                            <small class="text-muted">
                                What is being charged?
                            </small>

                        </div>

                    </div>


                    <div class="text-center text-muted mb-3">

                        <i class="bx bx-down-arrow-alt font-size-18"></i>

                    </div>


                    <div class="d-flex align-items-center mb-3">

                        <div class="avatar-xs me-3">

                            <span class="avatar-title rounded-circle bg-success-subtle text-success">

                                2

                            </span>

                        </div>

                        <div>

                            <h6 class="mb-0">
                                Fee Structure
                            </h6>

                            <small class="text-muted">
                                How much and for which period?
                            </small>

                        </div>

                    </div>


                    <div class="text-center text-muted mb-3">

                        <i class="bx bx-down-arrow-alt font-size-18"></i>

                    </div>


                    <div class="d-flex align-items-center mb-3">

                        <div class="avatar-xs me-3">

                            <span class="avatar-title rounded-circle bg-warning-subtle text-warning">

                                3

                            </span>

                        </div>

                        <div>

                            <h6 class="mb-0">
                                Invoice
                            </h6>

                            <small class="text-muted">
                                Student-specific charges
                            </small>

                        </div>

                    </div>


                    <div class="text-center text-muted mb-3">

                        <i class="bx bx-down-arrow-alt font-size-18"></i>

                    </div>


                    <div class="d-flex align-items-center">

                        <div class="avatar-xs me-3">

                            <span class="avatar-title rounded-circle bg-info-subtle text-info">

                                4

                            </span>

                        </div>

                        <div>

                            <h6 class="mb-0">
                                Payment & Receipt
                            </h6>

                            <small class="text-muted">
                                Collect and record payment
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- Dependent Dropdown JavaScript --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const academicYearSelect =
        document.getElementById('academic_year_id');

    const classSelect =
        document.getElementById('class_id');

    const termSelect =
        document.getElementById('term_id');

    const classLoading =
        document.getElementById('class-loading');

    const termLoading =
        document.getElementById('term-loading');

    const classHelp =
        document.getElementById('class-help');

    const termHelp =
        document.getElementById('term-help');


    /*
    |--------------------------------------------------------------------------
    | Old Laravel values
    |--------------------------------------------------------------------------
    |
    | These allow the form to restore the user's selections after
    | validation failure.
    |
    */

    const oldClassId =
        @json(old('class_id'));

    const oldTermId =
        @json(old('term_id'));


    /*
    |--------------------------------------------------------------------------
    | Reset Class
    |--------------------------------------------------------------------------
    */

    function resetClass(
        message = 'Select Academic Year First'
    ) {

        classSelect.innerHTML = '';

        const option =
            document.createElement('option');

        option.value = '';

        option.textContent =
            message;

        classSelect.appendChild(option);

        classSelect.disabled = true;

        if (classHelp) {

            classHelp.textContent =
                'Only classes mapped to the selected academic year will be displayed.';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Reset Term
    |--------------------------------------------------------------------------
    */

    function resetTerm(
        message = 'Select Academic Year First'
    ) {

        termSelect.innerHTML = '';

        const option =
            document.createElement('option');

        option.value = '';

        option.textContent =
            message;

        termSelect.appendChild(option);

        termSelect.disabled = true;

        if (termHelp) {

            termHelp.textContent =
                'Only terms belonging to the selected academic year will be displayed.';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Load Classes
    |--------------------------------------------------------------------------
    */

    async function loadClasses(
        academicYearId,
        selectedClassId = ''
    ) {

        resetClass(
            'Loading mapped classes...'
        );


        if (!academicYearId) {

            resetClass();

            return;
        }


        classSelect.disabled = true;


        if (classLoading) {

            classLoading.classList.remove('d-none');

        }


        try {

            const url =
                "{{ url('admin/fee-structures/academic-years') }}/"
                + encodeURIComponent(academicYearId)
                + "/classes";


            const response =
                await fetch(
                    url,
                    {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to load classes. HTTP ' +
                    response.status
                );

            }


            const data =
                await response.json();


            const classes =
                Array.isArray(data.classes)
                    ? data.classes
                    : [];


            /*
            |--------------------------------------------------------------------------
            | Clear dropdown
            |--------------------------------------------------------------------------
            */

            classSelect.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | All Classes
            |--------------------------------------------------------------------------
            |
            | Empty value means class_id = NULL.
            |
            */

            const allClassesOption =
                document.createElement('option');

            allClassesOption.value = '';

            allClassesOption.textContent =
                'All Classes';


            if (
                selectedClassId === null ||
                selectedClassId === undefined ||
                selectedClassId === ''
            ) {

                allClassesOption.selected = true;

            }


            classSelect.appendChild(
                allClassesOption
            );


            /*
            |--------------------------------------------------------------------------
            | Add mapped classes
            |--------------------------------------------------------------------------
            */

            classes.forEach(function (schoolClass) {

                const option =
                    document.createElement('option');


                option.value =
                    schoolClass.id;


                option.textContent =
                    schoolClass.code
                        ? schoolClass.name +
                          ' (' +
                          schoolClass.code +
                          ')'
                        : schoolClass.name;


                if (
                    selectedClassId &&
                    String(selectedClassId) ===
                    String(schoolClass.id)
                ) {

                    option.selected = true;

                }


                classSelect.appendChild(
                    option
                );

            });


            /*
            |--------------------------------------------------------------------------
            | Enable dropdown
            |--------------------------------------------------------------------------
            */

            classSelect.disabled = false;


            /*
            |--------------------------------------------------------------------------
            | Help text
            |--------------------------------------------------------------------------
            */

            if (classes.length === 0) {

                if (classHelp) {

                    classHelp.innerHTML =
                        '<span class="text-warning">' +
                        '<i class="mdi mdi-alert-outline me-1"></i>' +
                        'No classes are currently mapped to this academic year.' +
                        '</span>';

                }

            } else {

                if (classHelp) {

                    classHelp.textContent =
                        classes.length +
                        ' mapped class' +
                        (
                            classes.length === 1
                                ? ''
                                : 'es'
                        ) +
                        ' available for this academic year.';

                }

            }


        } catch (error) {

            console.error(
                'Fee Structure - Load Classes:',
                error
            );


            resetClass(
                'Unable to load classes'
            );


            if (classHelp) {

                classHelp.innerHTML =
                    '<span class="text-danger">' +
                    '<i class="mdi mdi-alert-circle-outline me-1"></i>' +
                    'Unable to load mapped classes. Please try again.' +
                    '</span>';

            }

        } finally {

            if (classLoading) {

                classLoading.classList.add(
                    'd-none'
                );

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Load Terms
    |--------------------------------------------------------------------------
    */

    async function loadTerms(
        academicYearId,
        selectedTermId = ''
    ) {

        resetTerm(
            'Loading terms...'
        );


        if (!academicYearId) {

            resetTerm();

            return;
        }


        termSelect.disabled = true;


        if (termLoading) {

            termLoading.classList.remove(
                'd-none'
            );

        }


        try {

            const url =
                "{{ url('admin/fee-structures/academic-years') }}/"
                + encodeURIComponent(academicYearId)
                + "/terms";


            const response =
                await fetch(
                    url,
                    {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to load terms. HTTP ' +
                    response.status
                );

            }


            const data =
                await response.json();


            const terms =
                Array.isArray(data.terms)
                    ? data.terms
                    : [];


            /*
            |--------------------------------------------------------------------------
            | Clear dropdown
            |--------------------------------------------------------------------------
            */

            termSelect.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | All Terms
            |--------------------------------------------------------------------------
            |
            | Empty value means term_id = NULL.
            |
            */

            const allTermsOption =
                document.createElement('option');

            allTermsOption.value = '';

            allTermsOption.textContent =
                'All Terms';


            if (
                selectedTermId === null ||
                selectedTermId === undefined ||
                selectedTermId === ''
            ) {

                allTermsOption.selected = true;

            }


            termSelect.appendChild(
                allTermsOption
            );


            /*
            |--------------------------------------------------------------------------
            | Add Terms
            |--------------------------------------------------------------------------
            */

            terms.forEach(function (term) {

                const option =
                    document.createElement('option');


                option.value =
                    term.id;


                option.textContent =
                    term.term_number
                        ? term.name +
                          ' (Term ' +
                          term.term_number +
                          ')'
                        : term.name;


                if (
                    selectedTermId &&
                    String(selectedTermId) ===
                    String(term.id)
                ) {

                    option.selected = true;

                }


                termSelect.appendChild(
                    option
                );

            });


            /*
            |--------------------------------------------------------------------------
            | Enable dropdown
            |--------------------------------------------------------------------------
            */

            termSelect.disabled = false;


            /*
            |--------------------------------------------------------------------------
            | Help text
            |--------------------------------------------------------------------------
            */

            if (terms.length === 0) {

                if (termHelp) {

                    termHelp.innerHTML =
                        '<span class="text-warning">' +
                        '<i class="mdi mdi-alert-outline me-1"></i>' +
                        'No terms have been configured for this academic year.' +
                        '</span>';

                }

            } else {

                if (termHelp) {

                    termHelp.textContent =
                        terms.length +
                        ' term' +
                        (
                            terms.length === 1
                                ? ''
                                : 's'
                        ) +
                        ' available for this academic year.';

                }

            }


        } catch (error) {

            console.error(
                'Fee Structure - Load Terms:',
                error
            );


            resetTerm(
                'Unable to load terms'
            );


            if (termHelp) {

                termHelp.innerHTML =
                    '<span class="text-danger">' +
                    '<i class="mdi mdi-alert-circle-outline me-1"></i>' +
                    'Unable to load terms. Please try again.' +
                    '</span>';

            }

        } finally {

            if (termLoading) {

                termLoading.classList.add(
                    'd-none'
                );

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Academic Year Change
    |--------------------------------------------------------------------------
    */

    academicYearSelect.addEventListener(
        'change',
        function () {

            const academicYearId =
                this.value;


            /*
            |--------------------------------------------------------------------------
            | VERY IMPORTANT
            |--------------------------------------------------------------------------
            |
            | Whenever the academic year changes, completely reset both
            | dependent dropdowns.
            |
            */

            resetClass();

            resetTerm();


            if (!academicYearId) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Load both independently
            |--------------------------------------------------------------------------
            */

            loadClasses(
                academicYearId
            );


            loadTerms(
                academicYearId
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Page Load
    |--------------------------------------------------------------------------
    |
    | This is important after validation errors.
    |
    */

    if (academicYearSelect.value) {

        loadClasses(
            academicYearSelect.value,
            oldClassId
        );


        loadTerms(
            academicYearSelect.value,
            oldTermId
        );

    } else {

        resetClass();

        resetTerm();

    }

});

</script>

@endsection