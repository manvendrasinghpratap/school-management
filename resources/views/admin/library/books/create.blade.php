@extends('backend.layout.default')

@section('title', 'Add Library Book')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Add Library Book</h4>
            <p class="text-muted mb-0">
                Register a new book in your school library.
            </p>
        </div>

        <a href="{{ route('admin.library.books.index') }}"
           class="btn btn-light">
            ← Back to Books
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Book Form --}}
    <form method="POST"
          action="{{ route('admin.library.books.store') }}">

        @csrf

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Book Information</h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Title --}}
                    <div class="col-md-6">
                        <label for="title" class="form-label">
                            Book Title <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="Enter book title"
                            required
                        >

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- ISBN --}}
                    <div class="col-md-3">
                        <label for="isbn" class="form-label">
                            ISBN
                        </label>

                        <input
                            type="text"
                            id="isbn"
                            name="isbn"
                            value="{{ old('isbn') }}"
                            class="form-control @error('isbn') is-invalid @enderror"
                            placeholder="ISBN"
                        >

                        @error('isbn')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Quantity --}}
                    <div class="col-md-3">
                        <label for="quantity" class="form-label">
                            Total Quantity <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="{{ old('quantity', 1) }}"
                            class="form-control @error('quantity') is-invalid @enderror"
                            min="0"
                            step="1"
                            required
                        >

                        <small class="text-muted">
                            Number of books currently registered.
                        </small>

                        @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div class="col-md-4">
                        <label for="category_id" class="form-label">
                            Category <span class="text-danger">*</span>
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            class="form-select @error('category_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Category</option>

                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected((string) old('category_id') === (string) $category->id)
                                >
                                    {{ $category->name }}
                                    @if ($category->code)
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

                    {{-- Publisher --}}
                    <div class="col-md-4">
                        <label for="publisher_id" class="form-label">
                            Publisher
                        </label>

                        <select
                            id="publisher_id"
                            name="publisher_id"
                            class="form-select @error('publisher_id') is-invalid @enderror"
                        >
                            <option value="">Select Publisher</option>

                            @foreach ($publishers as $publisher)
                                <option
                                    value="{{ $publisher->id }}"
                                    @selected((string) old('publisher_id') === (string) $publisher->id)
                                >
                                    {{ $publisher->name }}
                                </option>
                            @endforeach
                        </select>

                        <small class="text-muted">
                            The selected publisher name will be stored with the book.
                        </small>

                        @error('publisher_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Authors --}}
                    <div class="col-md-4">
                        <label for="author_ids" class="form-label">
                            Library Authors
                        </label>

                        <select
                            id="author_ids"
                            name="author_ids[]"
                            multiple
                            class="form-select @error('author_ids') is-invalid @enderror"
                            style="min-height: 120px;"
                        >
                            @php
                                $oldAuthors = old('author_ids', []);
                                $oldAuthors = is_array($oldAuthors)
                                    ? $oldAuthors
                                    : [$oldAuthors];
                            @endphp

                            @foreach ($authors as $author)
                                <option
                                    value="{{ $author->id }}"
                                    @selected(in_array($author->id, $oldAuthors))
                                >
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>

                        <small class="text-muted">
                            Hold Ctrl/Cmd to select multiple authors.
                        </small>

                        @error('author_ids')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        @error('author_ids.*')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Shelf Location --}}
                    <div class="col-md-6">
                        <label for="shelf_location" class="form-label">
                            Shelf Location
                        </label>

                        <input
                            type="text"
                            id="shelf_location"
                            name="shelf_location"
                            value="{{ old('shelf_location') }}"
                            class="form-control @error('shelf_location') is-invalid @enderror"
                            placeholder="Example: A-101"
                        >

                        @error('shelf_location')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Legacy Author Display --}}
                    <div class="col-md-6">
                        <label for="author" class="form-label">
                            Primary / Display Author
                        </label>

                        <input
                            type="text"
                            id="author"
                            name="author"
                            value="{{ old('author') }}"
                            class="form-control @error('author') is-invalid @enderror"
                            placeholder="Optional display author name"
                        >

                        <small class="text-muted">
                            Optional legacy/display author text. Library Authors above are
                            stored in the author relationship.
                        </small>

                        @error('author')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

            </div>

            <div class="card-footer d-flex gap-2">

                <button type="submit" class="btn btn-primary">
                    Save Book
                </button>

                <a href="{{ route('admin.library.books.index') }}"
                   class="btn btn-light">
                    Cancel
                </a>

            </div>
        </div>

    </form>

</div>

@endsection
