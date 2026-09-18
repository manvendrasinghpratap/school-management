@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">{{ $title ?? 'Category Details' }}</h4>

                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.library.categories.index') }}">
                                Library Categories
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            {{ $category->name }}
                        </li>
                    </ol>
                </div>

                <div class="page-title-right">
                    <a href="{{ route('admin.library.categories.index') }}"
                       class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Categories
                    </a>

                    @can('library.update')
                        <a href="{{ route('admin.library.categories.edit', $category) }}"
                           class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i>
                            Edit Category
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>


    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif


    {{-- Category Information --}}
    <div class="row">

        <div class="col-xl-4 col-lg-5">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Category Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="mb-4 text-center">
                        <div class="avatar-lg mx-auto">
                            <div class="avatar-title rounded-circle bg-primary-subtle text-primary fs-2">
                                <i class="bx bx-category"></i>
                            </div>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">

                            <tr>
                                <th style="width: 40%;">
                                    Name
                                </th>

                                <td>
                                    <strong>{{ $category->name }}</strong>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Code
                                </th>

                                <td>
                                    @if($category->code)
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $category->code }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            —
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Description
                                </th>

                                <td>
                                    @if($category->description)
                                        {{ $category->description }}
                                    @else
                                        <span class="text-muted">
                                            No description provided.
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Books
                                </th>

                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        {{ $books->total() }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Created
                                </th>

                                <td>
                                    {{ optional($category->created_at)->format('d M Y, h:i A') ?? '—' }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Updated
                                </th>

                                <td>
                                    {{ optional($category->updated_at)->format('d M Y, h:i A') ?? '—' }}
                                </td>
                            </tr>

                        </table>
                    </div>

                </div>
            </div>

        </div>


        {{-- Books --}}
        <div class="col-xl-8 col-lg-7">

            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">

                    <div>
                        <h5 class="card-title mb-1">
                            Books in this Category
                        </h5>

                        <p class="text-muted mb-0">
                            Books assigned to
                            <strong>{{ $category->name }}</strong>
                        </p>
                    </div>

                    <span class="badge bg-primary-subtle text-primary">
                        {{ $books->total() }} {{ Str::plural('Book', $books->total()) }}
                    </span>

                </div>


                <div class="card-body">

                    @if($books->count())

                        <div class="table-responsive">

                            <table class="table table-hover align-middle">

                                <thead class="table-light">

                                    <tr>
                                        <th>#</th>
                                        <th>Book</th>
                                        <th>ISBN</th>
                                        <th>Author</th>
                                        <th>Quantity</th>
                                        <th>Available</th>
                                        <th>Shelf</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($books as $book)

                                        <tr>

                                            <td>
                                                {{ $books->firstItem() + $loop->index }}
                                            </td>

                                            <td>
                                                <strong>
                                                    {{ $book->title }}
                                                </strong>
                                            </td>

                                            <td>
                                                @if($book->isbn)
                                                    {{ $book->isbn }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($book->author)
                                                    {{ $book->author }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    {{ $book->quantity ?? 0 }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge bg-success-subtle text-success">
                                                    {{ $book->available_quantity ?? 0 }}
                                                </span>
                                            </td>

                                            <td>
                                                @if($book->shelf_location)
                                                    {{ $book->shelf_location }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                        {{-- Pagination --}}
                        @if($books->hasPages())
                            <div class="mt-3">
                                {{ $books->links() }}
                            </div>
                        @endif

                    @else

                        <div class="text-center py-5">

                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title rounded-circle bg-light text-muted fs-2">
                                    <i class="bx bx-book"></i>
                                </div>
                            </div>

                            <h5 class="mb-2">
                                No Books Found
                            </h5>

                            <p class="text-muted mb-0">
                                There are currently no books assigned to this category.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection