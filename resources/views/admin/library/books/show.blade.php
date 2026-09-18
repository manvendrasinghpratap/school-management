@extends('backend.layout.default')

@section('title', 'Library Book')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-flex align-items-center justify-content-between">

                <div>

                    <h4 class="mb-1">
                        {{ $book->title }}
                    </h4>

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.library.books.index') }}">
                                Library Books
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            {{ $book->title }}
                        </li>

                    </ol>

                </div>


                <div class="d-flex gap-2">

                    {{-- Back --}}
                    <a href="{{ route('admin.library.books.index') }}"
                       class="btn btn-light">

                        <i class="bx bx-arrow-back me-1"></i>
                        Back

                    </a>


                    {{-- Edit --}}
                    @can('library.update')

                        <a href="{{ route('admin.library.books.edit', $book) }}"
                           class="btn btn-primary">

                            <i class="bx bx-edit me-1"></i>
                            Edit

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

            <i class="bx bx-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="bx bx-error-circle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


    {{-- =========================================================
        BOOK INFORMATION
    ========================================================== --}}
    <div class="card mb-3">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Book Information
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- =================================================
                    TITLE
                ================================================== --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Title
                    </div>

                    <div class="fw-semibold">
                        {{ $book->title ?: '—' }}
                    </div>

                </div>


                {{-- =================================================
                    ISBN
                ================================================== --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        ISBN
                    </div>

                    <div class="fw-semibold">
                        {{ $book->isbn ?: '—' }}
                    </div>

                </div>


                {{-- =================================================
                    CATEGORY
                    IMPORTANT:
                    Use libraryCategory because books.category is
                    an existing text column.
                ================================================== --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Category
                    </div>

                    <div>

                        @if($book->libraryCategory)

                            <span class="badge bg-primary-subtle text-primary">

                                {{ $book->libraryCategory->name }}

                                @if($book->libraryCategory->code)

                                    ({{ $book->libraryCategory->code }})

                                @endif

                            </span>

                        @elseif($book->category)

                            {{-- Legacy category text fallback --}}

                            <span class="badge bg-secondary-subtle text-secondary">

                                {{ $book->category }}

                            </span>

                        @else

                            <span class="text-muted">
                                Not assigned
                            </span>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    AUTHOR
                ================================================== --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Author
                    </div>

                    <div class="fw-semibold">

                        @if($book->authors && $book->authors->count())

                            {{ $book->authors->pluck('name')->join(', ') }}

                        @elseif($book->author)

                            {{ $book->author }}

                        @else

                            <span class="text-muted">
                                —
                            </span>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    PUBLISHER
                    IMPORTANT:
                    publisher is a TEXT column, not a relationship.
                ================================================== --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Publisher
                    </div>

                    <div class="fw-semibold">

                        {{ $book->publisher ?: '—' }}

                    </div>

                </div>


                {{-- =================================================
                    SHELF LOCATION
                ================================================== --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Shelf Location
                    </div>

                    <div class="fw-semibold">

                        {{ $book->shelf_location ?: '—' }}

                    </div>

                </div>


                {{-- =================================================
                    TOTAL QUANTITY
                ================================================== --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Total Quantity
                    </div>

                    <div>

                        <span class="badge bg-secondary-subtle text-secondary fs-6">

                            {{ $book->quantity ?? 0 }}

                        </span>

                    </div>

                </div>


                {{-- =================================================
                    AVAILABLE QUANTITY
                ================================================== --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Available
                    </div>

                    <div>

                        <span class="badge bg-success-subtle text-success fs-6">

                            {{ $book->available_quantity ?? 0 }}

                        </span>

                    </div>

                </div>


                {{-- =================================================
                    UNAVAILABLE QUANTITY
                ================================================== --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Unavailable
                    </div>

                    <div>

                        @php

                            $quantity = (int) (
                                $book->quantity ?? 0
                            );

                            $available = (int) (
                                $book->available_quantity ?? 0
                            );

                            $unavailable = max(
                                0,
                                $quantity - $available
                            );

                        @endphp


                        <span class="badge bg-warning-subtle text-warning fs-6">

                            {{ $unavailable }}

                        </span>

                    </div>

                </div>


            </div>

        </div>

    </div>


    {{-- =========================================================
        AUTHORS
    ========================================================== --}}
    @if($book->authors && $book->authors->count())

        <div class="card mb-3">

            <div class="card-header">

                <h5 class="card-title mb-0">
                    Authors
                </h5>

            </div>


            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">

                    @foreach($book->authors as $author)

                        <span class="badge bg-primary-subtle text-primary">

                            {{ $author->name }}

                        </span>

                    @endforeach

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        BOOK COPIES
    ========================================================== --}}
    <div class="card">

        <div class="card-header d-flex align-items-center justify-content-between">

            <div>

                <h5 class="card-title mb-1">
                    Book Copies
                </h5>

                <p class="text-muted mb-0">
                    Physical copies registered for this book.
                </p>

            </div>


            @can('library.create')

                <a href="{{ route('admin.library.books.copies.index', $book) }}"
                   class="btn btn-primary btn-sm">

                    <i class="bx bx-plus me-1"></i>

                    Manage Copies

                </a>

            @endcan

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 60px;">
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

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($book->copies as $copy)

                            <tr>

                                {{-- Number --}}
                                <td>

                                    {{ $loop->iteration }}

                                </td>


                                {{-- Accession --}}
                                <td>

                                    <strong>
                                        {{ $copy->accession_number }}
                                    </strong>

                                </td>


                                {{-- Barcode --}}
                                <td>

                                    {{ $copy->barcode ?: '—' }}

                                </td>


                                {{-- Condition --}}
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

                                    @elseif($condition === 'lost')

                                        <span class="badge bg-dark-subtle text-dark">
                                            Lost
                                        </span>

                                    @else

                                        {{ ucfirst(
                                            $copy->condition_status ?: '—'
                                        ) }}

                                    @endif

                                </td>


                                {{-- Status --}}
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

                                        <span class="badge bg-primary">
                                            Issued
                                        </span>

                                    @elseif($status === 'reserved')

                                        <span class="badge bg-warning text-dark">
                                            Reserved
                                        </span>

                                    @elseif($status === 'lost')

                                        <span class="badge bg-dark">
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

                                        {{ ucfirst(
                                            $copy->status ?: '—'
                                        ) }}

                                    @endif

                                </td>


                                {{-- Purchase Date --}}
                                <td>

                                    @if($copy->purchase_date)

                                        {{ \Carbon\Carbon::parse(
                                            $copy->purchase_date
                                        )->format('d M Y') }}

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bx bx-book-open fs-1 d-block mb-2"></i>

                                        <h5 class="mb-1">
                                            No Copies Registered
                                        </h5>

                                        <p class="mb-0">
                                            No physical copies have been registered for this book yet.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection