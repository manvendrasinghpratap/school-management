@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-sm-0">ID Cards</h4>

                    <p class="text-muted mb-0">
                        Manage generated student and staff ID cards.
                    </p>
                </div>

                <div class="d-flex gap-2">

                    @can('id-cards.generate')

                        <a href="{{ route('admin.id-cards.create') }}"
                           class="btn btn-primary">

                            <i class="ri-add-line me-1"></i>

                            Generate ID Card

                        </a>

                    @endcan


                    @can('id-cards.generate')

                        <a href="{{ route('admin.id-cards.templates') }}"
                           class="btn btn-light">

                            <i class="ri-layout-line me-1"></i>

                            Templates

                        </a>

                    @endcan

                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
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


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="card">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.id-cards.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Card number or verification code">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="expired"
                                {{ request('status') === 'expired' ? 'selected' : '' }}>
                                Expired
                            </option>

                            <option value="cancelled"
                                {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Holder --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Holder
                        </label>

                        <select name="holder_type"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="student"
                                {{ request('holder_type') === 'student' ? 'selected' : '' }}>
                                Students
                            </option>

                            <option value="staff"
                                {{ request('holder_type') === 'staff' ? 'selected' : '' }}>
                                Staff
                            </option>

                        </select>

                    </div>


                    {{-- Search Buttons --}}
                    <div class="col-md-2">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="ri-search-line me-1"></i>

                                Search

                            </button>


                            <a href="{{ route('admin.id-cards.index') }}"
                               class="btn btn-light"
                               title="Reset">

                                <i class="ri-refresh-line"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        GENERATED ID CARDS
    ========================================================== --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex align-items-center justify-content-between">

                <div>

                    <h5 class="card-title mb-1">
                        Generated ID Cards
                    </h5>

                    <p class="text-muted mb-0">

                        {{ $idCards->total() }}

                        {{ $idCards->total() == 1 ? 'card' : 'cards' }}

                        found.

                    </p>

                </div>


                <span class="badge bg-primary-subtle text-primary">

                    {{ $idCards->total() }}

                    {{ $idCards->total() == 1 ? 'Card' : 'Cards' }}

                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th style="width:60px;">
                                #
                            </th>

                            <th>
                                Card Number
                            </th>

                            <th>
                                Holder
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Template
                            </th>

                            <th>
                                Issued
                            </th>

                            <th>
                                Expires
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($idCards as $idCard)

                        <tr>

                            {{-- Number --}}
                            <td>

                                {{ $idCards->firstItem() + $loop->index }}

                            </td>


                            {{-- Card Number --}}
                            <td>

                                <span class="fw-semibold">

                                    {{ $idCard->card_number }}

                                </span>

                                @if($idCard->verification_code)

                                    <div class="small text-muted mt-1">

                                        Verification:

                                        {{ $idCard->verification_code }}

                                    </div>

                                @endif

                            </td>


                            {{-- Holder --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="avatar-sm flex-shrink-0">

                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">

                                            <i class="ri-user-line"></i>

                                        </div>

                                    </div>


                                    <div class="ms-2">

                                        @if($idCard->student)

                                            <h6 class="mb-0">

                                                {{ trim(
                                                    $idCard->student->first_name . ' ' .
                                                    ($idCard->student->middle_name ?? '') . ' ' .
                                                    $idCard->student->last_name
                                                ) }}

                                            </h6>

                                            <small class="text-muted">
                                                Student
                                            </small>

                                        @elseif($idCard->staff)

                                            <h6 class="mb-0">

                                                {{ trim(
                                                    $idCard->staff->first_name . ' ' .
                                                    ($idCard->staff->middle_name ?? '') . ' ' .
                                                    $idCard->staff->last_name
                                                ) }}

                                            </h6>

                                            <small class="text-muted">
                                                Staff
                                            </small>

                                        @else

                                            <h6 class="mb-0">
                                                Unknown
                                            </h6>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Type --}}
                            <td>

                                @if($idCard->student_id)

                                    <span class="badge bg-primary-subtle text-primary">

                                        <i class="ri-graduation-cap-line me-1"></i>

                                        Student

                                    </span>

                                @elseif($idCard->staff_id)

                                    <span class="badge bg-info-subtle text-info">

                                        <i class="ri-user-settings-line me-1"></i>

                                        Staff

                                    </span>

                                @else

                                    <span class="badge bg-secondary-subtle text-secondary">

                                        General

                                    </span>

                                @endif

                            </td>


                            {{-- Template --}}
                            <td>

                                @if($idCard->template)

                                    <span class="fw-medium">

                                        {{ $idCard->template->name }}

                                    </span>

                                    <div class="small text-muted">

                                        {{ ucfirst($idCard->template->card_type) }}

                                    </div>

                                @else

                                    <span class="text-muted">
                                        No template
                                    </span>

                                @endif

                            </td>


                            {{-- Issued --}}
                            <td>

                                @if($idCard->issued_at)

                                    {{ $idCard->issued_at->format('d M Y') }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Expires --}}
                            <td>

                                @if($idCard->expires_at)

                                    {{ $idCard->expires_at->format('d M Y') }}

                                @else

                                    <span class="text-muted">
                                        No expiry
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @switch($idCard->status)

                                    @case('active')

                                        <span class="badge bg-success-subtle text-success">

                                            <i class="ri-checkbox-circle-line me-1"></i>

                                            Active

                                        </span>

                                        @break


                                    @case('expired')

                                        <span class="badge bg-warning-subtle text-warning">

                                            <i class="ri-time-line me-1"></i>

                                            Expired

                                        </span>

                                        @break


                                    @case('cancelled')

                                        <span class="badge bg-danger-subtle text-danger">

                                            <i class="ri-close-circle-line me-1"></i>

                                            Cancelled

                                        </span>

                                        @break


                                    @default

                                        <span class="badge bg-secondary-subtle text-secondary">

                                            {{ ucfirst($idCard->status) }}

                                        </span>

                                @endswitch

                            </td>


                            {{-- Actions --}}
                            <td class="text-end">

                                @can('id-cards.view')

                                    <a href="{{ route('admin.id-cards.show', $idCard->id) }}"
                                       class="btn btn-sm btn-soft-primary"
                                       title="View">

                                        <i class="ri-eye-line"></i>

                                    </a>


                                    <a href="{{ route('admin.id-cards.print', $idCard->id) }}"
                                       class="btn btn-sm btn-soft-info"
                                       target="_blank"
                                       title="Print">

                                        <i class="ri-printer-line"></i>

                                    </a>

                                @endcan


                                @can('id-cards.generate')

                                    <form method="POST"
                                          action="{{ route('admin.id-cards.destroy', $idCard->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this ID card?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-soft-danger"
                                                title="Delete">

                                            <i class="ri-delete-bin-line"></i>

                                        </button>

                                    </form>

                                @endcan

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-5">

                                <div class="avatar-md mx-auto mb-3">

                                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">

                                        <i class="ri-id-card-line"></i>

                                    </div>

                                </div>


                                <h5>
                                    No ID Cards Found
                                </h5>


                                <p class="text-muted mb-3">

                                    No generated ID cards match your current filters.

                                </p>


                                @can('id-cards.generate')

                                    <a href="{{ route('admin.id-cards.create') }}"
                                       class="btn btn-primary">

                                        <i class="ri-add-line me-1"></i>

                                        Generate ID Card

                                    </a>

                                @endcan

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($idCards->hasPages())

                <div class="mt-3">

                    {{ $idCards->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection