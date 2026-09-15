@extends('backend.layout.default')

@section('title', 'Collect Payment')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Collect Payment</h4>

                <div class="page-title-right">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Payments
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
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

    <form method="POST"
          action="{{ route('admin.payments.store') }}"
          id="paymentForm">

        @csrf

        {{-- ============================= --}}
        {{-- STUDENT SELECTION              --}}
        {{-- ============================= --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-user me-1"></i>
                    Student Selection
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Academic Year --}}
                    <div class="col-md-3 mb-3">
                        <label for="academic_year_id" class="form-label">
                            Academic Year <span class="text-danger">*</span>
                        </label>

                        <select
                            name="academic_year_id"
                            id="academic_year_id"
                            class="form-select"
                        >
                            <option value="">Select Academic Year</option>

                            @foreach (
                                \App\Models\AcademicYears::where('school_id', auth()->user()->school_id)
                                    ->where('is_active', true)
                                    ->orderByDesc('id')
                                    ->get()
                                as $academicYear
                            )
                                <option
                                    value="{{ $academicYear->id }}"
                                    {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}
                                >
                                    {{ $academicYear->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('academic_year_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Class --}}
                    <div class="col-md-3 mb-3">
                        <label for="class_id" class="form-label">
                            Class <span class="text-danger">*</span>
                        </label>

                        <select
                            name="class_id"
                            id="class_id"
                            class="form-select"
                            disabled
                        >
                            <option value="">Select Class</option>
                        </select>
                    </div>

                    {{-- Section --}}
                    <div class="col-md-3 mb-3">
                        <label for="section_id" class="form-label">
                            Section <span class="text-danger">*</span>
                        </label>

                        <select
                            name="section_id"
                            id="section_id"
                            class="form-select"
                            disabled
                        >
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    {{-- Student --}}
                    <div class="col-md-3 mb-3">
                        <label for="student_id" class="form-label">
                            Student <span class="text-danger">*</span>
                        </label>

                        <select
                            name="student_id"
                            id="student_id"
                            class="form-select"
                            disabled
                        >
                            <option value="">Select Student</option>
                        </select>
                    </div>

                </div>

            </div>
        </div>


        {{-- ============================= --}}
        {{-- INVOICE SELECTION              --}}
        {{-- ============================= --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-receipt me-1"></i>
                    Invoice
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="invoice_id" class="form-label">
                            Invoice <span class="text-danger">*</span>
                        </label>

                        <select
                            name="invoice_id"
                            id="invoice_id"
                            class="form-select"
                            disabled
                        >
                            <option value="">Select Invoice</option>
                        </select>

                        <div class="form-text">
                            Only active invoices with an outstanding balance are available.
                        </div>

                        @error('invoice_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                {{-- Invoice Summary --}}
                <div id="invoiceSummary" class="d-none">

                    <div class="alert alert-light border">

                        <div class="row">

                            <div class="col-md-2 mb-3">
                                <small class="text-muted d-block">
                                    Invoice Number
                                </small>
                                <strong id="invoiceNumber">-</strong>
                            </div>

                            <div class="col-md-2 mb-3">
                                <small class="text-muted d-block">
                                    Invoice Date
                                </small>
                                <strong id="invoiceDate">-</strong>
                            </div>

                            <div class="col-md-2 mb-3">
                                <small class="text-muted d-block">
                                    Due Date
                                </small>
                                <strong id="invoiceDueDate">-</strong>
                            </div>

                            <div class="col-md-2 mb-3">
                                <small class="text-muted d-block">
                                    Total
                                </small>
                                <strong id="invoiceTotal">₹0.00</strong>
                            </div>

                            <div class="col-md-2 mb-3">
                                <small class="text-muted d-block">
                                    Paid
                                </small>
                                <strong id="invoicePaid">₹0.00</strong>
                            </div>

                            <div class="col-md-2 mb-3">
                                <small class="text-muted d-block">
                                    Balance
                                </small>
                                <strong id="invoiceBalance">₹0.00</strong>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>


        {{-- ============================= --}}
        {{-- PAYMENT INFORMATION            --}}
        {{-- ============================= --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-money me-1"></i>
                    Payment Information
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Payment Amount --}}
                    <div class="col-md-4 mb-3">
                        <label for="amount" class="form-label">
                            Payment Amount <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">₹</span>

                            <input
                                type="number"
                                name="amount"
                                id="amount"
                                class="form-control"
                                value="{{ old('amount') }}"
                                min="0.01"
                                step="0.01"
                                placeholder="0.00"
                                disabled
                            >
                        </div>

                        <div class="form-text">
                            Maximum payment is the outstanding invoice balance.
                        </div>

                        @error('amount')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Payment Method --}}
                    <div class="col-md-4 mb-3">
                        <label for="payment_method" class="form-label">
                            Payment Method <span class="text-danger">*</span>
                        </label>

                        <select
                            name="payment_method"
                            id="payment_method"
                            class="form-select"
                            disabled
                        >
                            <option value="">Select Payment Method</option>

                            <option value="cash"
                                {{ old('payment_method') === 'cash' ? 'selected' : '' }}>
                                Cash
                            </option>

                            <option value="bank_transfer"
                                {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer
                            </option>

                            <option value="card"
                                {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                                Card
                            </option>

                            <option value="online"
                                {{ old('payment_method') === 'online' ? 'selected' : '' }}>
                                Online
                            </option>

                            <option value="mobile_money"
                                {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>
                                Mobile Money
                            </option>

                            <option value="other"
                                {{ old('payment_method') === 'other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>

                        @error('payment_method')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Paid At --}}
                    <div class="col-md-4 mb-3">
                        <label for="paid_at" class="form-label">
                            Paid At <span class="text-danger">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            name="paid_at"
                            id="paid_at"
                            class="form-control"
                            value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}"
                            disabled
                        >

                        @error('paid_at')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Transaction Reference --}}
                    <div class="col-md-6 mb-3">
                        <label for="transaction_reference" class="form-label">
                            Transaction Reference
                        </label>

                        <input
                            type="text"
                            name="transaction_reference"
                            id="transaction_reference"
                            class="form-control"
                            value="{{ old('transaction_reference') }}"
                            maxlength="255"
                            placeholder="Bank / online transaction reference"
                            disabled
                        >

                        @error('transaction_reference')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Notes --}}
                    <div class="col-md-6 mb-3">
                        <label for="notes" class="form-label">
                            Notes
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            class="form-control"
                            rows="3"
                            maxlength="2000"
                            placeholder="Optional payment notes"
                            disabled
                        >{{ old('notes') }}</textarea>

                        @error('notes')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>
        </div>


        {{-- ============================= --}}
        {{-- ACTIONS                        --}}
        {{-- ============================= --}}
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.payments.index') }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="savePaymentBtn"
                        disabled
                    >
                        <i class="bx bx-money me-1"></i>
                        Record Payment
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

    const academicYearSelect = document.getElementById('academic_year_id');
    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section_id');
    const studentSelect = document.getElementById('student_id');
    const invoiceSelect = document.getElementById('invoice_id');

    const invoiceSummary = document.getElementById('invoiceSummary');

    const invoiceNumber = document.getElementById('invoiceNumber');
    const invoiceDate = document.getElementById('invoiceDate');
    const invoiceDueDate = document.getElementById('invoiceDueDate');
    const invoiceTotal = document.getElementById('invoiceTotal');
    const invoicePaid = document.getElementById('invoicePaid');
    const invoiceBalance = document.getElementById('invoiceBalance');

    const amountInput = document.getElementById('amount');
    const paymentMethodSelect = document.getElementById('payment_method');
    const paidAtInput = document.getElementById('paid_at');
    const transactionReferenceInput = document.getElementById('transaction_reference');
    const notesInput = document.getElementById('notes');
    const savePaymentBtn = document.getElementById('savePaymentBtn');


    function resetSelect(select, placeholder) {
        select.innerHTML = '';

        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;

        select.appendChild(option);
        select.disabled = true;
    }


    function enablePaymentFields() {
        amountInput.disabled = false;
        paymentMethodSelect.disabled = false;
        paidAtInput.disabled = false;
        transactionReferenceInput.disabled = false;
        notesInput.disabled = false;
        savePaymentBtn.disabled = false;
    }


    function disablePaymentFields() {
        amountInput.disabled = true;
        paymentMethodSelect.disabled = true;
        paidAtInput.disabled = true;
        transactionReferenceInput.disabled = true;
        notesInput.disabled = true;
        savePaymentBtn.disabled = true;
    }


    function resetInvoiceSummary() {
        invoiceSummary.classList.add('d-none');

        invoiceNumber.textContent = '-';
        invoiceDate.textContent = '-';
        invoiceDueDate.textContent = '-';
        invoiceTotal.textContent = '₹0.00';
        invoicePaid.textContent = '₹0.00';
        invoiceBalance.textContent = '₹0.00';

        disablePaymentFields();
    }


    function formatMoney(value) {
        return '₹' + Number(value || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }


    async function fetchJson(url) {

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Request failed with status ' + response.status);
        }

        return await response.json();
    }


    /*
     * Academic Year → Class
     */
    academicYearSelect.addEventListener('change', async function () {

        resetSelect(classSelect, 'Loading classes...');
        resetSelect(sectionSelect, 'Select Section');
        resetSelect(studentSelect, 'Select Student');
        resetSelect(invoiceSelect, 'Select Invoice');
        resetInvoiceSummary();

        if (!this.value) {
            resetSelect(classSelect, 'Select Class');
            return;
        }

        try {

            const url =
                "{{ route('admin.payments.filter-classes') }}" +
                "?academic_year_id=" +
                encodeURIComponent(this.value);

            const classes = await fetchJson(url);

            resetSelect(classSelect, 'Select Class');

            if (!classes.length) {
                classSelect.innerHTML =
                    '<option value="">No classes found</option>';
                return;
            }

            classes.forEach(function (item) {

                const option = document.createElement('option');

                option.value = item.id;
                option.textContent =
                    item.name +
                    (item.code ? ' (' + item.code + ')' : '');

                classSelect.appendChild(option);
            });

            classSelect.disabled = false;

        } catch (error) {

            console.error(error);

            resetSelect(classSelect, 'Unable to load classes');
        }
    });


    /*
     * Class → Section
     */
    classSelect.addEventListener('change', async function () {

        resetSelect(sectionSelect, 'Loading sections...');
        resetSelect(studentSelect, 'Select Student');
        resetSelect(invoiceSelect, 'Select Invoice');
        resetInvoiceSummary();

        if (!this.value) {
            resetSelect(sectionSelect, 'Select Section');
            return;
        }

        try {

            const url =
                "{{ route('admin.payments.filter-sections') }}" +
                "?class_id=" +
                encodeURIComponent(this.value);

            const sections = await fetchJson(url);

            resetSelect(sectionSelect, 'Select Section');

            if (!sections.length) {
                sectionSelect.innerHTML =
                    '<option value="">No sections found</option>';
                return;
            }

            sections.forEach(function (item) {

                const option = document.createElement('option');

                option.value = item.id;
                option.textContent =
                    item.name +
                    (item.code ? ' (' + item.code + ')' : '');

                sectionSelect.appendChild(option);
            });

            sectionSelect.disabled = false;

        } catch (error) {

            console.error(error);

            resetSelect(sectionSelect, 'Unable to load sections');
        }
    });


    /*
     * Section → Student
     */
    sectionSelect.addEventListener('change', async function () {

        resetSelect(studentSelect, 'Loading students...');
        resetSelect(invoiceSelect, 'Select Invoice');
        resetInvoiceSummary();

        if (!this.value) {
            resetSelect(studentSelect, 'Select Student');
            return;
        }

        try {

            const url =
                "{{ route('admin.payments.filter-students') }}" +
                "?academic_year_id=" +
                encodeURIComponent(academicYearSelect.value) +
                "&class_id=" +
                encodeURIComponent(classSelect.value) +
                "&section_id=" +
                encodeURIComponent(this.value);

            const students = await fetchJson(url);

            resetSelect(studentSelect, 'Select Student');

            if (!students.length) {
                studentSelect.innerHTML =
                    '<option value="">No students found</option>';
                return;
            }

            students.forEach(function (item) {

                const option = document.createElement('option');

                option.value = item.id;

                option.textContent =
                    item.first_name +
                    (item.middle_name ? ' ' + item.middle_name : '') +
                    ' ' +
                    item.last_name +
                    (item.student_number
                        ? ' — ' + item.student_number
                        : '');

                studentSelect.appendChild(option);
            });

            studentSelect.disabled = false;

        } catch (error) {

            console.error(error);

            resetSelect(studentSelect, 'Unable to load students');
        }
    });


    /*
     * Student → Invoice
     */
    studentSelect.addEventListener('change', async function () {

        resetSelect(invoiceSelect, 'Loading invoices...');
        resetInvoiceSummary();

        if (!this.value) {
            resetSelect(invoiceSelect, 'Select Invoice');
            return;
        }

        try {

            const url =
                "{{ route('admin.payments.filter-invoices') }}" +
                "?student_id=" +
                encodeURIComponent(this.value);

            const invoices = await fetchJson(url);

            resetSelect(invoiceSelect, 'Select Invoice');

            if (!invoices.length) {

                invoiceSelect.innerHTML =
                    '<option value="">No outstanding invoices found</option>';

                return;
            }

            invoices.forEach(function (item) {

                const option = document.createElement('option');

                option.value = item.id;

                option.textContent =
                    item.invoice_number +
                    ' — Balance ₹' +
                    Number(item.balance).toFixed(2);

                option.dataset.invoice = JSON.stringify(item);

                invoiceSelect.appendChild(option);
            });

            invoiceSelect.disabled = false;

        } catch (error) {

            console.error(error);

            resetSelect(invoiceSelect, 'Unable to load invoices');
        }
    });


    /*
     * Invoice → Payment information
     */
    invoiceSelect.addEventListener('change', function () {

        const selectedOption =
            this.options[this.selectedIndex];

        if (!this.value || !selectedOption.dataset.invoice) {

            resetInvoiceSummary();

            return;
        }

        let invoice;

        try {
            invoice = JSON.parse(
                selectedOption.dataset.invoice
            );
        } catch (error) {

            console.error(error);

            resetInvoiceSummary();

            return;
        }


        invoiceSummary.classList.remove('d-none');

        invoiceNumber.textContent =
            invoice.invoice_number || '-';

        invoiceDate.textContent =
            invoice.invoice_date || '-';

        invoiceDueDate.textContent =
            invoice.due_date || '-';

        invoiceTotal.textContent =
            formatMoney(invoice.total);

        invoicePaid.textContent =
            formatMoney(invoice.paid);

        invoiceBalance.textContent =
            formatMoney(invoice.balance);


        /*
         * Default payment amount to outstanding balance.
         */
        amountInput.value =
            Number(invoice.balance || 0).toFixed(2);

        amountInput.max =
            Number(invoice.balance || 0).toFixed(2);

        enablePaymentFields();
    });


    /*
     * Client-side overpayment protection.
     */
    amountInput.addEventListener('input', function () {

        const maxAmount =
            Number(this.max || 0);

        const enteredAmount =
            Number(this.value || 0);

        if (maxAmount > 0 && enteredAmount > maxAmount) {

            this.setCustomValidity(
                'Payment amount cannot exceed the invoice balance.'
            );

        } else {

            this.setCustomValidity('');
        }
    });


    /*
     * Prevent accidental double submission.
     */
    document.getElementById('paymentForm')
        .addEventListener('submit', function (event) {

            if (
                !invoiceSelect.value ||
                !amountInput.value ||
                !paymentMethodSelect.value ||
                !paidAtInput.value
            ) {

                event.preventDefault();

                alert(
                    'Please select an invoice and complete the payment information.'
                );

                return;
            }

            savePaymentBtn.disabled = true;

            savePaymentBtn.innerHTML =
                '<i class="bx bx-loader-alt bx-spin me-1"></i>' +
                ' Recording Payment...';
        });


    /*
     * Initial state.
     */
    resetSelect(classSelect, 'Select Class');
    resetSelect(sectionSelect, 'Select Section');
    resetSelect(studentSelect, 'Select Student');
    resetSelect(invoiceSelect, 'Select Invoice');
    resetInvoiceSummary();

});
</script>

@endsection