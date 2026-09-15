@extends('backend.layout.default')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div>
                <h4 class="mb-1">Dashboard</h4>
                <p class="text-muted mb-0">
                    Welcome to your School Management System.
                </p>
            </div>
        </div>
    </div>

    {{-- Current Academic Period --}}
    <div class="row mb-4">

        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-calendar"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Current Academic Year
                            </p>

                            <h5 class="mb-0">
                                {{ $currentAcademicYear?->name ?? 'Not Set' }}
                            </h5>

                            @if($currentAcademicYear)
                                <small class="text-muted">
                                    {{ $currentAcademicYear->start_date?->format('d M Y') }}
                                    -
                                    {{ $currentAcademicYear->end_date?->format('d M Y') }}
                                </small>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-success-subtle text-success font-size-20">
                                <i class="bx bx-book-open"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Current Term
                            </p>

                            <h5 class="mb-0">
                                {{ $currentTerm?->name ?? 'Not Set' }}
                            </h5>

                            @if($currentTerm)
                                <small class="text-muted">
                                    {{ $currentTerm->start_date?->format('d M Y') }}
                                    -
                                    {{ $currentTerm->end_date?->format('d M Y') }}
                                </small>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Main Statistics --}}
    <div class="row">

        {{-- Students --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                Total Students
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['students']) }}
                            </h4>
                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-user"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.students.index') }}"
                           class="text-primary">
                            View Students
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Active Students --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                Active Students
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['active_students']) }}
                            </h4>
                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-success-subtle text-success font-size-20">
                                <i class="bx bx-user-check"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.students.index', ['status' => 'active']) }}"
                           class="text-success">
                            Active Students
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Staff --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                Total Staff
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['staff']) }}
                            </h4>
                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-info-subtle text-info font-size-20">
                                <i class="bx bx-group"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.staff.index') }}"
                           class="text-info">
                            View Staff
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Instructors --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2">
                                Instructors
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['instructors']) }}
                            </h4>
                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-warning-subtle text-warning font-size-20">
                                <i class="bx bx-chalkboard"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.instructors.index') }}"
                           class="text-warning">
                            View Instructors
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Academic Statistics --}}
    <div class="row">

        {{-- Classes --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Classes
                    </p>

                    <h4 class="mb-0">
                        {{ number_format($statistics['classes']) }}
                    </h4>

                    <div class="mt-3">
                        <a href="{{ route('admin.classes.index') }}"
                           class="text-primary">
                            Manage Classes
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Sections --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Sections
                    </p>

                    <h4 class="mb-0">
                        {{ number_format($statistics['sections']) }}
                    </h4>

                    <div class="mt-3">
                        <a href="{{ route('admin.sections.index') }}"
                           class="text-primary">
                            Manage Sections
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Departments --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Departments
                    </p>

                    <h4 class="mb-0">
                        {{ number_format($statistics['departments']) }}
                    </h4>

                    <div class="mt-3">
                        <a href="{{ route('admin.departments.index') }}"
                           class="text-primary">
                            Manage Departments
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Courses --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Subjects / Courses
                    </p>

                    <h4 class="mb-0">
                        {{ number_format($statistics['courses']) }}
                    </h4>

                    <div class="mt-3">
                        <a href="{{ route('admin.courses.index') }}"
                           class="text-primary">
                            Manage Courses
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
    {{-- =========================================================
     FINANCE DASHBOARD
========================================================= --}}

@if(!empty($financeDashboard))

    {{-- Finance Overview --}}
    <div class="row mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">

                            <p class="text-muted mb-2">
                                Total Invoiced
                            </p>

                            <h4 class="mb-0">
                                ₹{{ number_format($financeDashboard['total_invoiced'], 2) }}
                            </h4>

                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-receipt"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        @can('invoices.view')
                            <a href="{{ route('admin.invoices.index') }}"
                               class="text-primary">
                                View Invoices
                                <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        @endcan
                    </div>

                </div>
            </div>
        </div>


        {{-- Total Collected --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">

                            <p class="text-muted mb-2">
                                Total Collected
                            </p>

                            <h4 class="mb-0 text-success">
                                ₹{{ number_format($financeDashboard['total_collected'], 2) }}
                            </h4>

                            <small class="text-muted">
                                Effective collection
                            </small>

                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-success-subtle text-success font-size-20">
                                <i class="bx bx-money"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        @can('payments.view')
                            <a href="{{ route('admin.payments.index') }}"
                               class="text-success">
                                View Payments
                                <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        @endcan
                    </div>

                </div>
            </div>
        </div>


        {{-- Outstanding --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">

                            <p class="text-muted mb-2">
                                Outstanding Fees
                            </p>

                            <h4 class="mb-0 text-warning">
                                ₹{{ number_format($financeDashboard['outstanding'], 2) }}
                            </h4>

                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-warning-subtle text-warning font-size-20">
                                <i class="bx bx-time-five"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        @can('invoices.view')
                            <a href="{{ route('admin.invoices.index') }}"
                               class="text-warning">
                                Outstanding Invoices
                                <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        @endcan
                    </div>

                </div>
            </div>
        </div>


        {{-- Refunded --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex">
                        <div class="flex-grow-1">

                            <p class="text-muted mb-2">
                                Total Refunded
                            </p>

                            <h4 class="mb-0 text-danger">
                                ₹{{ number_format($financeDashboard['total_refunded'], 2) }}
                            </h4>

                            <small class="text-muted">
                                Processed refunds
                            </small>

                        </div>

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-danger-subtle text-danger font-size-20">
                                <i class="bx bx-undo"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        @can('payment-refunds.view')
                            <a href="{{ route('admin.payment-refunds.index') }}"
                               class="text-danger">
                                View Refunds
                                <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        @endcan
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- Invoice Status + Collection Rate --}}
    <div class="row mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Paid Invoices
                    </p>

                    <h4 class="mb-0 text-success">
                        {{ number_format($financeDashboard['paid_invoices']) }}
                    </h4>

                    <small class="text-muted">
                        Fully paid
                    </small>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Partial Invoices
                    </p>

                    <h4 class="mb-0 text-warning">
                        {{ number_format($financeDashboard['partial_invoices']) }}
                    </h4>

                    <small class="text-muted">
                        Partially paid
                    </small>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Unpaid Invoices
                    </p>

                    <h4 class="mb-0 text-danger">
                        {{ number_format($financeDashboard['unpaid_invoices']) }}
                    </h4>

                    <small class="text-muted">
                        Payment pending
                    </small>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <p class="text-muted mb-2">
                        Collection Rate
                    </p>

                    <h4 class="mb-0 text-primary">
                        {{ number_format($financeDashboard['collection_percentage'], 2) }}%
                    </h4>

                    <div class="progress mt-3" style="height: 6px;">
                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width: {{ $financeDashboard['collection_percentage'] }}%"
                            aria-valuenow="{{ $financeDashboard['collection_percentage'] }}"
                            aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- Recent Payments --}}
    <div class="row mb-4">

        <div class="col-xl-7">

            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            <i class="bx bx-money me-2"></i>
                            Recent Payments
                        </h5>

                        @can('payments.view')
                            <a href="{{ route('admin.payments.index') }}"
                               class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
                        @endcan

                    </div>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>Student</th>
                                    <th>Invoice</th>
                                    <th>Method</th>
                                    <th class="text-end">Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($financeDashboard['recent_payments'] as $payment)

                                    <tr>

                                        <td>
                                            @if($payment->invoice?->student)
                                                <div>
                                                    <strong>
                                                        {{ $payment->invoice->student->first_name }}
                                                        {{ $payment->invoice->student->last_name }}
                                                    </strong>

                                                    <small class="d-block text-muted">
                                                        {{ $payment->invoice->student->student_number }}
                                                    </small>
                                                </div>
                                            @else
                                                —
                                            @endif
                                        </td>

                                        <td>
                                            @if($payment->invoice)
                                                <a href="{{ route('admin.invoices.show', $payment->invoice) }}">
                                                    {{ $payment->invoice->invoice_number }}
                                                </a>
                                            @else
                                                —
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}
                                            </span>
                                        </td>

                                        <td class="text-end">
                                            <strong class="text-success">
                                                ₹{{ number_format((float) $payment->amount, 2) }}
                                            </strong>
                                        </td>

                                        <td>
                                            <small>
                                                {{ $payment->paid_at?->format('d M Y') ?? '—' }}
                                            </small>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5"
                                            class="text-center text-muted py-4">
                                            <i class="bx bx-money font-size-24 d-block mb-2"></i>
                                            No payments recorded yet.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- Payment Method Summary --}}
        <div class="col-xl-5">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-wallet me-2"></i>
                        Payment Methods
                    </h5>
                </div>

                <div class="card-body">

                    @forelse($financeDashboard['payment_method_summary'] as $method)

                        <div class="d-flex align-items-center mb-3">

                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    <i class="bx bx-credit-card"></i>
                                </span>
                            </div>

                            <div class="ms-3 flex-grow-1">

                                <h6 class="mb-1">
                                    {{ ucfirst(str_replace('_', ' ', $method->payment_method ?? 'Other')) }}
                                </h6>

                                <small class="text-muted">
                                    {{ number_format($method->payment_count) }}
                                    payment{{ $method->payment_count == 1 ? '' : 's' }}
                                </small>

                            </div>

                            <div class="text-end">

                                <strong>
                                    ₹{{ number_format((float) $method->gross_amount, 2) }}
                                </strong>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-4 text-muted">
                            <i class="bx bx-wallet font-size-24 d-block mb-2"></i>
                            No payment data available.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- Outstanding Invoices + Refunds --}}
    <div class="row mb-4">

        {{-- Outstanding Invoices --}}
        <div class="col-xl-7">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            <i class="bx bx-time-five me-2"></i>
                            Outstanding Invoices
                        </h5>

                        @can('invoices.view')
                            <a href="{{ route('admin.invoices.index') }}"
                               class="btn btn-sm btn-outline-warning">
                                View All
                            </a>
                        @endcan

                    </div>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>Invoice</th>
                                    <th>Student</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($financeDashboard['outstanding_invoices'] as $invoice)

                                    <tr>

                                        <td>
                                            <a href="{{ route('admin.invoices.show', $invoice) }}">
                                                {{ $invoice->invoice_number }}
                                            </a>
                                        </td>

                                        <td>

                                            @if($invoice->student)

                                                <strong>
                                                    {{ $invoice->student->first_name }}
                                                    {{ $invoice->student->last_name }}
                                                </strong>

                                                <small class="d-block text-muted">
                                                    {{ $invoice->student->student_number }}
                                                </small>

                                            @else
                                                —
                                            @endif

                                        </td>

                                        <td class="text-end">
                                            ₹{{ number_format((float) $invoice->total, 2) }}
                                        </td>

                                        <td class="text-end">
                                            <strong class="text-danger">
                                                ₹{{ number_format((float) $invoice->balance, 2) }}
                                            </strong>
                                        </td>

                                        <td>

                                            @if($invoice->status === 'partial')

                                                <span class="badge bg-warning-subtle text-warning">
                                                    Partial
                                                </span>

                                            @elseif($invoice->status === 'unpaid')

                                                <span class="badge bg-danger-subtle text-danger">
                                                    Unpaid
                                                </span>

                                            @else

                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    {{ ucfirst($invoice->status ?? 'Pending') }}
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5"
                                            class="text-center text-muted py-4">

                                            <i class="bx bx-check-circle font-size-24 d-block mb-2"></i>

                                            No outstanding invoices.

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- Recent Refunds --}}
        <div class="col-xl-5">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            <i class="bx bx-undo me-2"></i>
                            Recent Refunds
                        </h5>

                        @can('payment-refunds.view')
                            <a href="{{ route('admin.payment-refunds.index') }}"
                               class="btn btn-sm btn-outline-danger">
                                View All
                            </a>
                        @endcan

                    </div>

                </div>

                <div class="card-body">

                    @forelse($financeDashboard['recent_refunds'] as $refund)

                        <div class="d-flex align-items-center py-2 border-bottom">

                            <div class="avatar-sm">

                                <span class="avatar-title rounded-circle bg-danger-subtle text-danger">
                                    <i class="bx bx-undo"></i>
                                </span>

                            </div>

                            <div class="ms-3 flex-grow-1">

                                <h6 class="mb-1">

                                    @if($refund->student)

                                        {{ $refund->student->first_name }}
                                        {{ $refund->student->last_name }}

                                    @else

                                        Payment #{{ $refund->payment_id }}

                                    @endif

                                </h6>

                                <small class="text-muted">
                                    {{ $refund->reason ?: 'Refund request' }}
                                </small>

                            </div>

                            <div class="text-end">

                                <strong class="text-danger">
                                    ₹{{ number_format((float) $refund->amount, 2) }}
                                </strong>

                                <span class="badge d-block mt-1
                                    @if($refund->status === 'processed')
                                        bg-success-subtle text-success
                                    @elseif($refund->status === 'approved')
                                        bg-primary-subtle text-primary
                                    @elseif($refund->status === 'requested')
                                        bg-warning-subtle text-warning
                                    @elseif($refund->status === 'rejected')
                                        bg-danger-subtle text-danger
                                    @else
                                        bg-secondary-subtle text-secondary
                                    @endif
                                ">
                                    {{ ucfirst($refund->status) }}
                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-4 text-muted">

                            <i class="bx bx-undo font-size-24 d-block mb-2"></i>

                            No refunds recorded yet.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

    @endif
    {{-- Quick Actions --}}
    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-bolt-circle me-2"></i>
                        Quick Actions
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex flex-wrap gap-2">

                        <a href="{{ route('admin.students.create') }}"
                           class="btn btn-primary">
                            <i class="bx bx-user-plus me-1"></i>
                            Add Student
                        </a>

                        <a href="{{ route('admin.staff.create') }}"
                           class="btn btn-info">
                            <i class="bx bx-user-plus me-1"></i>
                            Add Staff
                        </a>

                        <a href="{{ route('admin.instructors.create') }}"
                           class="btn btn-warning">
                            <i class="bx bx-chalkboard me-1"></i>
                            Add Instructor
                        </a>

                        <a href="{{ route('admin.classes.create') }}"
                           class="btn btn-success">
                            <i class="bx bx-building me-1"></i>
                            Add Class
                        </a>

                        <a href="{{ route('admin.courses.create') }}"
                           class="btn btn-secondary">
                            <i class="bx bx-book-add me-1"></i>
                            Add Course
                        </a>

                        <a href="{{ route('admin.academic-years.create') }}"
                           class="btn btn-dark">
                            <i class="bx bx-calendar-plus me-1"></i>
                            Add Academic Year
                        </a>

                        <a href="{{ route('admin.terms.create') }}"
                           class="btn btn-outline-primary">
                            <i class="bx bx-calendar-event me-1"></i>
                            Add Term
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        {{-- Recent Students --}}
        <div class="col-xl-6">

            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            <i class="bx bx-user me-2"></i>
                            Recent Students
                        </h5>

                        <a href="{{ route('admin.students.index') }}"
                           class="btn btn-sm btn-outline-primary">
                            View All
                        </a>

                    </div>
                </div>

                <div class="card-body">

                    @forelse($recentStudents as $student)

                        <div class="d-flex align-items-center py-2 border-bottom">

                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                </span>
                            </div>

                            <div class="ms-3 flex-grow-1">

                                <h6 class="mb-1">
                                    {{ $student->first_name }}
                                    {{ $student->middle_name }}
                                    {{ $student->last_name }}
                                </h6>

                                <small class="text-muted">
                                    {{ $student->student_number }}
                                </small>

                            </div>

                            <span class="badge
                                @if($student->status === 'active')
                                    bg-success-subtle text-success
                                @else
                                    bg-secondary-subtle text-secondary
                                @endif">
                                {{ ucfirst($student->status) }}
                            </span>

                        </div>

                    @empty

                        <div class="text-center py-4 text-muted">
                            <i class="bx bx-user-x font-size-24 d-block mb-2"></i>
                            No students registered yet.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

        {{-- Recent Staff --}}
        <div class="col-xl-6">

            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="card-title mb-0">
                            <i class="bx bx-group me-2"></i>
                            Recent Staff
                        </h5>

                        <a href="{{ route('admin.staff.index') }}"
                           class="btn btn-sm btn-outline-primary">
                            View All
                        </a>

                    </div>
                </div>

                <div class="card-body">

                    @forelse($recentStaff as $staff)

                        <div class="d-flex align-items-center py-2 border-bottom">

                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-info-subtle text-info">
                                    {{ strtoupper(substr($staff->first_name, 0, 1)) }}
                                </span>
                            </div>

                            <div class="ms-3 flex-grow-1">

                                <h6 class="mb-1">
                                    {{ $staff->first_name }}
                                    {{ $staff->middle_name }}
                                    {{ $staff->last_name }}
                                </h6>

                                <small class="text-muted">
                                    {{ $staff->staff_number }}
                                </small>

                            </div>

                            <span class="badge
                                @if($staff->status === 'active')
                                    bg-success-subtle text-success
                                @else
                                    bg-secondary-subtle text-secondary
                                @endif">
                                {{ ucfirst($staff->status) }}
                            </span>

                        </div>

                    @empty

                        <div class="text-center py-4 text-muted">
                            <i class="bx bx-group font-size-24 d-block mb-2"></i>
                            No staff registered yet.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>
@endsection