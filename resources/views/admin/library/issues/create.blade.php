@extends('backend.layout.default')

@section('title', 'Issue Book')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Issue Book</h4>
            <p class="text-muted mb-0">
                Issue a physical library copy to an active library member.
            </p>
        </div>

        <a href="{{ route('admin.library.issues.index') }}"
           class="btn btn-light">
            <i class="mdi mdi-arrow-left me-1"></i>
            Back to Issues
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.library.issues.store') }}">

        @csrf

        <div class="row">

            {{-- Issue Details --}}
            <div class="col-xl-8">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Issue Details
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Book Copy --}}
                            <div class="col-md-12">
                                <label class="form-label">
                                    Book Copy <span class="text-danger">*</span>
                                </label>

                                <select name="book_copy_id"
                                        class="form-select @error('book_copy_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select available book copy
                                    </option>

                                    @foreach ($copies as $copy)

                                        <option value="{{ $copy->id }}"
                                            {{ old('book_copy_id') == $copy->id ? 'selected' : '' }}>

                                            {{ $copy->book?->title ?? 'Unknown Book' }}
                                            —
                                            Copy: {{ $copy->accession_number }}
                                            @if ($copy->barcode)
                                                — Barcode: {{ $copy->barcode }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('book_copy_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @if ($copies->isEmpty())
                                    <div class="text-danger small mt-2">
                                        No available physical book copies are currently available.
                                    </div>
                                @else
                                    <div class="form-text">
                                        Only available physical copies from your school are shown.
                                    </div>
                                @endif
                            </div>

                            {{-- Member --}}
                            <div class="col-md-12">
                                <label class="form-label">
                                    Library Member <span class="text-danger">*</span>
                                </label>

                                <select name="library_member_id"
                                        class="form-select @error('library_member_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select active library member
                                    </option>

                                    @foreach ($members as $member)

                                        @php
                                            $holderName = '';

                                            if ($member->student) {
                                                $holderName = trim(
                                                    $member->student->first_name . ' ' .
                                                    ($member->student->middle_name ?? '') . ' ' .
                                                    $member->student->last_name
                                                );
                                            } elseif ($member->staff) {
                                                $holderName = trim(
                                                    $member->staff->first_name . ' ' .
                                                    ($member->staff->last_name ?? '')
                                                );
                                            }
                                        @endphp

                                        <option value="{{ $member->id }}"
                                            {{ old('library_member_id') == $member->id ? 'selected' : '' }}>

                                            {{ $member->member_number }}
                                            — {{ $holderName }}

                                            @if ($member->max_books)
                                                — Limit: {{ $member->max_books }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('library_member_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @if ($members->isEmpty())
                                    <div class="text-danger small mt-2">
                                        No active library members are available.
                                    </div>
                                @else
                                    <div class="form-text">
                                        Only active members from your school are shown.
                                    </div>
                                @endif
                            </div>

                            {{-- Issued Date --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Issue Date <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="issued_date"
                                       value="{{ old('issued_date', now()->format('Y-m-d')) }}"
                                       class="form-control @error('issued_date') is-invalid @enderror"
                                       required>

                                @error('issued_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Due Date --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Due Date <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="due_date"
                                       value="{{ old('due_date', now()->addDays(14)->format('Y-m-d')) }}"
                                       min="{{ old('issued_date', now()->format('Y-m-d')) }}"
                                       class="form-control @error('due_date') is-invalid @enderror"
                                       required>

                                @error('due_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Due Time --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Due Time
                                </label>

                                <input type="time"
                                       id="due_time"
                                       class="form-control">

                                <div class="form-text">
                                    Optional. If omitted, the due date remains date-only.
                                </div>
                            </div>

                            {{-- Hidden Due At --}}
                            <input type="hidden"
                                   name="due_at"
                                   id="due_at"
                                   value="{{ old('due_at') }}">

                        </div>

                    </div>
                </div>

            </div>

            {{-- Information --}}
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Issue Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="alert alert-info mb-0">

                            <h6 class="alert-heading">
                                <i class="mdi mdi-information-outline me-1"></i>
                                Library Issue Rules
                            </h6>

                            <ul class="mb-0 ps-3">
                                <li class="mb-2">
                                    Only available physical copies can be issued.
                                </li>

                                <li class="mb-2">
                                    Only active library members can receive books.
                                </li>

                                <li class="mb-2">
                                    A member cannot exceed their maximum book limit.
                                </li>

                                <li>
                                    A member cannot hold another active copy of
                                    the same book.
                                </li>
                            </ul>

                        </div>

                    </div>
                </div>

            </div>

        </div>

        {{-- Actions --}}
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.library.issues.index') }}"
                       class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary"
                            {{ $copies->isEmpty() || $members->isEmpty() ? 'disabled' : '' }}>

                        <i class="mdi mdi-book-arrow-right me-1"></i>
                        Issue Book

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

    const issuedDate = document.querySelector('[name="issued_date"]');
    const dueDate = document.querySelector('[name="due_date"]');
    const dueTime = document.getElementById('due_time');
    const dueAt = document.getElementById('due_at');

    function syncDueAt() {

        if (!dueDate || !dueAt) {
            return;
        }

        if (dueDate.value && dueTime && dueTime.value) {
            dueAt.value = dueDate.value + ' ' + dueTime.value + ':00';
        } else {
            dueAt.value = '';
        }
    }

    function updateDueDateMinimum() {

        if (!issuedDate || !dueDate) {
            return;
        }

        if (issuedDate.value) {
            dueDate.min = issuedDate.value;

            if (dueDate.value && dueDate.value < issuedDate.value) {
                dueDate.value = issuedDate.value;
            }
        }

        syncDueAt();
    }

    issuedDate?.addEventListener('change', updateDueDateMinimum);
    dueDate?.addEventListener('change', syncDueAt);
    dueTime?.addEventListener('change', syncDueAt);

    updateDueDateMinimum();
    syncDueAt();
});
</script>
@endsection