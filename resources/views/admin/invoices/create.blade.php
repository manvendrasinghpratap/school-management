@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Create Invoice
                </h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.invoices.index') }}"
                       class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Invoices
                    </a>
                </div>

            </div>

        </div>
    </div>


    {{-- ============================================================
         VALIDATION ERRORS
    ============================================================ --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================
         SUCCESS MESSAGE
    ============================================================ --}}
    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ============================================================
         INVOICE FORM
    ============================================================ --}}
    <form
        method="POST"
        action="{{ route('admin.invoices.store') }}"
        id="invoiceForm"
    >

        @csrf


        {{-- ========================================================
             STUDENT SELECTION
        ========================================================= --}}
        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">

                    <i class="bx bx-user me-1"></i>

                    Student Selection

                </h5>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- ==================================================
                         ACADEMIC YEAR
                    =================================================== --}}
                    <div class="col-md-3">

                        <div class="mb-3">

                            <label
                                for="academic_year_id"
                                class="form-label"
                            >

                                Academic Year

                                <span class="text-danger">*</span>

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


                                @foreach ($academicYears as $academicYear)

                                    <option
                                        value="{{ $academicYear->id }}"
                                        {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}
                                    >

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

                    </div>


                    {{-- ==================================================
                         CLASS
                    =================================================== --}}
                    <div class="col-md-3">

                        <div class="mb-3">

                            <label
                                for="class_id"
                                class="form-label"
                            >

                                Class

                                <span class="text-danger">*</span>

                            </label>


                            <select
                                name="class_id"
                                id="class_id"
                                class="form-select @error('class_id') is-invalid @enderror"
                                required
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

                        </div>

                    </div>


                    {{-- ==================================================
                         SECTION
                    =================================================== --}}
                    <div class="col-md-3">

                        <div class="mb-3">

                            <label
                                for="section_id"
                                class="form-label"
                            >

                                Section

                                <span class="text-danger">*</span>

                            </label>


                            <select
                                name="section_id"
                                id="section_id"
                                class="form-select @error('section_id') is-invalid @enderror"
                                required
                                disabled
                            >

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

                    </div>


                    {{-- ==================================================
                         STUDENT
                    =================================================== --}}
                    <div class="col-md-3">

                        <div class="mb-3">

                            <label
                                for="student_id"
                                class="form-label"
                            >

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
                                    Select Section First
                                </option>

                            </select>


                            @error('student_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================
             INVOICE INFORMATION
        ========================================================= --}}
        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">

                    <i class="bx bx-receipt me-1"></i>

                    Invoice Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Invoice Date --}}
                    <div class="col-md-4">

                        <div class="mb-3">

                            <label
                                for="invoice_date"
                                class="form-label"
                            >

                                Invoice Date

                                <span class="text-danger">*</span>

                            </label>


                            <input
                                type="date"
                                name="invoice_date"
                                id="invoice_date"
                                class="form-control"
                                value="{{ old('invoice_date', now()->format('Y-m-d')) }}"
                                required
                            >

                        </div>

                    </div>


                    {{-- Due Date --}}
                    <div class="col-md-4">

                        <div class="mb-3">

                            <label
                                for="due_date"
                                class="form-label"
                            >

                                Due Date

                            </label>


                            <input
                                type="date"
                                name="due_date"
                                id="due_date"
                                class="form-control"
                                value="{{ old('due_date') }}"
                            >

                        </div>

                    </div>


                    {{-- Student Fee Assignment --}}
                    <div class="col-md-4">

                        <div class="mb-3">

                            <label
                                for="student_fee_select"
                                class="form-label"
                            >

                                Student Fee Assignment

                            </label>


                            <select
                                id="student_fee_select"
                                class="form-select"
                                disabled
                            >

                                <option value="">
                                    Select student first
                                </option>

                            </select>


                            <small class="text-muted">

                                Select a fee assignment to add it as an
                                invoice item.

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================
             INVOICE ITEMS
        ========================================================= --}}
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">

                    <i class="bx bx-list-ul me-1"></i>

                    Invoice Items

                </h5>


                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    id="addManualItem"
                >

                    <i class="bx bx-plus me-1"></i>

                    Add Item

                </button>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-bordered align-middle"
                        id="invoiceItemsTable"
                    >

                        <thead>

                            <tr>

                                <th style="width: 20%;">
                                    Fee Category
                                </th>

                                <th style="width: 30%;">
                                    Description
                                </th>

                                <th style="width: 15%;">
                                    Quantity
                                </th>

                                <th style="width: 20%;">
                                    Amount
                                </th>

                                <th style="width: 10%;">
                                    Line Total
                                </th>

                                <th style="width: 5%;">
                                    #
                                </th>

                            </tr>

                        </thead>


                        <tbody id="invoiceItemsBody">

                            <tr class="empty-row">

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >

                                    No invoice items added yet.

                                    <br>

                                    Select a student fee assignment or click
                                    <strong>Add Item</strong>.

                                </td>

                            </tr>

                        </tbody>


                        <tfoot>

                            <tr>

                                <th
                                    colspan="4"
                                    class="text-end"
                                >
                                    Subtotal
                                </th>

                                <th colspan="2">

                                    ₹<span id="subtotalDisplay">
                                        0.00
                                    </span>

                                </th>

                            </tr>


                            <tr>

                                <th
                                    colspan="4"
                                    class="text-end"
                                >
                                    Discount
                                </th>

                                <th colspan="2">

                                    <input
                                        type="number"
                                        name="discount"
                                        id="discount"
                                        class="form-control"
                                        value="{{ old('discount', 0) }}"
                                        min="0"
                                        step="0.01"
                                    >

                                </th>

                            </tr>


                            <tr>

                                <th
                                    colspan="4"
                                    class="text-end"
                                >

                                    <strong>
                                        Total
                                    </strong>

                                </th>

                                <th colspan="2">

                                    <strong>

                                        ₹<span id="totalDisplay">
                                            0.00
                                        </span>

                                    </strong>

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>



        {{-- ========================================================
             PAYMENT INFORMATION
        ========================================================= --}}
        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">

                    <i class="bx bx-money me-1"></i>

                    Payment Status

                </h5>

            </div>


            <div class="card-body">

                <div class="alert alert-info mb-0">

                    <i class="bx bx-info-circle me-1"></i>

                    No payment will be recorded when creating the invoice.

                    The invoice will initially be created as
                    <strong>Unpaid</strong>
                    with the full amount outstanding.

                    Payments will be handled through the separate
                    <strong>Payment Management</strong>
                    module.

                </div>

            </div>

        </div>



        {{-- ========================================================
             ACTIONS
        ========================================================= --}}
        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.invoices.index') }}"
                        class="btn btn-light"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="saveInvoiceBtn"
                    >

                        <i class="bx bx-save me-1"></i>

                        Create Invoice

                    </button>

                </div>

            </div>

        </div>


    </form>

