@extends('backend.layout.default')

@section('title', 'Library Books')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">Library Books</h4>
            <p class="text-muted mb-0">
                Manage all library books for your school.
            </p>
        </div>

        @can('library.create')
            <a href="{{ route('admin.library.books.create') }}"
               class="btn btn-primary">
                <i class="ri-add-line align-middle me-1"></i>
                Add Book
            </a>
        @endcan

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    @if($errors->any())
        <div class="alert alert-danger">

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- =========================================================
         SEARCH
    ========================================================== --}}

    <div class="card mb-3">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.library.books.index') }}">

                <div class="row g-2 align-items-end">

                    <div class="col-md-6">

                        <label class="form-label">
                            Search Books
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search title, ISBN, author, publisher or category..."
                        >

                    </div>


                    <div class="col-md-auto">

                        <button type="submit"
                                class="btn btn-secondary">

                            <i class="ri-search-line align-middle me-1"></i>
                            Search

                        </button>

                    </div>


                    @if(request('search'))

                        <div class="col-md-auto">

                            <a href="{{ route('admin.library.books.index') }}"
                               class="btn btn-light">

                                <i class="ri-close-line align-middle me-1"></i>
                                Clear

                            </a>

                        </div>

                    @endif

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         BOOKS TABLE
    ========================================================== --}}

    <div class="card">

        <div class="card-header bg-transparent">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="card-title mb-1">
                        Library Books
                    </h5>

                    <p class="text-muted mb-0">
                        Physical and catalogued books registered in the library.
                    </p>

                </div>

                @if(method_exists($books, 'total'))

                    <span class="badge bg-primary-subtle text-primary">
                        {{ $books->total() }} book{{ $books->total() == 1 ? '' : 's' }}
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
                                Title
                            </th>

                            <th>
                                ISBN
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Author
                            </th>

                            <th>
                                Publisher
                            </th>

                            <th class="text-center">
                                Available
                            </th>

                            <th class="text-center">
                                Total
                            </th>

                            <th width="220"
                                class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($books as $book)

                            <tr>

                                {{-- =================================================
                                     NUMBER
                                ================================================== --}}

                                <td>

                                    {{ $books->firstItem() + $loop->index }}

                                </td>


                                {{-- =================================================
                                     TITLE
                                ================================================== --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $book->title }}

                                    </div>

                                </td>


                                {{-- =================================================
                                     ISBN
                                ================================================== --}}

                                <td>

                                    @if($book->isbn)

                                        <span class="text-muted">
                                            {{ $book->isbn }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     CATEGORY
                                ================================================== --}}

                                <td>

                                    @if($book->libraryCategory)

                                        <a href="{{ route(
                                            'admin.library.categories.show',
                                            $book->libraryCategory
                                        ) }}"
                                           class="text-decoration-none">

                                            <span class="badge bg-primary-subtle text-primary">

                                                {{ $book->libraryCategory->name }}

                                                @if($book->libraryCategory->code)

                                                    ({{ $book->libraryCategory->code }})

                                                @endif

                                            </span>

                                        </a>

                                    @elseif($book->category)

                                        {{-- Legacy category fallback --}}

                                        <span class="badge bg-light text-dark">

                                            {{ $book->category }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     AUTHORS
                                ================================================== --}}

                                <td>

                                    @if($book->authors && $book->authors->count())

                                        <div class="text-truncate"
                                             style="max-width: 220px;"
                                             title="{{ $book->authors->pluck('name')->join(', ') }}">

                                            {{ $book->authors->pluck('name')->join(', ') }}

                                        </div>

                                    @elseif($book->author)

                                        {{-- Legacy author fallback --}}

                                        {{ $book->author }}

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     PUBLISHER
                                ================================================== --}}

                                <td>

                                    @if($book->publisher)

                                        {{ $book->publisher }}

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     AVAILABLE
                                ================================================== --}}

                                <td class="text-center">

                                    @php
                                        $available = (int) ($book->available_quantity ?? 0);
                                        $quantity = (int) ($book->quantity ?? 0);
                                    @endphp

                                    @if($available > 0)

                                        <span class="badge bg-success-subtle text-success fs-6">

                                            {{ $available }}

                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger fs-6">

                                            0

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     TOTAL
                                ================================================== --}}

                                <td class="text-center">

                                    <span class="badge bg-light text-dark fs-6">

                                        {{ $quantity }}

                                    </span>

                                </td>


                                {{-- =================================================
                                     ACTIONS
                                ================================================== --}}

                                <td>

                                    <div class="d-flex justify-content-center gap-1 flex-wrap">


                                        {{-- VIEW --}}

                                        @can('library.view')

                                            <a href="{{ route(
                                                'admin.library.books.show',
                                                $book
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View Book">

                                                <i class="ri-eye-line"></i>
                                                View

                                            </a>

                                        @endcan


                                        {{-- EDIT --}}

                                        @can('library.update')

                                            <a href="{{ route(
                                                'admin.library.books.edit',
                                                $book
                                            ) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Edit Book">

                                                <i class="ri-edit-line"></i>
                                                Edit

                                            </a>

                                        @endcan


                                        {{-- MANAGE COPIES --}}

                                        @can('library.create')

                                            <a href="{{ route(
                                                'admin.library.books.copies.index',
                                                $book
                                            ) }}"
                                               class="btn btn-sm btn-outline-success"
                                               title="Manage Physical Copies">

                                                <i class="ri-book-2-line"></i>
                                                Copies

                                            </a>

                                        @endcan


                                        {{-- DELETE --}}

                                        @can('library.delete')

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.library.books.destroy',
                                                      $book
                                                  ) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this book?');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete Book">

                                                    <i class="ri-delete-bin-line"></i>

                                                </button>

                                            </form>

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            {{-- =================================================
                                 EMPTY STATE
                            ================================================== --}}

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="py-4">

                                        <div class="avatar-md mx-auto mb-3">

                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">

                                                <i class="ri-book-2-line"></i>

                                            </div>

                                        </div>

                                        <h5 class="mb-2">
                                            No Books Found
                                        </h5>

                                        <p class="text-muted mb-3">

                                            @if(request('search'))

                                                No books match your search criteria.

                                            @else

                                                No books have been registered in the library yet.

                                            @endif

                                        </p>


                                        @if(request('search'))

                                            <a href="{{ route(
                                                'admin.library.books.index'
                                            ) }}"
                                               class="btn btn-light me-2">

                                                Clear Search

                                            </a>

                                        @endif


                                        @can('library.create')

                                            <a href="{{ route(
                                                'admin.library.books.create'
                                            ) }}"
                                               class="btn btn-primary">

                                                <i class="ri-add-line align-middle me-1"></i>

                                                Add Book

                                            </a>

                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}

        @if($books->hasPages())

            <div class="card-footer bg-transparent">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="text-muted">

                        Showing

                        <strong>
                            {{ $books->firstItem() ?? 0 }}
                        </strong>

                        to

                        <strong>
                            {{ $books->lastItem() ?? 0 }}
                        </strong>

                        of

                        <strong>
                            {{ $books->total() }}
                        </strong>

                        books

                    </div>


                    <div>

                        {{ $books->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection