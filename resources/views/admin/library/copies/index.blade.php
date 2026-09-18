@extends('backend.layout.default')

@section('title', $book->title . ' - Copies')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h4 class="mb-1">
                {{ $book->title }} — Copies
            </h4>

            <div class="text-muted">

                ISBN:
                <strong>
                    {{ $book->isbn ?: '—' }}
                </strong>

                @if($book->libraryCategory)

                    <span class="mx-2">|</span>

                    Category:
                    <strong>
                        {{ $book->libraryCategory->name }}
                    </strong>

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.library.books.show',
                $book
            ) }}"
               class="btn btn-light">

                <i class="ri-arrow-left-line align-middle me-1"></i>

                Back to Book

            </a>

        </div>

    </div>


    {{-- =========================================================
         ALERTS
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
         INVENTORY SUMMARY
    ========================================================== --}}

    <div class="row mb-3">

        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Total Copies
                    </div>

                    <h4 class="mb-0">
                        {{ $book->quantity ?? 0 }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Available Copies
                    </div>

                    <h4 class="mb-0 text-success">
                        {{ $book->available_quantity ?? 0 }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="text-muted mb-1">
                        Unavailable Copies
                    </div>

                    <h4 class="mb-0 text-danger">

                        {{
                            max(
                                0,
                                (int)($book->quantity ?? 0)
                                -
                                (int)($book->available_quantity ?? 0)
                            )
                        }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ADD COPY
    ========================================================== --}}

    @can('library.create')

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="card-title mb-1">
                    Add Physical Book Copy
                </h5>

                <p class="text-muted mb-0">
                    Register an individual physical copy of this book.
                </p>

            </div>


            <div class="card-body">

                <form method="POST"
                      action="{{ route(
                          'admin.library.books.copies.store',
                          $book
                      ) }}">

                    @csrf


                    <div class="row g-3">


                        {{-- ACCESSION NUMBER --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Accession Number
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="accession_number"
                                value="{{ old('accession_number') }}"
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


                        {{-- BARCODE --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Barcode
                            </label>

                            <input
                                type="text"
                                name="barcode"
                                value="{{ old('barcode') }}"
                                class="form-control @error('barcode') is-invalid @enderror"
                                placeholder="e.g. 8901234567890"
                            >

                            @error('barcode')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PURCHASE DATE --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Purchase Date
                            </label>

                            <input
                                type="date"
                                name="purchase_date"
                                value="{{ old('purchase_date') }}"
                                class="form-control @error('purchase_date') is-invalid @enderror"
                            >

                            @error('purchase_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PURCHASE PRICE --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Purchase Price
                            </label>

                            <input
                                type="number"
                                name="purchase_price"
                                value="{{ old('purchase_price') }}"
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


                        {{-- CONDITION --}}

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
                                    @selected(old('condition_status') === 'new')>
                                    New
                                </option>

                                <option value="good"
                                    @selected(old('condition_status') === 'good')>
                                    Good
                                </option>

                                <option value="fair"
                                    @selected(old('condition_status') === 'fair')>
                                    Fair
                                </option>

                                <option value="damaged"
                                    @selected(old('condition_status') === 'damaged')>
                                    Damaged
                                </option>

                                <option value="lost"
                                    @selected(old('condition_status') === 'lost')>
                                    Lost
                                </option>

                            </select>

                            @error('condition_status')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- STATUS --}}

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

                                <option value="">
                                    Select status
                                </option>

                                <option value="available"
                                    @selected(old('status', 'available') === 'available')>
                                    Available
                                </option>

                                <option value="issued"
                                    @selected(old('status') === 'issued')>
                                    Issued
                                </option>

                                <option value="reserved"
                                    @selected(old('status') === 'reserved')>
                                    Reserved
                                </option>

                                <option value="lost"
                                    @selected(old('status') === 'lost')>
                                    Lost
                                </option>

                                <option value="damaged"
                                    @selected(old('status') === 'damaged')>
                                    Damaged
                                </option>

                                <option value="maintenance"
                                    @selected(old('status') === 'maintenance')>
                                    Maintenance
                                </option>

                            </select>

                            @error('status')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- NOTES --}}

                        <div class="col-md-12">

                            <label class="form-label">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                rows="2"
                                class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Optional notes about this physical copy..."
                            >{{ old('notes') }}</textarea>

                            @error('notes')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- SUBMIT --}}

                        <div class="col-md-12">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="ri-add-line align-middle me-1"></i>

                                Add Copy

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endcan


    {{-- =========================================================
         COPIES LIST
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="card-title mb-1">
                        Physical Copies
                    </h5>

                    <p class="text-muted mb-0">
                        Individual physical copies registered for this book.
                    </p>

                </div>

                @if(method_exists($copies, 'total'))

                    <span class="badge bg-primary-subtle text-primary">

                        {{ $copies->total() }}
                        copy{{ $copies->total() == 1 ? '' : 'ies' }}

                    </span>

                @endif

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Accession
                            </th>

                            <th>
                                Barcode
                            </th>

                            <th>
                                Condition
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Purchase Date
                            </th>

                            <th>Price</th>
                            <th class="text-end">Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($copies as $copy)

                            <tr>

                                <td>
                                    {{ $copies->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $copy->accession_number }}
                                    </strong>

                                </td>


                                <td>

                                    {{ $copy->barcode ?: '—' }}

                                </td>


                                <td>

                                    @php
                                        $condition = strtolower(
                                            (string) $copy->condition_status
                                        );
                                    @endphp

                                    @if($condition === 'new')

                                        <span class="badge bg-success-subtle text-success">
                                            New
                                        </span>

                                    @elseif($condition === 'good')

                                        <span class="badge bg-primary-subtle text-primary">
                                            Good
                                        </span>

                                    @elseif($condition === 'fair')

                                        <span class="badge bg-warning-subtle text-warning">
                                            Fair
                                        </span>

                                    @elseif($condition === 'damaged')

                                        <span class="badge bg-danger-subtle text-danger">
                                            Damaged
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark">
                                            {{ ucfirst($condition ?: '—') }}
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @php
                                        $status = strtolower(
                                            (string) $copy->status
                                        );
                                    @endphp

                                    @if($status === 'available')

                                        <span class="badge bg-success">
                                            Available
                                        </span>

                                    @elseif($status === 'issued')

                                        <span class="badge bg-warning">
                                            Issued
                                        </span>

                                    @elseif($status === 'reserved')

                                        <span class="badge bg-info">
                                            Reserved
                                        </span>

                                    @elseif($status === 'lost')

                                        <span class="badge bg-danger">
                                            Lost
                                        </span>

                                    @elseif($status === 'damaged')

                                        <span class="badge bg-danger">
                                            Damaged
                                        </span>

                                    @elseif($status === 'maintenance')

                                        <span class="badge bg-secondary">
                                            Maintenance
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark">
                                            {{ ucfirst($status ?: '—') }}
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($copy->purchase_date)

                                        {{ \Carbon\Carbon::parse(
                                            $copy->purchase_date
                                        )->format('d-m-Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($copy->purchase_price !== null)

                                        {{ number_format(
                                            (float) $copy->purchase_price,
                                            2
                                        ) }}

                                    @else

                                        —

                                    @endif

                                </td>
                                <td class="text-end">

    @can('library.update')

        <a href="{{ route(
            'admin.library.books.copies.edit',
            [$book, $copy]
        ) }}"
           class="btn btn-sm btn-outline-primary">

            <i class="ri-edit-line"></i>
            Edit

        </a>

    @endcan


    @can('library.delete')

        <form method="POST"
              action="{{ route(
                  'admin.library.books.copies.destroy',
                  [$book, $copy]
              ) }}"
              class="d-inline"
              onsubmit="return confirm('Are you sure you want to delete this physical copy?');">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-sm btn-outline-danger">

                <i class="ri-delete-bin-line"></i>
                Delete

            </button>

        </form>

    @endcan

</td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="ri-book-open-line fs-2 d-block mb-2"></i>

                                        <div class="fw-semibold">
                                            No Copies Registered
                                        </div>

                                        <small>
                                            Add a physical copy using the form above.
                                        </small>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PAGINATION --}}

        @if($copies->hasPages())

            <div class="card-footer">

                {{ $copies->links() }}

            </div>

        @endif

    </div>

</div>

@endsection