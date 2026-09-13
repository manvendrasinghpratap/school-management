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
                    Edit Invoice
                </h4>

                <div class="page-title-right d-flex gap-2">

                    <a href="{{ route('admin.invoices.show', $invoice) }}"
                       class="btn btn-info">
                        <i class="bx bx-show me-1"></i>
                        View Invoice
                    </a>

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

            <strong>
                <i class="bx bx-error-circle me-1"></i>
                Please correct the following:
            </strong>

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

            <i class="bx bx-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- ============================================================
         ERROR MESSAGE
    ============================================================ --}}
    @if (session('error'))

        <div class="alert alert-danger">

            <i class="bx bx-error-circle me-1"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- ============================================================
         PAYMENT WARNING
    ============================================================ --}}
    @if (in_array($invoice->status, ['paid', 'partial']))

        <div class="alert alert-warning">

            <i class="bx bx-info-circle me-1"></i>

            This invoice has payment activity.

            Financial amounts should be changed through
            <strong>Payment Management</strong> rather than manually
            changing paid or balance amounts.

        </div>

    @endif


    {{-- ============================================================
         INVOICE FORM
    ============================================================ --}}
    <form
        method="POST"
        action="{{ route('admin.invoices.update', $invoice) }}"
        id="invoiceEditForm"
    >

        @csrf

        @method('PUT')


        {{-- ========================================================
             STUDENT INFORMATION
        ========================================================= --}}
        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">

                    <i class="bx bx-user me-1"></i>

                    Student Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Student --}}
                    <div class="col-md-3">

                        <div class="mb-3">

                            <label class="form-label">
                                Student
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ trim(
                                    ($invoice->student?->first_name ?? '') . ' ' .
                                    ($invoice->student?->middle_name ?? '') . ' ' .
                                    ($invoice->student?->last_name ?? '')
                                ) }}"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- Student Number --}}
                    <div class="col-md-3">

                        <div class="mb-3">

                            <label class="form-label">
                                Student Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $invoice->student?->student_number ?? '' }}"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- Invoice Number --}}
                    <div class="col-md-3">

                        <div class="mb-3">

                            <label class="form-label">
                                Invoice Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $invoice->invoice_number }}"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <div class="mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <div class="pt-2">

                                @switch($invoice->status)

                                    @case('paid')

                                        <span class="badge bg-success fs-6">
                                            Paid
                                        </span>

                                        @break

                                    @case('partial')

                                        <span class="badge bg-warning text-dark fs-6">
                                            Partial
                                        </span>

                                        @break

                                    @case('cancelled')

                                        <span class="badge bg-danger fs-6">
                                            Cancelled
                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-danger fs-6">
                                            Unpaid
                                        </span>

                                @endswitch

                            </div>

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
                    <div class="col-md-6">

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
                                class="form-control @error('invoice_date') is-invalid @enderror"
                                value="{{ old(
                                    'invoice_date',
                                    optional($invoice->invoice_date)->format('Y-m-d')
                                ) }}"
                                required
                            >


                            @error('invoice_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- Due Date --}}
                    <div class="col-md-6">

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
                                class="form-control @error('due_date') is-invalid @enderror"
                                value="{{ old(
                                    'due_date',
                                    $invoice->due_date
                                        ? $invoice->due_date->format('Y-m-d')
                                        : ''
                                ) }}"
                            >


                            @error('due_date')

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
                    id="addInvoiceItem"
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

                                <th style="width:20%;">
                                    Fee Category
                                </th>

                                <th style="width:30%;">
                                    Description
                                </th>

                                <th style="width:12%;">
                                    Quantity
                                </th>

                                <th style="width:18%;">
                                    Amount
                                </th>

                                <th style="width:15%;">
                                    Line Total
                                </th>

                                <th style="width:5%;">
                                    #
                                </th>

                            </tr>

                        </thead>


                        <tbody id="invoiceItemsBody">

                            @forelse ($invoice->items as $index => $item)

                                <tr class="invoice-item-row">

                                    {{-- Fee Category --}}
                                    <td>

                                        <select
                                            name="items[{{ $index }}][fee_category_id]"
                                            class="form-select item-fee-category"
                                            required
                                        >

                                            <option value="">
                                                Select Fee Category
                                            </option>


                                            @foreach ($feeCategories as $feeCategory)

                                                <option
                                                    value="{{ $feeCategory->id }}"
                                                    {{ (string) $item->fee_category_id === (string) $feeCategory->id ? 'selected' : '' }}
                                                >

                                                    {{ $feeCategory->name }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </td>


                                    {{-- Description --}}
                                    <td>

                                        <input
                                            type="text"
                                            name="items[{{ $index }}][description]"
                                            class="form-control item-description"
                                            value="{{ $item->description }}"
                                            maxlength="500"
                                            required
                                        >

                                    </td>


                                    {{-- Quantity --}}
                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][quantity]"
                                            class="form-control item-quantity"
                                            value="{{ $item->quantity }}"
                                            min="0.01"
                                            step="0.01"
                                            required
                                        >

                                    </td>


                                    {{-- Amount --}}
                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][amount]"
                                            class="form-control item-amount"
                                            value="{{ $item->amount }}"
                                            min="0.01"
                                            step="0.01"
                                            required
                                        >

                                    </td>


                                    {{-- Line Total --}}
                                    <td>

                                        <span class="item-line-total fw-semibold">

                                            ₹{{ number_format(
                                                (float) $item->quantity *
                                                (float) $item->amount,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- Remove --}}
                                    <td class="text-center">

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm remove-item"
                                            title="Remove Item"
                                        >

                                            <i class="bx bx-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr class="empty-row">

                                    <td
                                        colspan="6"
                                        class="text-center text-muted py-4"
                                    >

                                        No invoice items found.

                                        <br>

                                        Click
                                        <strong>Add Item</strong>
                                        to add an invoice item.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>


                        <tfoot>

                            {{-- Subtotal --}}
                            <tr>

                                <th
                                    colspan="4"
                                    class="text-end"
                                >

                                    Subtotal

                                </th>

                                <th colspan="2">

                                    ₹<span id="subtotalDisplay">
                                        {{ number_format((float) $invoice->subtotal, 2) }}
                                    </span>

                                </th>

                            </tr>


                            {{-- Discount --}}
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
                                        class="form-control @error('discount') is-invalid @enderror"
                                        value="{{ old(
                                            'discount',
                                            $invoice->discount
                                        ) }}"
                                        min="0"
                                        step="0.01"
                                    >


                                    @error('discount')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </th>

                            </tr>


                            {{-- Total --}}
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
                                            {{ number_format((float) $invoice->total, 2) }}
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

                    Payment Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="mb-3">

                            <label class="text-muted">
                                Invoice Total
                            </label>

                            <h5>
                                ₹{{ number_format((float) $invoice->total, 2) }}
                            </h5>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="mb-3">

                            <label class="text-muted">
                                Paid
                            </label>

                            <h5>
                                ₹{{ number_format((float) $invoice->paid, 2) }}
                            </h5>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="mb-3">

                            <label class="text-muted">
                                Current Balance
                            </label>

                            <h5>
                                ₹{{ number_format((float) $invoice->balance, 2) }}
                            </h5>

                        </div>

                    </div>

                </div>


                <div class="alert alert-info mb-0">

                    <i class="bx bx-info-circle me-1"></i>

                    Payments are managed separately.
                    Paid and balance values are not editable from this page.

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
                        href="{{ route('admin.invoices.show', $invoice) }}"
                        class="btn btn-light"
                    >

                        <i class="bx bx-x me-1"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="updateInvoiceBtn"
                    >

                        <i class="bx bx-save me-1"></i>

                        Update Invoice

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

    const form =
        document.getElementById('invoiceEditForm');

    const itemsBody =
        document.getElementById('invoiceItemsBody');

    const addItemButton =
        document.getElementById('addInvoiceItem');

    const discountInput =
        document.getElementById('discount');

    const subtotalDisplay =
        document.getElementById('subtotalDisplay');

    const totalDisplay =
        document.getElementById('totalDisplay');

    const updateButton =
        document.getElementById('updateInvoiceBtn');


    if (
        !form ||
        !itemsBody ||
        !addItemButton ||
        !discountInput ||
        !subtotalDisplay ||
        !totalDisplay
    ) {

        console.error(
            'Invoice edit form: required elements are missing.'
        );

        return;

    }


    /* ================================================================
       ITEM INDEX
    ================================================================= */

    let itemIndex =
        itemsBody.querySelectorAll('.invoice-item-row').length;


    /* ================================================================
       UPDATE EMPTY STATE
    ================================================================= */

    function updateEmptyState() {

        const rows =
            itemsBody.querySelectorAll('.invoice-item-row');

        const emptyRow =
            itemsBody.querySelector('.empty-row');


        if (rows.length === 0) {

            if (!emptyRow) {

                const row =
                    document.createElement('tr');

                row.className =
                    'empty-row';

                row.innerHTML = `
                    <td colspan="6"
                        class="text-center text-muted py-4">

                        No invoice items added yet.

                        <br>

                        Click
                        <strong>Add Item</strong>
                        to add an invoice item.

                    </td>
                `;

                itemsBody.appendChild(row);

            }

        } else {

            if (emptyRow) {
                emptyRow.remove();
            }

        }

    }


    /* ================================================================
       UPDATE TOTALS
    ================================================================= */

    function updateTotals() {

        let subtotal = 0;


        const rows =
            itemsBody.querySelectorAll('.invoice-item-row');


        rows.forEach(function (row) {

            const quantityInput =
                row.querySelector('.item-quantity');

            const amountInput =
                row.querySelector('.item-amount');

            const lineTotalElement =
                row.querySelector('.item-line-total');


            const quantity =
                parseFloat(
                    quantityInput?.value
                ) || 0;


            const amount =
                parseFloat(
                    amountInput?.value
                ) || 0;


            const lineTotal =
                quantity * amount;


            subtotal +=
                lineTotal;


            if (lineTotalElement) {

                lineTotalElement.textContent =
                    '₹' + lineTotal.toFixed(2);

            }

        });


        let discount =
            parseFloat(
                discountInput.value
            ) || 0;


        if (discount < 0) {
            discount = 0;
        }


        const total =
            Math.max(
                subtotal - discount,
                0
            );


        subtotalDisplay.textContent =
            subtotal.toFixed(2);


        totalDisplay.textContent =
            total.toFixed(2);

    }


    /* ================================================================
       ADD ITEM
    ================================================================= */

    function addItem() {

        const emptyRow =
            itemsBody.querySelector('.empty-row');


        if (emptyRow) {
            emptyRow.remove();
        }


        const row =
            document.createElement('tr');

        row.className =
            'invoice-item-row';


        row.innerHTML = `

            <td>

                <select
                    name="items[${itemIndex}][fee_category_id]"
                    class="form-select item-fee-category"
                    required
                >

                    <option value="">
                        Select Fee Category
                    </option>

                    @foreach ($feeCategories as $feeCategory)

                        <option value="{{ $feeCategory->id }}">
                            {{ $feeCategory->name }}
                        </option>

                    @endforeach

                </select>

            </td>


            <td>

                <input
                    type="text"
                    name="items[${itemIndex}][description]"
                    class="form-control item-description"
                    maxlength="500"
                    required
                >

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][quantity]"
                    class="form-control item-quantity"
                    value="1"
                    min="0.01"
                    step="0.01"
                    required
                >

            </td>


            <td>

                <input
                    type="number"
                    name="items[${itemIndex}][amount]"
                    class="form-control item-amount"
                    value="0"
                    min="0.01"
                    step="0.01"
                    required
                >

            </td>


            <td>

                <span class="item-line-total fw-semibold">
                    ₹0.00
                </span>

            </td>


            <td class="text-center">

                <button
                    type="button"
                    class="btn btn-danger btn-sm remove-item"
                    title="Remove Item"
                >

                    <i class="bx bx-trash"></i>

                </button>

            </td>

        `;


        itemsBody.appendChild(row);


        itemIndex++;


        updateTotals();

    }


    /* ================================================================
       ADD ITEM BUTTON
    ================================================================= */

    addItemButton.addEventListener(
        'click',
        function () {

            addItem();

        }
    );


    /* ================================================================
       ITEM INPUT EVENTS
    ================================================================= */

    itemsBody.addEventListener(
        'input',
        function (event) {

            if (
                event.target.classList.contains(
                    'item-quantity'
                ) ||
                event.target.classList.contains(
                    'item-amount'
                )
            ) {

                updateTotals();

            }

        }
    );


    /* ================================================================
       REMOVE ITEM
    ================================================================= */

    itemsBody.addEventListener(
        'click',
        function (event) {

            const removeButton =
                event.target.closest('.remove-item');


            if (!removeButton) {
                return;
            }


            const row =
                removeButton.closest(
                    '.invoice-item-row'
                );


            if (!row) {
                return;
            }


            const rows =
                itemsBody.querySelectorAll(
                    '.invoice-item-row'
                );


            /*
             * Prevent removing the final item.
             * Invoice must contain at least one item.
             */
            if (rows.length === 1) {

                alert(
                    'An invoice must contain at least one item.'
                );

                return;

            }


            row.remove();


            updateEmptyState();
            updateTotals();

        }
    );


    /* ================================================================
       DISCOUNT CHANGE
    ================================================================= */

    discountInput.addEventListener(
        'input',
        function () {

            updateTotals();

        }
    );


    /* ================================================================
       FORM SUBMIT VALIDATION
    ================================================================= */

    form.addEventListener(
        'submit',
        function (event) {

            const rows =
                itemsBody.querySelectorAll(
                    '.invoice-item-row'
                );


            /*
             * At least one item required.
             */
            if (rows.length === 0) {

                event.preventDefault();

                alert(
                    'Please add at least one invoice item.'
                );

                return;

            }


            let subtotal = 0;


            let invalidItem = false;


            rows.forEach(function (row) {

                const feeCategory =
                    row.querySelector(
                        '.item-fee-category'
                    );

                const description =
                    row.querySelector(
                        '.item-description'
                    );

                const quantity =
                    row.querySelector(
                        '.item-quantity'
                    );

                const amount =
                    row.querySelector(
                        '.item-amount'
                    );


                const quantityValue =
                    parseFloat(
                        quantity?.value
                    ) || 0;


                const amountValue =
                    parseFloat(
                        amount?.value
                    ) || 0;


                if (
                    !feeCategory?.value ||
                    !description?.value.trim() ||
                    quantityValue <= 0 ||
                    amountValue <= 0
                ) {

                    invalidItem = true;

                    return;

                }


                subtotal +=
                    quantityValue *
                    amountValue;

            });


            if (invalidItem) {

                event.preventDefault();

                alert(
                    'Please complete all invoice item fields correctly.'
                );

                return;

            }


            const discount =
                parseFloat(
                    discountInput.value
                ) || 0;


            if (discount < 0) {

                event.preventDefault();

                alert(
                    'Discount cannot be negative.'
                );

                discountInput.focus();

                return;

            }


            if (discount > subtotal) {

                event.preventDefault();

                alert(
                    'Discount cannot exceed the invoice subtotal.'
                );

                discountInput.focus();

                return;

            }


            /*
             * Due date should not be before invoice date.
             */
            const invoiceDate =
                document.getElementById(
                    'invoice_date'
                ).value;


            const dueDate =
                document.getElementById(
                    'due_date'
                ).value;


            if (
                invoiceDate &&
                dueDate &&
                dueDate < invoiceDate
            ) {

                event.preventDefault();

                alert(
                    'Due date cannot be earlier than the invoice date.'
                );

                document.getElementById(
                    'due_date'
                ).focus();

                return;

            }


            /*
             * Prevent double submission.
             */
            if (updateButton) {

                updateButton.disabled =
                    true;

                updateButton.innerHTML =
                    '<i class="bx bx-loader-alt bx-spin me-1"></i> Updating...';

            }

        }
    );


    /* ================================================================
       INITIALIZE
    ================================================================= */

    updateEmptyState();

    updateTotals();

});

</script>

@endsection