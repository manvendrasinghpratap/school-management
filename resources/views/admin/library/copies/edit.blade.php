@extends('backend.layout.default')

@section('title', 'Edit Copy - ' . $book->title)

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h4 class="mb-1">
                Edit Physical Book Copy
            </h4>

            <div class="text-muted">

                <strong>{{ $book->title }}</strong>

                @if($book->isbn)
                    <span class="mx-2">|</span>
                    ISBN: {{ $book->isbn }}
                @endif

                @if($book->libraryCategory)
                    <span class="mx-2">|</span>
                    Category: {{ $book->libraryCategory->name }}
                @endif

            </div>

        </div>

        <div>

            <a href="{{ route(
                'admin.library.books.copies.index',
                $book
            ) }}"
               class="btn btn-light">

                <i class="ri-arrow-left-line align-middle me-1"></i>
                Back to Copies

            </a>

        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         BOOK SUMMARY
    ========================================================== --}}

    <div class="row mb-3">

        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Book
                    </div>

                    <h5 class="mb-0">
                        {{ $book->title }}
                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Accession Number
                    </div>

                    <h5 class="mb-0">
                        {{ $copy->accession_number }}
                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Current Status
                    </div>

                    @php
                        $currentStatus = strtolower(
                            (string) $copy->status
                        );
                    @endphp

                    @if($currentStatus === 'available')

                        <span class="badge bg-success">
                            Available
                        </span>

                    @elseif($currentStatus === 'issued')

                        <span class="badge bg-warning">
                            Issued
                        </span>

                    @elseif($currentStatus === 'reserved')

                        <span class="badge bg-info">
                            Reserved
                        </span>

                    @elseif($currentStatus === 'lost')

                        <span class="badge bg-danger">
                            Lost
                        </span>

                    @elseif($currentStatus === 'damaged')

                        <span class="badge bg-danger">
                            Damaged
                        </span>

                    @elseif($currentStatus === 'maintenance')

                        <span class="badge bg-secondary">
                            Maintenance
                        </span>

                    @else

                        <span class="badge bg-light text-dark">
                            {{ ucfirst($currentStatus ?: '—') }}
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         EDIT FORM
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-1">
                Edit Physical Book Copy
            </h5>

            <p class="text-muted mb-0">
                Update the information for this physical copy.
            </p>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.library.books.copies.update',
                      [$book, $copy]
                  ) }}">

                @csrf

                @method('PUT')


                <div class="row g-3">


                    {{-- =================================================
                         ACCESSION NUMBER
                    ================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Accession Number
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="accession_number"
                            value="{{ old(
                                'accession_number',
                                $copy->accession_number
                            ) }}"
                            class="form-control @error('accession_number') is-invalid @enderror"
                            placeholder="e.g. GEO-001"
                            required
                        >

                        @error('accession_number')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         BARCODE
                    ================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Barcode
                        </label>

                        <input
                            type="text"
                            name="barcode"
                            value="{{ old(
                                'barcode',
                                $copy->barcode
                            ) }}"
                            class="form-control @error('barcode') is-invalid @enderror"
                            placeholder="e.g. 8901234567890"
                        >

                        @error('barcode')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         PURCHASE DATE
                    ================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Purchase Date
                        </label>

                        <input
                            type="date"
                            name="purchase_date"
                            value="{{ old(
                                'purchase_date',
                                $copy->purchase_date
                                    ? \Carbon\Carbon::parse(
                                        $copy->purchase_date
                                    )->format('Y-m-d')
                                    : ''
                            ) }}"
                            class="form-control @error('purchase_date') is-invalid @enderror"
                        >

                        @error('purchase_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         PURCHASE PRICE
                    ================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Purchase Price
                        </label>

                        <input
                            type="number"
                            name="purchase_price"
                            value="{{ old(
                                'purchase_price',
                                $copy->purchase_price ?? 0
                            ) }}"
                            class="form-control @error('purchase_price') is-invalid @enderror"
                            placeholder="0.00"
                            min="0"
                            step="0.01"
                        >

                        @error('purchase_price')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         CONDITION
                    ================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Condition
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="condition_status"
                            class="form-select @error('condition_status') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select condition
                            </option>

                            <option value="new"
                                @selected(
                                    old(
                                        'condition_status',
                                        $copy->condition_status
                                    ) === 'new'
                                )>
                                New
                            </option>

                            <option value="good"
                                @selected(
                                    old(
                                        'condition_status',
                                        $copy->condition_status
                                    ) === 'good'
                                )>
                                Good
                            </option>

                            <option value="fair"
                                @selected(
                                    old(
                                        'condition_status',
                                        $copy->condition_status
                                    ) === 'fair'
                                )>
                                Fair
                            </option>

                            <option value="damaged"
                                @selected(
                                    old(
                                        'condition_status',
                                        $copy->condition_status
                                    ) === 'damaged'
                                )>
                                Damaged
                            </option>

                            <option value="lost"
                                @selected(
                                    old(
                                        'condition_status',
                                        $copy->condition_status
                                    ) === 'lost'
                                )>
                                Lost
                            </option>

                        </select>

                        @error('condition_status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         STATUS
                    ================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="available"
                                @selected(
                                    old(
                                        'status',
                                        $copy->status
                                    ) === 'available'
                                )>
                                Available
                            </option>

                            <option value="issued"
                                @selected(
                                    old(
                                        'status',
                                        $copy->status
                                    ) === 'issued'
                                )>
                                Issued
                            </option>

                            <option value="reserved"
                                @selected(
                                    old(
                                        'status',
                                        $copy->status
                                    ) === 'reserved'
                                )>
                                Reserved
                            </option>

                            <option value="lost"
                                @selected(
                                    old(
                                        'status',
                                        $copy->status
                                    ) === 'lost'
                                )>
                                Lost
                            </option>

                            <option value="damaged"
                                @selected(
                                    old(
                                        'status',
                                        $copy->status
                                    ) === 'damaged'
                                )>
                                Damaged
                            </option>

                            <option value="maintenance"
                                @selected(
                                    old(
                                        'status',
                                        $copy->status
                                    ) === 'maintenance'
                                )>
                                Maintenance
                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         NOTES
                    ================================================== --}}

                    <div class="col-md-12">

                        <label class="form-label">
                            Notes
                        </label>

                        <textarea
                            name="notes"
                            rows="4"
                            class="form-control @error('notes') is-invalid @enderror"
                            placeholder="Optional notes about this physical copy..."
                        >{{ old('notes', $copy->notes) }}</textarea>

                        @error('notes')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         ACTION BUTTONS
                    ================================================== --}}

                    <div class="col-md-12">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="ri-save-line align-middle me-1"></i>

                            Update Copy

                        </button>


                        <a
                            href="{{ route(
                                'admin.library.books.copies.index',
                                $book
                            ) }}"
                            class="btn btn-light"
                        >

                            Cancel

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection