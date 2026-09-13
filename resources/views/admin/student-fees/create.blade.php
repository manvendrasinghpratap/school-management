@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-flex align-items-center justify-content-between">

                <h4 class="mb-0">
                    Assign Fee to Student
                </h4>

                <a href="{{ route('admin.student-fees.index') }}"
                   class="btn btn-light">
                    <i class="bx bx-arrow-back me-1"></i>
                    Back to Student Fees
                </a>

            </div>

        </div>
    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Please correct the following errors:</strong>

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


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bx bx-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bx bx-error-circle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row">

        {{-- ============================================================
             FORM
        ============================================================= --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">
                        Fee Assignment Details
                    </h4>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.student-fees.store') }}"
                          id="studentFeeForm">

                        @csrf


                        {{-- =================================================
                             1. ACADEMIC YEAR
                        ================================================== --}}
                        <div class="mb-3">

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

                                @foreach($academicYears as $academicYear)

                                    <option value="{{ $academicYear->id }}"
                                        {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}>

                                        {{ $academicYear->name }}

                                    </option>

                                @endforeach

                            </select>


                            @error('academic_year_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             2. CLASS
                        ================================================== --}}
                        <div class="mb-3">

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
                                    Select Academic Year First
                                </option>

                            </select>


                            @error('class_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             3. SECTION
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="section_id"
                                   class="form-label">

                                Section

                                <span class="text-danger">*</span>

                            </label>


                            <select name="section_id"
                                    id="section_id"
                                    class="form-select @error('section_id') is-invalid @enderror"
                                    disabled
                                    required>

                                <option value="">
                                    Select Class First
                                </option>

                            </select>


                            @error('section_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             4. STUDENT
                        ================================================== --}}
                        <div class="mb-3">

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
                                    Select Section First
                                </option>

                            </select>


                            @error('student_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror


                            <small class="text-muted">

                                Only students actively enrolled in the selected
                                academic year, class and section will appear.

                            </small>

                        </div>


                        {{-- =================================================
                             ACADEMIC SUMMARY
                        ================================================== --}}
                        <div id="academicSelectionSummary"
                             class="alert alert-light border d-none mb-4">

                            <h6 class="mb-3">

                                <i class="bx bx-sitemap me-1"></i>

                                Academic Placement

                            </h6>


                            <div class="row">

                                <div class="col-md-4">

                                    <small class="text-muted d-block">
                                        Academic Year
                                    </small>

                                    <strong id="summaryAcademicYear">
                                        —
                                    </strong>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted d-block">
                                        Class
                                    </small>

                                    <strong id="summaryClass">
                                        —
                                    </strong>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted d-block">
                                        Section
                                    </small>

                                    <strong id="summarySection">
                                        —
                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             5. FEE STRUCTURE
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="fee_structure_id"
                                   class="form-label">

                                Fee Structure

                                <span class="text-danger">*</span>

                            </label>


                            <select name="fee_structure_id"
                                    id="fee_structure_id"
                                    class="form-select @error('fee_structure_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Fee Structure
                                </option>

                                @foreach($feeStructures as $feeStructure)

                                    <option value="{{ $feeStructure->id }}"
                                            data-academic-year="{{ $feeStructure->academic_year_id }}"
                                            data-class="{{ $feeStructure->class_id }}"
                                            data-amount="{{ $feeStructure->amount }}">

                                        {{ $feeStructure->feeCategory?->name ?? 'N/A' }}

                                        —

                                        {{ $feeStructure->academicYear?->name ?? 'N/A' }}

                                        @if($feeStructure->classModel)

                                            —

                                            {{ $feeStructure->classModel->name }}

                                        @else

                                            — All Classes

                                        @endif

                                        @if($feeStructure->term)

                                            —

                                            {{ $feeStructure->term->name }}

                                        @else

                                            — All Terms

                                        @endif

                                        —

                                        ₹{{ number_format((float) $feeStructure->amount, 2) }}

                                    </option>

                                @endforeach

                            </select>


                            @error('fee_structure_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             FEE STRUCTURE AMOUNT
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="structure_amount"
                                   class="form-label">

                                Fee Structure Amount

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="text"
                                       id="structure_amount"
                                       class="form-control"
                                       readonly
                                       placeholder="Select fee structure">

                            </div>

                        </div>


                        {{-- =================================================
                             6. SCHOLARSHIP
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="scholarship_id"
                                   class="form-label">

                                Scholarship

                            </label>


                            <select name="scholarship_id"
                                    id="scholarship_id"
                                    class="form-select @error('scholarship_id') is-invalid @enderror">

                                <option value="">
                                    No Scholarship
                                </option>

                                @foreach($scholarships as $scholarship)

                                    <option value="{{ $scholarship->id }}"
                                            data-type="{{ $scholarship->type }}"
                                            data-value="{{ $scholarship->value }}">

                                        {{ $scholarship->name }}

                                        —

                                        {{ ucfirst($scholarship->type) }}

                                        —

                                        {{ number_format((float) $scholarship->value, 2) }}

                                    </option>

                                @endforeach

                            </select>


                            @error('scholarship_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             7. ASSIGNED AMOUNT
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="amount"
                                   class="form-label">

                                Assigned Amount

                                <span class="text-danger">*</span>

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="number"
                                       name="amount"
                                       id="amount"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}"
                                       min="0.01"
                                       step="0.01"
                                       placeholder="Enter assigned amount"
                                       required>

                            </div>


                            <small class="text-muted">

                                Normally this should match the fee structure
                                amount. A lower amount can be used for an
                                authorized adjustment or mid-year arrangement.

                            </small>


                            @error('amount')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             8. DISCOUNT
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="discount"
                                   class="form-label">

                                Discount

                                <span class="text-danger">*</span>

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="number"
                                       name="discount"
                                       id="discount"
                                       class="form-control @error('discount') is-invalid @enderror"
                                       value="{{ old('discount', '0.00') }}"
                                       min="0"
                                       step="0.01"
                                       required>

                            </div>


                            @error('discount')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                             PAYMENT SUMMARY
                        ================================================== --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Payment Summary
                            </label>


                            <div class="alert alert-info mb-0">

                                <div class="d-flex justify-content-between mb-2">

                                    <span>
                                        Assigned Amount
                                    </span>

                                    <strong>

                                        ₹
                                        <span id="preview_amount">
                                            0.00
                                        </span>

                                    </strong>

                                </div>


                                <div class="d-flex justify-content-between mb-2">

                                    <span>
                                        Discount
                                    </span>

                                    <strong class="text-danger">

                                        − ₹
                                        <span id="preview_discount">
                                            0.00
                                        </span>

                                    </strong>

                                </div>


                                <hr>


                                <div class="d-flex justify-content-between">

                                    <strong>
                                        Net Payable
                                    </strong>

                                    <strong class="text-primary fs-5">

                                        ₹
                                        <span id="preview_net">
                                            0.00
                                        </span>

                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             BUTTONS
                        ================================================== --}}
                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bx bx-save me-1"></i>

                                Assign Fee

                            </button>


                            <a href="{{ route('admin.student-fees.index') }}"
                               class="btn btn-light">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- ============================================================
             INFORMATION
        ============================================================= --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">
                        Fee Assignment
                    </h4>

                </div>


                <div class="card-body">

                    <div class="alert alert-light">

                        <h6>

                            <i class="bx bx-sitemap me-1"></i>

                            Selection Order

                        </h6>


                        <ol class="mb-0">

                            <li>Academic Year</li>

                            <li>Class</li>

                            <li>Section</li>

                            <li>Student</li>

                            <li>Fee Structure</li>

                            <li>Scholarship</li>

                            <li>Amount</li>

                            <li>Discount</li>

                        </ol>

                    </div>


                    <div class="alert alert-light">

                        <h6>

                            <i class="bx bx-receipt me-1"></i>

                            Fee Structure

                        </h6>

                        <p class="mb-0">

                            The fee structure defines the standard amount
                            applicable to the selected academic setup.

                        </p>

                    </div>


                    <div class="alert alert-light">

                        <h6>

                            <i class="bx bx-money me-1"></i>

                            Discount

                        </h6>

                        <p class="mb-0">

                            The discount reduces the student's net payable
                            amount without changing the original fee structure.

                        </p>

                    </div>


                    <div class="alert alert-warning mb-0">

                        <h6>

                            <i class="bx bx-lock me-1"></i>

                            Financial Protection

                        </h6>

                        <p class="mb-0">

                            Paid and partially paid assignments will be protected
                            from normal editing.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const academicYearSelect =
        document.getElementById('academic_year_id');

    const classSelect =
        document.getElementById('class_id');

    const sectionSelect =
        document.getElementById('section_id');

    const studentSelect =
        document.getElementById('student_id');

    const feeStructureSelect =
        document.getElementById('fee_structure_id');

    const structureAmount =
        document.getElementById('structure_amount');

    const amountInput =
        document.getElementById('amount');

    const discountInput =
        document.getElementById('discount');

    const previewAmount =
        document.getElementById('preview_amount');

    const previewDiscount =
        document.getElementById('preview_discount');

    const previewNet =
        document.getElementById('preview_net');

    const summaryBox =
        document.getElementById('academicSelectionSummary');

    const summaryAcademicYear =
        document.getElementById('summaryAcademicYear');

    const summaryClass =
        document.getElementById('summaryClass');

    const summarySection =
        document.getElementById('summarySection');


    /*
    |--------------------------------------------------------------------------
    | ROUTES
    |--------------------------------------------------------------------------
    */

    const filterClassesUrl =
        @json(route('admin.student-fees.filter-classes'));

    const filterSectionsUrl =
        @json(route('admin.student-fees.filter-sections'));

    const filterStudentsUrl =
        @json(route('admin.student-fees.filter-students'));


    /*
    |--------------------------------------------------------------------------
    | RESET CLASS
    |--------------------------------------------------------------------------
    */

    function resetClasses(message) {

        classSelect.innerHTML =
            '<option value="">' +
            message +
            '</option>';

        classSelect.disabled = true;

    }


    /*
    |--------------------------------------------------------------------------
    | RESET SECTION
    |--------------------------------------------------------------------------
    */

    function resetSections(message) {

        sectionSelect.innerHTML =
            '<option value="">' +
            message +
            '</option>';

        sectionSelect.disabled = true;

    }


    /*
    |--------------------------------------------------------------------------
    | RESET STUDENT
    |--------------------------------------------------------------------------
    */

    function resetStudents(message) {

        studentSelect.innerHTML =
            '<option value="">' +
            message +
            '</option>';

        studentSelect.disabled = true;

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD CLASSES BY ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */

    async function loadClasses() {

        const academicYearId =
            academicYearSelect.value;


        resetClasses(
            academicYearId
                ? 'Loading Classes...'
                : 'Select Academic Year First'
        );

        resetSections('Select Class First');

        resetStudents('Select Section First');

        summaryBox.classList.add('d-none');


        if (!academicYearId) {

            return;

        }


        try {

            const url =
                filterClassesUrl +
                '?academic_year_id=' +
                encodeURIComponent(academicYearId);


            const response =
                await fetch(
                    url,
                    {
                        method: 'GET',

                        headers: {
                            'X-Requested-With':
                                'XMLHttpRequest',

                            'Accept':
                                'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to load classes.'
                );

            }


            const data =
                await response.json();


            const classes =
                data.classes || [];


            classSelect.innerHTML =
                '<option value="">Select Class</option>';


            classes.forEach(function (item) {

                const option =
                    document.createElement('option');


                option.value =
                    item.id;


                option.textContent =
                    item.code
                        ? item.name +
                          ' (' +
                          item.code +
                          ')'
                        : item.name;


                classSelect.appendChild(option);

            });


            if (classes.length === 0) {

                classSelect.innerHTML =
                    '<option value="">No Classes Available</option>';

                classSelect.disabled = true;

                return;

            }


            classSelect.disabled = false;


        } catch (error) {

            console.error(error);


            classSelect.innerHTML =
                '<option value="">Unable to Load Classes</option>';

            classSelect.disabled = true;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD SECTIONS BY CLASS
    |--------------------------------------------------------------------------
    */

    async function loadSections() {

        const classId =
            classSelect.value;


        resetSections(
            classId
                ? 'Loading Sections...'
                : 'Select Class First'
        );

        resetStudents('Select Section First');

        summaryBox.classList.add('d-none');


        if (!classId) {

            return;

        }


        try {

            const url =
                filterSectionsUrl +
                '?class_id=' +
                encodeURIComponent(classId);


            const response =
                await fetch(
                    url,
                    {
                        method: 'GET',

                        headers: {
                            'X-Requested-With':
                                'XMLHttpRequest',

                            'Accept':
                                'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to load sections.'
                );

            }


            const data =
                await response.json();


            const sections =
                data.sections || [];


            sectionSelect.innerHTML =
                '<option value="">Select Section</option>';


            sections.forEach(function (section) {

                const option =
                    document.createElement('option');


                option.value =
                    section.id;


                option.textContent =
                    section.code
                        ? section.name +
                          ' (' +
                          section.code +
                          ')'
                        : section.name;


                sectionSelect.appendChild(option);

            });


            if (sections.length === 0) {

                sectionSelect.innerHTML =
                    '<option value="">No Sections Available</option>';

                sectionSelect.disabled = true;

                return;

            }


            sectionSelect.disabled = false;


        } catch (error) {

            console.error(error);


            sectionSelect.innerHTML =
                '<option value="">Unable to Load Sections</option>';

            sectionSelect.disabled = true;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD STUDENTS
    |--------------------------------------------------------------------------
    */

    async function loadStudents() {

        const academicYearId =
            academicYearSelect.value;

        const classId =
            classSelect.value;

        const sectionId =
            sectionSelect.value;


        resetStudents(
            sectionId
                ? 'Loading Students...'
                : 'Select Section First'
        );


        summaryBox.classList.add('d-none');


        if (
            !academicYearId ||
            !classId ||
            !sectionId
        ) {

            return;

        }


        try {

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

            params.append(
                'section_id',
                sectionId
            );


            const response =
                await fetch(
                    filterStudentsUrl +
                    '?' +
                    params.toString(),
                    {
                        method: 'GET',

                        headers: {
                            'X-Requested-With':
                                'XMLHttpRequest',

                            'Accept':
                                'application/json'
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
                data.students || [];


            studentSelect.innerHTML =
                '<option value="">Select Student</option>';


            students.forEach(function (student) {

                const option =
                    document.createElement('option');


                option.value =
                    student.id;


                const name = [

                    student.first_name,

                    student.middle_name,

                    student.last_name

                ]
                .filter(Boolean)
                .join(' ');


                if (student.student_number) {

                    option.textContent =
                        name +
                        ' — ' +
                        student.student_number;

                } else {

                    option.textContent =
                        name;

                }


                studentSelect.appendChild(option);

            });


            if (students.length === 0) {

                studentSelect.innerHTML =
                    '<option value="">No Students Available</option>';

                studentSelect.disabled = true;

            } else {

                studentSelect.disabled = false;

            }


            /*
            |--------------------------------------------------------------------------
            | SUMMARY
            |--------------------------------------------------------------------------
            */

            const academicYearOption =
                academicYearSelect.options[
                    academicYearSelect.selectedIndex
                ];

            const classOption =
                classSelect.options[
                    classSelect.selectedIndex
                ];

            const sectionOption =
                sectionSelect.options[
                    sectionSelect.selectedIndex
                ];


            summaryAcademicYear.textContent =
                academicYearOption
                    ? academicYearOption.textContent.trim()
                    : '—';


            summaryClass.textContent =
                classOption
                    ? classOption.textContent.trim()
                    : '—';


            summarySection.textContent =
                sectionOption
                    ? sectionOption.textContent.trim()
                    : '—';


            summaryBox.classList.remove('d-none');


        } catch (error) {

            console.error(error);


            studentSelect.innerHTML =
                '<option value="">Unable to Load Students</option>';

            studentSelect.disabled = true;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE FEE STRUCTURE AMOUNT
    |--------------------------------------------------------------------------
    */

    function updateStructureAmount() {

        const option =
            feeStructureSelect.options[
                feeStructureSelect.selectedIndex
            ];


        if (
            !option ||
            !option.value
        ) {

            structureAmount.value = '';

            return;

        }


        const amount =
            parseFloat(
                option.dataset.amount
            ) || 0;


        structureAmount.value =
            amount.toFixed(2);


        /*
        |--------------------------------------------------------------------------
        | Automatically populate assigned amount
        |--------------------------------------------------------------------------
        */

        if (!amountInput.value) {

            amountInput.value =
                amount.toFixed(2);

        }


        updateNetPayable();

    }


    /*
    |--------------------------------------------------------------------------
    | NET PAYABLE
    |--------------------------------------------------------------------------
    */

    function updateNetPayable() {

        const amount =
            parseFloat(
                amountInput.value
            ) || 0;


        const discount =
            parseFloat(
                discountInput.value
            ) || 0;


        const net =
            Math.max(
                0,
                amount - discount
            );


        previewAmount.textContent =
            amount.toFixed(2);


        previewDiscount.textContent =
            discount.toFixed(2);


        previewNet.textContent =
            net.toFixed(2);

    }


    /*
    |--------------------------------------------------------------------------
    | EVENTS
    |--------------------------------------------------------------------------
    */

    academicYearSelect.addEventListener(
        'change',
        function () {

            loadClasses();

        }
    );


    classSelect.addEventListener(
        'change',
        function () {

            loadSections();

        }
    );


    sectionSelect.addEventListener(
        'change',
        function () {

            loadStudents();

        }
    );


    feeStructureSelect.addEventListener(
        'change',
        function () {

            updateStructureAmount();

        }
    );


    amountInput.addEventListener(
        'input',
        function () {

            updateNetPayable();

        }
    );


    discountInput.addEventListener(
        'input',
        function () {

            updateNetPayable();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL STATE
    |--------------------------------------------------------------------------
    */

    resetClasses(
        'Select Academic Year First'
    );

    resetSections(
        'Select Class First'
    );

    resetStudents(
        'Select Section First'
    );

    updateStructureAmount();

    updateNetPayable();

});

</script>

@endsection