</div>

@endsection



@section('js')

<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    /* ================================================================
       ELEMENTS
    ================================================================= */
    const invoiceForm = document.getElementById('invoiceForm');
    const academicYearSelect = document.getElementById('academic_year_id');
    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section_id');
    const studentSelect = document.getElementById('student_id');
    const studentFeeSelect = document.getElementById('student_fee_select');
    const addManualItemButton = document.getElementById('addManualItem');
    const invoiceItemsBody = document.getElementById('invoiceItemsBody');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const discountInput = document.getElementById('discount');

    if (!academicYearSelect || !classSelect || !sectionSelect || !studentSelect ||
        !studentFeeSelect || !addManualItemButton || !invoiceItemsBody ||
        !subtotalDisplay || !totalDisplay || !discountInput) {
        console.error('Invoice create form: required elements are missing.');
        return;
    }

    /* ================================================================
       ROUTES
    ================================================================= */
    const filterClassesUrl = @json(route('admin.invoices.filter-classes'));
    const filterSectionsUrl = @json(route('admin.invoices.filter-sections'));
    const filterStudentsUrl = @json(route('admin.invoices.filter-students'));
    const filterStudentFeesUrl = @json(route('admin.invoices.filter-student-fees'));

    /* ================================================================
       DEFAULT ACADEMIC YEAR
       Old input wins after validation failure. Otherwise use a current
       year if the loaded collection exposes is_current, then fall back
       to the first active academic year.
    ================================================================= */
    const oldAcademicYearId = @json(old('academic_year_id'));
    const defaultAcademicYearId = @json(
        old('academic_year_id')
        ?: optional(
            $academicYears->first(function ($year) {
                return (bool) ($year->is_current ?? false);
            })
        )->id
        ?: optional($academicYears->first())->id
    );

    /* ================================================================
       REQUEST COUNTERS
       Prevent stale AJAX responses from overwriting newer selections.
    ================================================================= */
    let classRequestNumber = 0;
    let sectionRequestNumber = 0;
    let studentRequestNumber = 0;
    let feeRequestNumber = 0;

    /* ================================================================
       HELPERS
    ================================================================= */
    function resetSelect(select, placeholder, disabled = true) {
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        select.appendChild(option);
        select.value = '';
        select.disabled = disabled;
    }

    function appendOption(select, value, text) {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        select.appendChild(option);
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function normaliseArray(data, keys) {
        if (Array.isArray(data)) {
            return data;
        }
        for (const key of keys) {
            if (data && Array.isArray(data[key])) {
                return data[key];
            }
        }
        return [];
    }

    async function fetchJson(url) {
        const response = await fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            cache: 'no-store',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.redirected && response.url.includes('/login')) {
            throw new Error('Your session has expired. Please login again.');
        }

        if (!response.ok) {
            let message = 'HTTP error ' + response.status;
            try {
                const errorData = await response.json();
                if (errorData.message) {
                    message = errorData.message;
                } else if (errorData.errors) {
                    const firstError = Object.values(errorData.errors).flat()[0];
                    if (firstError) {
                        message = firstError;
                    }
                }
            } catch (e) {
                // Response was not JSON.
            }
            throw new Error(message);
        }

        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Expected JSON but received:', text.substring(0, 500));
            throw new Error('The server did not return JSON.');
        }

        return await response.json();
    }


    /* ================================================================
       INVOICE ITEM STATE
    ================================================================= */
    let itemIndex = 0;

    function getInvoiceRows() {
        return invoiceItemsBody.querySelectorAll('tr.invoice-item-row');
    }

    function updateEmptyInvoiceItemsRow() {
        const rows = getInvoiceRows();
        const emptyRow = invoiceItemsBody.querySelector('tr.empty-row');

        if (rows.length === 0) {
            if (!emptyRow) {
                invoiceItemsBody.innerHTML = `
                    <tr class="empty-row">
                        <td colspan="6" class="text-center text-muted py-4">
                            No invoice items added yet.
                            <br>
                            Select a student fee assignment or click
                            <strong>Add Item</strong>.
                        </td>
                    </tr>
                `;
            }
        } else if (emptyRow) {
            emptyRow.remove();
        }
    }

    function calculateInvoiceTotals() {
        let subtotal = 0;

        getInvoiceRows().forEach(function (row) {
            const quantityInput = row.querySelector('.item-quantity');
            const amountInput = row.querySelector('.item-amount');
            const lineTotalDisplay = row.querySelector('.line-total');

            const quantity = parseFloat(quantityInput?.value) || 0;
            const amount = parseFloat(amountInput?.value) || 0;
            const lineTotal = quantity * amount;

            if (lineTotalDisplay) {
                lineTotalDisplay.textContent = '₹' + lineTotal.toFixed(2);
            }

            subtotal += lineTotal;
        });

        const discount = Math.max(0, parseFloat(discountInput.value) || 0);
        const total = Math.max(0, subtotal - discount);

        subtotalDisplay.textContent = subtotal.toFixed(2);
        totalDisplay.textContent = total.toFixed(2);
    }

    function renumberInvoiceItems() {
        getInvoiceRows().forEach(function (row, index) {
            row.querySelectorAll('[data-item-field]').forEach(function (input) {
                const field = input.getAttribute('data-item-field');
                input.name = `items[${index}][${field}]`;
            });
        });
        itemIndex = getInvoiceRows().length;
    }

    function buildFeeCategoryOptions(selectedId = '') {
        const feeCategories = @json(
            $feeCategories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            })->values()
        );

        let html = '<option value="">Select Category</option>';

        feeCategories.forEach(function (category) {
            const selected = String(category.id) === String(selectedId) ? ' selected' : '';
            html += `<option value="${category.id}"${selected}>${escapeHtml(category.name)}</option>`;
        });

        return html;
    }

    function attachItemCalculationListeners(row) {
        const quantityInput = row.querySelector('.item-quantity');
        const amountInput = row.querySelector('.item-amount');

        if (quantityInput) {
            quantityInput.addEventListener('input', calculateInvoiceTotals);
            quantityInput.addEventListener('change', calculateInvoiceTotals);
        }

        if (amountInput) {
            amountInput.addEventListener('input', calculateInvoiceTotals);
            amountInput.addEventListener('change', calculateInvoiceTotals);
        }
    }

    function addInvoiceItem(item = {}) {
        updateEmptyInvoiceItemsRow();

        const row = document.createElement('tr');
        row.className = 'invoice-item-row';

        const currentIndex = itemIndex++;
        const feeCategoryId = item.fee_category_id ?? '';
        const description = item.description || item.fee_category || '';
        const quantity = item.quantity ?? 1;
        const amount = item.amount ?? item.net_assigned ?? 0;
        const studentFeeId = item.student_fee_id ?? '';

        row.innerHTML = `
            <td>
                <select
                    name="items[${currentIndex}][fee_category_id]"
                    data-item-field="fee_category_id"
                    class="form-select item-category"
                >
                    ${buildFeeCategoryOptions(feeCategoryId)}
                </select>
            </td>

            <td>
                <input
                    type="text"
                    name="items[${currentIndex}][description]"
                    data-item-field="description"
                    class="form-control item-description"
                    value="${escapeHtml(description)}"
                    maxlength="500"
                    required
                >
                <input
                    type="hidden"
                    name="items[${currentIndex}][student_fee_id]"
                    data-item-field="student_fee_id"
                    value="${escapeHtml(studentFeeId)}"
                    class="item-student-fee-id"
                >
            </td>

            <td>
                <input
                    type="number"
                    name="items[${currentIndex}][quantity]"
                    data-item-field="quantity"
                    class="form-control item-quantity"
                    value="${escapeHtml(quantity)}"
                    min="0.01"
                    step="0.01"
                    required
                >
            </td>

            <td>
                <input
                    type="number"
                    name="items[${currentIndex}][amount]"
                    data-item-field="amount"
                    class="form-control item-amount"
                    value="${escapeHtml(amount)}"
                    min="0.01"
                    step="0.01"
                    required
                >
            </td>

            <td>
                <span class="line-total">₹0.00</span>
            </td>

            <td class="text-center">
                <button
                    type="button"
                    class="btn btn-danger btn-sm remove-invoice-item"
                    title="Remove item"
                >
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;

        invoiceItemsBody.appendChild(row);
        attachItemCalculationListeners(row);
        renumberInvoiceItems();
        calculateInvoiceTotals();
    }

    /* ================================================================
       REMOVE ITEM
    ================================================================= */
    invoiceItemsBody.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-invoice-item');
        if (!button) {
            return;
        }

        const row = button.closest('tr.invoice-item-row');
        if (row) {
            row.remove();
        }

        renumberInvoiceItems();
        updateEmptyInvoiceItemsRow();
        calculateInvoiceTotals();
    });

    /* ================================================================
       ADD MANUAL ITEM
    ================================================================= */
    addManualItemButton.addEventListener('click', function (event) {
        event.preventDefault();
        addInvoiceItem({
            quantity: 1,
            amount: 0,
            description: '',
            fee_category_id: ''
        });
    });

    /* ================================================================
       LOAD CLASSES
       Academic Year -> Class
    ================================================================= */
    async function loadClasses(academicYearId, restoreOld = false) {
        const requestId = ++classRequestNumber;

        resetSelect(
            classSelect,
            academicYearId ? 'Loading classes...' : 'Select Academic Year First',
            true
        );
        resetSelect(sectionSelect, 'Select Class First', true);
        resetSelect(studentSelect, 'Select Section First', true);
        resetSelect(studentFeeSelect, 'Select Student First', true);

        if (!academicYearId) {
            return;
        }

        const url = filterClassesUrl + '?academic_year_id=' + encodeURIComponent(academicYearId);
        console.log('Loading classes:', url);

        try {
            const data = await fetchJson(url);

            if (requestId !== classRequestNumber) {
                return;
            }

            const classes = normaliseArray(data, ['classes', 'data']);
            resetSelect(classSelect, 'Select Class', true);

            if (classes.length === 0) {
                resetSelect(classSelect, 'No classes found for this academic year', true);
                console.warn('No classes found for academic year:', academicYearId);
                return;
            }

            classes.forEach(function (item) {
                const className = item.name || 'Class';
                const classCode = item.code ? ' (' + item.code + ')' : '';
                appendOption(classSelect, item.id, className + classCode);
            });

            classSelect.disabled = false;

            if (restoreOld) {
                const oldClassId = @json(old('class_id'));
                if (oldClassId && [...classSelect.options].some(option => String(option.value) === String(oldClassId))) {
                    classSelect.value = String(oldClassId);
                    await loadSections(oldClassId, true);
                }
            }
        } catch (error) {
            if (requestId !== classRequestNumber) {
                return;
            }
            console.error('Invoice class loading error:', error);
            resetSelect(classSelect, 'Unable to load classes', true);
            alert('Unable to load classes.\n\n' + error.message);
        }
    }

    /* ================================================================
       LOAD SECTIONS
       Class -> Section
    ================================================================= */
    async function loadSections(classId, restoreOld = false) {
        const requestId = ++sectionRequestNumber;

        resetSelect(sectionSelect, classId ? 'Loading sections...' : 'Select Class First', true);
        resetSelect(studentSelect, 'Select Section First', true);
        resetSelect(studentFeeSelect, 'Select Student First', true);

        if (!classId) {
            return;
        }

        const url = filterSectionsUrl + '?class_id=' + encodeURIComponent(classId);
        console.log('Loading sections:', url);

        try {
            const data = await fetchJson(url);

            if (requestId !== sectionRequestNumber) {
                return;
            }

            const sections = normaliseArray(data, ['sections', 'data']);
            resetSelect(sectionSelect, 'Select Section', true);

            if (sections.length === 0) {
                resetSelect(sectionSelect, 'No sections found', true);
                return;
            }

            sections.forEach(function (item) {
                const sectionName = item.name || 'Section';
                const sectionCode = item.code ? ' (' + item.code + ')' : '';
                appendOption(sectionSelect, item.id, sectionName + sectionCode);
            });

            sectionSelect.disabled = false;

            if (restoreOld) {
                const oldSectionId = @json(old('section_id'));
                if (oldSectionId && [...sectionSelect.options].some(option => String(option.value) === String(oldSectionId))) {
                    sectionSelect.value = String(oldSectionId);
                    await loadStudents(oldSectionId, true);
                }
            }
        } catch (error) {
            if (requestId !== sectionRequestNumber) {
                return;
            }
            console.error('Invoice section loading error:', error);
            resetSelect(sectionSelect, 'Unable to load sections', true);
            alert('Unable to load sections.\n\n' + error.message);
        }
    }

    /* ================================================================
       LOAD STUDENTS
       Academic Year -> Class -> Section -> Student
    ================================================================= */
    async function loadStudents(sectionId, restoreOld = false) {
        const requestId = ++studentRequestNumber;
        const academicYearId = academicYearSelect.value;
        const classId = classSelect.value;

        resetSelect(studentSelect, sectionId ? 'Loading students...' : 'Select Section First', true);
        resetSelect(studentFeeSelect, 'Select Student First', true);

        if (!academicYearId || !classId || !sectionId) {
            return;
        }

        const url = filterStudentsUrl +
            '?academic_year_id=' + encodeURIComponent(academicYearId) +
            '&class_id=' + encodeURIComponent(classId) +
            '&section_id=' + encodeURIComponent(sectionId);

        console.log('Loading students:', url);

        try {
            const data = await fetchJson(url);

            if (requestId !== studentRequestNumber) {
                return;
            }

            const students = normaliseArray(data, ['students', 'data']);
            resetSelect(studentSelect, 'Select Student', true);

            if (students.length === 0) {
                resetSelect(studentSelect, 'No students found', true);
                return;
            }

            students.forEach(function (student) {
                const fullName = [
                    student.first_name,
                    student.middle_name,
                    student.last_name
                ].filter(Boolean).join(' ');

                const studentNumber = student.student_number
                    ? ' — ' + student.student_number
                    : '';

                appendOption(studentSelect, student.id, fullName + studentNumber);
            });

            studentSelect.disabled = false;

            if (restoreOld) {
                const oldStudentId = @json(old('student_id'));
                if (oldStudentId && [...studentSelect.options].some(option => String(option.value) === String(oldStudentId))) {
                    studentSelect.value = String(oldStudentId);
                    await loadStudentFees(oldStudentId, true);
                }
            }
        } catch (error) {
            if (requestId !== studentRequestNumber) {
                return;
            }
            console.error('Invoice student loading error:', error);
            resetSelect(studentSelect, 'Unable to load students', true);
            alert('Unable to load students.\n\n' + error.message);
        }
    }

    /* ================================================================
       LOAD STUDENT FEE ASSIGNMENTS
    ================================================================= */
    async function loadStudentFees(studentId, restoreOld = false) {
        const requestId = ++feeRequestNumber;

        resetSelect(
            studentFeeSelect,
            studentId ? 'Loading fee assignments...' : 'Select Student First',
            true
        );

        if (!studentId) {
            return;
        }

        const url = filterStudentFeesUrl + '?student_id=' + encodeURIComponent(studentId);
        console.log('Loading student fees:', url);

        try {
            const data = await fetchJson(url);

            if (requestId !== feeRequestNumber) {
                return;
            }

            const fees = normaliseArray(data, ['student_fees', 'fees', 'data']);
            resetSelect(studentFeeSelect, 'Select Student Fee', true);

            if (fees.length === 0) {
                resetSelect(studentFeeSelect, 'No pending fee assignments', true);
                return;
            }

            fees.forEach(function (fee) {
                const category = fee.fee_category || fee.description || 'Fee';
                const amount = Number(fee.net_assigned || 0).toFixed(2);
                const status = fee.status ? ' (' + fee.status + ')' : '';

                const option = document.createElement('option');
                option.value = fee.id;
                option.textContent = category + ' — ₹' + amount + status;
                option.dataset.fee = JSON.stringify(fee);
                studentFeeSelect.appendChild(option);
            });

            studentFeeSelect.disabled = false;

            if (restoreOld) {
                const oldStudentFeeId = @json(old('student_fee_id'));
                if (oldStudentFeeId && [...studentFeeSelect.options].some(option => String(option.value) === String(oldStudentFeeId))) {
                    studentFeeSelect.value = String(oldStudentFeeId);
                }
            }
        } catch (error) {
            if (requestId !== feeRequestNumber) {
                return;
            }
            console.error('Invoice fee loading error:', error);
            resetSelect(studentFeeSelect, 'Unable to load fee assignments', true);
            alert('Unable to load student fee assignments.\n\n' + error.message);
        }
    }

    /* ================================================================
       ADD SELECTED STUDENT FEE AS INVOICE ITEM
    ================================================================= */
    studentFeeSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            return;
        }

        let fee = null;

        try {
            fee = JSON.parse(selectedOption.dataset.fee || '{}');
        } catch (error) {
            console.error('Unable to read student fee data:', error);
            alert('Unable to read the selected student fee assignment.');
            return;
        }

        if (!fee.id) {
            alert('Invalid student fee assignment.');
            return;
        }

        const duplicate = [...getInvoiceRows()].some(function (row) {
            const hidden = row.querySelector('.item-student-fee-id');
            return hidden && String(hidden.value) === String(fee.id);
        });

        if (duplicate) {
            alert('This student fee assignment has already been added to the invoice.');
            this.value = '';
            return;
        }

        addInvoiceItem({
            student_fee_id: fee.id,
            fee_category_id: fee.fee_category_id || '',
            description: fee.description || fee.fee_category || 'Fee',
            quantity: 1,
            amount: Number(fee.net_assigned || 0).toFixed(2)
        });

        this.value = '';
    });

    /* ================================================================
       DISCOUNT
    ================================================================= */
    discountInput.addEventListener('input', calculateInvoiceTotals);
    discountInput.addEventListener('change', calculateInvoiceTotals);

    /* ================================================================
       HIERARCHY EVENTS
    ================================================================= */
    academicYearSelect.addEventListener('change', function () {
        loadClasses(this.value, false);
    });

    classSelect.addEventListener('change', function () {
        loadSections(this.value, false);
    });

    sectionSelect.addEventListener('change', function () {
        loadStudents(this.value, false);
    });

    studentSelect.addEventListener('change', function () {
        loadStudentFees(this.value, false);
    });

    /* ================================================================
       FORM VALIDATION
       Prevent submission without at least one valid invoice item.
    ================================================================= */
    invoiceForm.addEventListener('submit', function (event) {
        renumberInvoiceItems();
        calculateInvoiceTotals();

        const rows = getInvoiceRows();

        if (rows.length === 0) {
            event.preventDefault();
            alert('Please add at least one invoice item before creating the invoice.');
            return;
        }

        let invalid = false;

        rows.forEach(function (row) {
            const description = row.querySelector('.item-description');
            const quantity = parseFloat(row.querySelector('.item-quantity')?.value || 0);
            const amount = parseFloat(row.querySelector('.item-amount')?.value || 0);

            if (!description || !description.value.trim() || quantity <= 0 || amount <= 0) {
                invalid = true;
            }
        });

        if (invalid) {
            event.preventDefault();
            alert('Please enter a valid description, quantity and amount for every invoice item.');
        }
    });

    /* ================================================================
       INITIAL PAGE LOAD
    ================================================================= */
    if (!academicYearSelect.value && defaultAcademicYearId) {
        academicYearSelect.value = String(defaultAcademicYearId);
    }

    updateEmptyInvoiceItemsRow();
    calculateInvoiceTotals();

    if (academicYearSelect.value) {
        loadClasses(academicYearSelect.value, Boolean(oldAcademicYearId));
    }
});
</script>

@endsection
