@extends('backend.layout.default')

@section('title', 'Edit Fee Structure')

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
                        Edit Fee Structure
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
                            Edit
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- Success Message --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- Error Message --}}
    {{-- ========================================================= --}}

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
                    data-bs-dismiss="alert">
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
                                Edit Fee Structure
                            </h5>

                            <p class="text-muted mb-0">
                                Update the fee structure configuration.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route('admin.fee-structures.update', $feeStructure) }}"
                    >

                        @csrf

                        @method('PUT')


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
                                            old(
                                                'fee_category_id',
                                                $feeStructure->fee_category_id
                                            ) == $feeCategory->id
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
                                            old(
                                                'academic_year_id',
                                                $feeStructure->academic_year_id
                                            ) == $academicYear->id
                                        )
                                    >

                                        {{ $academicYear->name }}

                                        @if(isset($academicYear->is_current) && $academicYear->is_current)
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
                                Changing the academic year will reload the mapped classes and terms.
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
                                    Loading classes...
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

                                Classes mapped to the selected academic year will be displayed.

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
                                    Loading terms...
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

                                Terms belonging to the selected academic year will be displayed.

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
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount', $feeStructure->amount) }}"
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
                                Enter the standard amount for this fee structure.
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
                                value="{{ old(
                                    'due_date',
                                    $feeStructure->due_date
                                        ? \Carbon\Carbon::parse($feeStructure->due_date)->format('Y-m-d')
                                        : ''
                                ) }}"
                            >

                            @error('due_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div class="form-text">
                                Optional due date for this fee structure.
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
                                    @checked(
                                        old(
                                            'is_active',
                                            $feeStructure->is_active
                                        )
                                    )
                                >

                                <label
                                    class="form-check-label"
                                    for="is_active"
                                >

                                    Active Fee Structure

                                </label>

                            </div>

                            <div class="form-text">

                                Inactive fee structures will not normally
                                be used for new fee processing.

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Buttons --}}
                        {{-- ================================================= --}}

                        <div class="d-flex flex-wrap gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bx bx-save me-1"></i>

                                Update Fee Structure

                            </button>


                            <a
                                href="{{ route('admin.fee-structures.show', $feeStructure) }}"
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
        {{-- Right Information Panel --}}
        {{-- ===================================================== --}}

        <div class="col-xl-4 col-lg-4">

            {{-- Current Configuration --}}
            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="bx bx-info-circle me-2 text-primary"></i>

                        Current Configuration

                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <label class="text-muted d-block mb-1">
                            Fee Category
                        </label>

                        <strong>

                            {{ optional($feeStructure->feeCategory)->name ?: '—' }}

                        </strong>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted d-block mb-1">
                            Academic Year
                        </label>

                        <strong>

                            {{ optional($feeStructure->academicYear)->name ?: '—' }}

                        </strong>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted d-block mb-1">
                            Current Class
                        </label>

                        <strong>

                            {{ optional($feeStructure->classModel)->name ?: 'All Classes' }}

                        </strong>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted d-block mb-1">
                            Current Term
                        </label>

                        <strong>

                            {{ optional($feeStructure->term)->name ?: 'All Terms' }}

                        </strong>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted d-block mb-1">
                            Current Amount
                        </label>

                        <h4 class="mb-0">

                            ₹{{ number_format(
                                (float) $feeStructure->amount,
                                2
                            ) }}

                        </h4>

                    </div>


                    <div>

                        <label class="text-muted d-block mb-1">
                            Status
                        </label>

                        @if($feeStructure->is_active)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Warning --}}
            <div class="card border-warning">

                <div class="card-header bg-warning-subtle">

                    <h5 class="card-title mb-0">

                        <i class="bx bx-error-circle me-2 text-warning"></i>

                        Before Updating

                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted small mb-0">

                        Changes to a fee structure should be made carefully.
                        Existing invoices and historical financial transactions
                        should not be changed simply because a student's class
                        or academic year changes.

                    </p>

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


    if (
        !academicYearSelect ||
        !classSelect ||
        !termSelect
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Existing / Old Values
    |--------------------------------------------------------------------------
    |
    | If validation fails, Laravel old() values take priority.
    | Otherwise we use the existing database values.
    |
    */

    const oldClassId =
        @json(old('class_id'));

    const oldTermId =
        @json(old('term_id'));


    const existingClassId =
        @json($feeStructure->class_id);

    const existingTermId =
        @json($feeStructure->term_id);


    const initialClassId =
        oldClassId !== null
            ? oldClassId
            : existingClassId;


    const initialTermId =
        oldTermId !== null
            ? oldTermId
            : existingTermId;


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


        if (classLoading) {

            classLoading.classList.remove(
                'd-none'
            );

        }


        try {

            const url =
                "{{ url('admin/fee-structures/academic-years') }}/"
                + encodeURIComponent(
                    academicYearId
                )
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
            | Rebuild Class Dropdown
            |--------------------------------------------------------------------------
            */

            classSelect.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | All Classes
            |--------------------------------------------------------------------------
            */

            const allClassesOption =
                document.createElement('option');

            allClassesOption.value = '';

            allClassesOption.textContent =
                'All Classes';


            /*
            | If the existing fee structure has no class,
            | select All Classes.
            */

            if (
                selectedClassId === null ||
                selectedClassId === undefined ||
                selectedClassId === ''
            ) {

                allClassesOption.selected =
                    true;

            }


            classSelect.appendChild(
                allClassesOption
            );


            /*
            |--------------------------------------------------------------------------
            | Add Mapped Classes
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

                    option.selected =
                        true;

                }


                classSelect.appendChild(
                    option
                );

            });


            /*
            |--------------------------------------------------------------------------
            | Enable
            |--------------------------------------------------------------------------
            */

            classSelect.disabled =
                false;


            /*
            |--------------------------------------------------------------------------
            | Help
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
                'Fee Structure Edit - Load Classes:',
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


        if (termLoading) {

            termLoading.classList.remove(
                'd-none'
            );

        }


        try {

            const url =
                "{{ url('admin/fee-structures/academic-years') }}/"
                + encodeURIComponent(
                    academicYearId
                )
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
            | Rebuild Term Dropdown
            |--------------------------------------------------------------------------
            */

            termSelect.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | All Terms
            |--------------------------------------------------------------------------
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

                allTermsOption.selected =
                    true;

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

                    option.selected =
                        true;

                }


                termSelect.appendChild(
                    option
                );

            });


            /*
            |--------------------------------------------------------------------------
            | Enable
            |--------------------------------------------------------------------------
            */

            termSelect.disabled =
                false;


            /*
            |--------------------------------------------------------------------------
            | Help
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
                'Fee Structure Edit - Load Terms:',
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
    | Academic Year Changed
    |--------------------------------------------------------------------------
    */

    academicYearSelect.addEventListener(
        'change',
        function () {

            const academicYearId =
                this.value;


            /*
            |--------------------------------------------------------------------------
            | Clear current Class and Term.
            |--------------------------------------------------------------------------
            */

            resetClass();

            resetTerm();


            /*
            |--------------------------------------------------------------------------
            | No Academic Year
            |--------------------------------------------------------------------------
            */

            if (!academicYearId) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Load new Classes and Terms.
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
    | When editing an existing structure, immediately load:
    |
    | 1. Classes mapped to its Academic Year
    | 2. Terms belonging to its Academic Year
    |
    | Then automatically select the existing values.
    |
    */

    if (academicYearSelect.value) {

        loadClasses(
            academicYearSelect.value,
            initialClassId
        );


        loadTerms(
            academicYearSelect.value,
            initialTermId
        );

    } else {

        resetClass();

        resetTerm();

    }

});

</script>

@endsection