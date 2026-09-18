@extends('backend.layout.default')

@section('title', 'Create Library Reservation')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-sm-0 font-size-18">Create Library Reservation</h4>
                    <p class="text-muted mb-0 mt-1">
                        Reserve a book for an active library member.
                    </p>
                </div>

                <a href="{{ route('admin.library.reservations.index') }}"
                   class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i>
                    Back to Reservations
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.library.reservations.store') }}">
        @csrf

        <div class="row">

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-bookmark-plus me-1"></i>
                            Reservation Details
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label">
                                    Book <span class="text-danger">*</span>
                                </label>

                                <select name="book_id"
                                        class="form-select"
                                        required>
                                    <option value="">Select Book</option>

                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}"
                                            @selected((string) old('book_id') === (string) $book->id)>
                                            {{ $book->title }}
                                            @if($book->isbn)
                                                — ISBN: {{ $book->isbn }}
                                            @endif
                                            — Available: {{ $book->available_quantity ?? 0 }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    Library Member <span class="text-danger">*</span>
                                </label>

                                <select name="library_member_id"
                                        class="form-select"
                                        required>
                                    <option value="">Select Active Member</option>

                                    @foreach($members as $member)
                                        @php
                                            $student = $member->student;
                                            $staff = $member->staff;

                                            $name = $student
                                                ? trim(
                                                    $student->first_name . ' ' .
                                                    ($student->middle_name ?? '') . ' ' .
                                                    $student->last_name
                                                )
                                                : ($staff
                                                    ? trim(
                                                        ($staff->first_name ?? '') . ' ' .
                                                        ($staff->last_name ?? '')
                                                    )
                                                    : 'Unknown');
                                        @endphp

                                        <option value="{{ $member->id }}"
                                            @selected((string) old('library_member_id') === (string) $member->id)>
                                            {{ $member->member_number }} — {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Reserved At</label>

                                <input type="datetime-local"
                                       name="reserved_at"
                                       value="{{ old('reserved_at', now()->format('Y-m-d\TH:i')) }}"
                                       class="form-control">

                                <small class="text-muted">
                                    Leave unchanged to use the current date and time.
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Expires At</label>

                                <input type="datetime-local"
                                       name="expires_at"
                                       value="{{ old('expires_at') }}"
                                       class="form-control">

                                <small class="text-muted">
                                    Optional. The reservation becomes expired after this time.
                                </small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>

                                <textarea name="notes"
                                          rows="4"
                                          class="form-control"
                                          maxlength="2000"
                                          placeholder="Optional reservation notes">{{ old('notes') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.library.reservations.index') }}"
                               class="btn btn-light">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="bx bx-bookmark-plus me-1"></i>
                                Create Reservation
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-4">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-info-circle me-1"></i>
                            Reservation Rules
                        </h5>
                    </div>

                    <div class="card-body">
                        <ul class="mb-0 ps-3">
                            <li class="mb-2">
                                Only active library members can reserve books.
                            </li>
                            <li class="mb-2">
                                The book and member must belong to the current school.
                            </li>
                            <li class="mb-2">
                                A member cannot have duplicate active reservations
                                for the same book.
                            </li>
                            <li>
                                An active reservation can later be fulfilled or cancelled.
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection
