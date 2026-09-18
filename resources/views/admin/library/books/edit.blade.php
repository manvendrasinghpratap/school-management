@extends('backend.layout.default')

@section('title', 'Edit Library Book')

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
                        Edit Book
                    </h4>

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.library.books.index') }}">
                                Library
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.library.books.index') }}">
                                Books
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit
                        </li>

                    </ol>

                </div>


                <div class="d-flex gap-2">

                    {{-- Back --}}
                    <a href="{{ route('admin.library.books.show', $book) }}"
                       class="btn btn-light">

                        <i class="bx bx-arrow-back me-1"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <div class="fw-semibold mb-2">

                <i class="bx bx-error-circle me-1"></i>

                Please correct the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>

    @endif


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


    <div class="row">

        {{-- =====================================================
            MAIN FORM
        ====================================================== --}}
        <div class="col-xl-8 col-lg-8">

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Book Information
                    </h5>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.library.books.update', $book) }}">

                        @csrf

                        @method('PUT')


                        <div class="row">


                            {{-- =================================================
                                TITLE
                            ================================================== --}}
                            <div class="col-md-8 mb-3">

                                <label for="title"
                                       class="form-label">

                                    Book Title

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="text"
                                       name="title"
                                       id="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $book->title) }}"
                                       maxlength="255"
                                       required>

                                @error('title')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                ISBN
                            ================================================== --}}
                            <div class="col-md-4 mb-3">

                                <label for="isbn"
                                       class="form-label">

                                    ISBN

                                </label>


                                <input type="text"
                                       name="isbn"
                                       id="isbn"
                                       class="form-control @error('isbn') is-invalid @enderror"
                                       value="{{ old('isbn', $book->isbn) }}"
                                       maxlength="100">

                                @error('isbn')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                CATEGORY
                            ================================================== --}}
                            <div class="col-md-6 mb-3">

                                <label for="category_id"
                                       class="form-label">

                                    Category

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="category_id"
                                        id="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Category
                                    </option>


                                    @foreach($categories as $category)

                                        <option value="{{ $category->id }}"
                                            @selected(
                                                (string) old(
                                                    'category_id',
                                                    $book->category_id
                                                ) === (string) $category->id
                                            )>

                                            {{ $category->name }}

                                            @if($category->code)

                                                ({{ $category->code }})

                                            @endif

                                        </option>

                                    @endforeach

                                </select>


                                @error('category_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                PUBLISHER
                            ================================================== --}}
                            <div class="col-md-6 mb-3">

                                <label for="publisher"
                                       class="form-label">

                                    Publisher

                                </label>


                                <select name="publisher"
                                        id="publisher"
                                        class="form-select @error('publisher') is-invalid @enderror">

                                    <option value="">
                                        Select Publisher
                                    </option>


                                    @foreach($publishers as $publisher)

                                        <option value="{{ $publisher->name }}"
                                            @selected(
                                                old(
                                                    'publisher',
                                                    $book->publisher
                                                ) === $publisher->name
                                            )>

                                            {{ $publisher->name }}

                                        </option>

                                    @endforeach


                                    {{-- =================================================
                                        PRESERVE EXISTING PUBLISHER
                                        IF IT IS NOT IN MASTER LIST
                                    ================================================== --}}
                                    @if(
                                        $book->publisher &&
                                        !$publishers->contains(
                                            'name',
                                            $book->publisher
                                        )
                                    )

                                        <option value="{{ $book->publisher }}"
                                            @selected(
                                                old(
                                                    'publisher',
                                                    $book->publisher
                                                ) === $book->publisher
                                            )>

                                            {{ $book->publisher }}

                                        </option>

                                    @endif

                                </select>


                                @error('publisher')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <small class="text-muted">

                                    Publisher is stored as text in the current
                                    <code>books</code> table.

                                </small>

                            </div>


                            {{-- =================================================
                                AUTHOR TEXT
                            ================================================== --}}
                            <div class="col-md-6 mb-3">

                                <label for="author"
                                       class="form-label">

                                    Author

                                </label>


                                <input type="text"
                                       name="author"
                                       id="author"
                                       class="form-control @error('author') is-invalid @enderror"
                                       value="{{ old('author', $book->author) }}"
                                       maxlength="255">


                                @error('author')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <small class="text-muted">

                                    Primary/display author name.

                                </small>

                            </div>


                            {{-- =================================================
                                LIBRARY AUTHORS
                            ================================================== --}}
                            <div class="col-md-6 mb-3">

                                <label for="author_ids"
                                       class="form-label">

                                    Library Authors

                                </label>


                                @php

                                    $selectedAuthorIds = old(
                                        'author_ids',
                                        $selectedAuthors ?? []
                                    );

                                    $selectedAuthorIds = array_map(
                                        'intval',
                                        is_array($selectedAuthorIds)
                                            ? $selectedAuthorIds
                                            : []
                                    );

                                @endphp


                                <select name="author_ids[]"
                                        id="author_ids"
                                        class="form-select @error('author_ids') is-invalid @enderror"
                                        multiple
                                        size="5">

                                    @foreach($authors as $author)

                                        <option value="{{ $author->id }}"
                                            @selected(
                                                in_array(
                                                    (int) $author->id,
                                                    $selectedAuthorIds,
                                                    true
                                                )
                                            )>

                                            {{ $author->name }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('author_ids')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror


                                @error('author_ids.*')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <small class="text-muted">

                                    Hold Ctrl/Cmd to select multiple authors.

                                </small>

                            </div>


                            {{-- =================================================
                                SHELF LOCATION
                            ================================================== --}}
                            <div class="col-md-6 mb-3">

                                <label for="shelf_location"
                                       class="form-label">

                                    Shelf Location

                                </label>


                                <input type="text"
                                       name="shelf_location"
                                       id="shelf_location"
                                       class="form-control @error('shelf_location') is-invalid @enderror"
                                       value="{{ old('shelf_location', $book->shelf_location) }}"
                                       maxlength="255">


                                @error('shelf_location')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                TOTAL QUANTITY
                            ================================================== --}}
                            <div class="col-md-6 mb-3">

                                <label for="quantity"
                                       class="form-label">

                                    Total Quantity

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="number"
                                       name="quantity"
                                       id="quantity"
                                       min="0"
                                       step="1"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       value="{{ old('quantity', $book->quantity ?? 0) }}"
                                       required>


                                @error('quantity')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <small class="text-muted">

                                    Total physical quantity of this book.

                                </small>

                            </div>


                            {{-- =================================================
                                AVAILABLE QUANTITY
                            ================================================== --}}
                            <div class="col-md-6 mb-3">

                                <label for="available_quantity"
                                       class="form-label">

                                    Available Quantity

                                </label>


                                <input type="text"
                                       id="available_quantity"
                                       class="form-control"
                                       value="{{ $book->available_quantity ?? 0 }}"
                                       readonly>


                                <small class="text-muted">

                                    Calculated automatically from the current
                                    inventory.

                                </small>

                            </div>


                            {{-- =================================================
                                CURRENT CATEGORY
                            ================================================== --}}
                            <div class="col-md-6 mb-3">

                                <label for="current_category"
                                       class="form-label">

                                    Current Category

                                </label>


                                <input type="text"
                                       id="current_category"
                                       class="form-control"
                                       value="{{ $book->libraryCategory?->name ?? ($book->category ?: 'Not assigned') }}"
                                       readonly>

                            </div>


                        </div>


                        {{-- =====================================================
                            FORM BUTTONS
                        ====================================================== --}}
                        <div class="border-top pt-3 mt-3">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bx bx-save me-1"></i>

                                Update Book

                            </button>


                            <a href="{{ route('admin.library.books.show', $book) }}"
                               class="btn btn-light">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SIDE INFORMATION
        ====================================================== --}}
        <div class="col-xl-4 col-lg-4">

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Current Book
                    </h5>

                </div>


                <div class="card-body">


                    {{-- Book --}}
                    <div class="mb-4">

                        <h5 class="mb-1">

                            {{ $book->title }}

                        </h5>


                        <span class="text-muted">

                            Book ID #{{ $book->id }}

                        </span>

                    </div>


                    {{-- ISBN --}}
                    <div class="mb-3">

                        <div class="text-muted small mb-1">

                            ISBN

                        </div>


                        <div class="fw-semibold">

                            {{ $book->isbn ?: '—' }}

                        </div>

                    </div>


                    {{-- Category --}}
                    <div class="mb-3">

                        <div class="text-muted small mb-1">

                            Category

                        </div>


                        <div class="fw-semibold">

                            @if($book->libraryCategory)

                                {{ $book->libraryCategory->name }}

                                @if($book->libraryCategory->code)

                                    <span class="text-muted">
                                        ({{ $book->libraryCategory->code }})
                                    </span>

                                @endif

                            @elseif($book->category)

                                {{ $book->category }}

                            @else

                                <span class="text-muted">
                                    Not assigned
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Publisher --}}
                    <div class="mb-3">

                        <div class="text-muted small mb-1">

                            Publisher

                        </div>


                        <div class="fw-semibold">

                            {{ $book->publisher ?: '—' }}

                        </div>

                    </div>


                    {{-- Author --}}
                    <div class="mb-3">

                        <div class="text-muted small mb-1">

                            Author

                        </div>


                        <div class="fw-semibold">

                            @if($book->author)

                                {{ $book->author }}

                            @elseif($book->authors && $book->authors->count())

                                {{ $book->authors->pluck('name')->join(', ') }}

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Total Quantity --}}
                    <div class="mb-3">

                        <div class="text-muted small mb-1">

                            Total Quantity

                        </div>


                        <div>

                            <span class="badge bg-secondary-subtle text-secondary">

                                {{ $book->quantity ?? 0 }}

                            </span>

                        </div>

                    </div>


                    {{-- Available Quantity --}}
                    <div class="mb-3">

                        <div class="text-muted small mb-1">

                            Available Quantity

                        </div>


                        <div>

                            <span class="badge bg-success-subtle text-success">

                                {{ $book->available_quantity ?? 0 }}

                            </span>

                        </div>

                    </div>


                    {{-- Registered Copies --}}
                    <div>

                        <div class="text-muted small mb-1">

                            Registered Copies

                        </div>


                        <div>

                            <span class="badge bg-info-subtle text-info">

                                {{ $book->copies->count() }}

                            </span>

                        </div>

                    </div>


                </div>

            </div>


            {{-- =====================================================
                QUANTITY INFORMATION
            ====================================================== --}}
            <div class="alert alert-info">

                <div class="d-flex">

                    <div class="me-2">

                        <i class="bx bx-info-circle fs-5"></i>

                    </div>


                    <div>

                        <strong>
                            Quantity:
                        </strong>

                        <div class="mt-1">

                            If this book already has issued, lost,
                            damaged, reserved, or otherwise unavailable
                            copies, the system preserves those unavailable
                            quantities when the total quantity is changed.

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                CATEGORY INFORMATION
            ====================================================== --}}
            <div class="alert alert-light border">

                <div class="d-flex">

                    <div class="me-2">

                        <i class="bx bx-category fs-5"></i>

                    </div>


                    <div>

                        <strong>
                            Category:
                        </strong>

                        <div class="mt-1 text-muted">

                            The selected category is stored using
                            <code>category_id</code>.

                            The existing <code>category</code> text column
                            is maintained for compatibility with the current
                            database structure.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection