@extends('backend.layout.default')

@section('title', $title)

@section('content')
<div class="container-fluid">

    {{-- ============================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ============================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">{{ $title }}</h4>

            <p class="text-muted mb-0">
                Manage and search library categories.
            </p>
        </div>

        @can('library.create')
            <a href="{{ route('admin.library.categories.create') }}"
               class="btn btn-primary">

                <i class="bx bx-plus me-1"></i>
                Add Category

            </a>
        @endcan

    </div>


    {{-- ============================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ============================================================= --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="bx bx-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================= --}}
    {{-- ERROR MESSAGE --}}
    {{-- ============================================================= --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="bx bx-error-circle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ============================================================= --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================= --}}
    {{-- FILTER CARD --}}
    {{-- ============================================================= --}}
    <div class="card mb-3">

        <div class="card-header">

            <div class="d-flex align-items-center">

                <i class="bx bx-filter-alt me-2"></i>

                <h5 class="card-title mb-0">
                    Filter Categories
                </h5>

            </div>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.library.categories.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label for="search"
                               class="form-label">

                            Search

                        </label>

                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Search category name, code or description">

                    </div>


                    {{-- Code --}}
                    <div class="col-md-3">

                        <label for="code"
                               class="form-label">

                            Category Code

                        </label>

                        <input type="text"
                               id="code"
                               name="code"
                               value="{{ request('code') }}"
                               class="form-control"
                               placeholder="Enter category code">

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-4">

                        <label class="form-label d-block">
                            &nbsp;
                        </label>

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bx bx-search me-1"></i>
                                Search

                            </button>


                            <a href="{{ route('admin.library.categories.index') }}"
                               class="btn btn-light">

                                <i class="bx bx-reset me-1"></i>
                                Reset

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- CATEGORY LIST --}}
    {{-- ============================================================= --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">
                    Category List
                </h5>

                <span class="text-muted small">

                    {{ $items->total() }}

                    {{ $items->total() === 1 ? 'category' : 'categories' }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 70px;">
                                #
                            </th>

                            <th>
                                Category Name
                            </th>

                            <th style="width: 160px;">
                                Code
                            </th>

                            <th>
                                Description
                            </th>

                            <th class="text-end"
                                style="width: 180px;">

                                Actions

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($items as $category)

                        <tr>

                            {{-- ================================================= --}}
                            {{-- NUMBER --}}
                            {{-- ================================================= --}}
                            <td>

                                {{ $items->firstItem() + $loop->index }}

                            </td>


                            {{-- ================================================= --}}
                            {{-- CATEGORY NAME --}}
                            {{-- ================================================= --}}
                            <td>

                                <div class="fw-semibold">

                                    {{ $category->name }}

                                </div>

                            </td>


                            {{-- ================================================= --}}
                            {{-- CATEGORY CODE --}}
                            {{-- ================================================= --}}
                            <td>

                                @if($category->code)

                                    <span class="badge bg-light text-dark">

                                        {{ $category->code }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- DESCRIPTION --}}
                            {{-- ================================================= --}}
                            <td>

                                @if($category->description)

                                    {{ \Illuminate\Support\Str::limit(
                                        $category->description,
                                        100
                                    ) }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- ACTIONS --}}
                            {{-- ================================================= --}}
                            <td class="text-end">

                                {{-- View --}}
                                @can('library.view')

                                    <a href="{{ route(
                                            'admin.library.categories.show',
                                            $category
                                        ) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="View Category">

                                        <i class="bx bx-show"></i>

                                    </a>

                                @endcan


                                {{-- Edit --}}
                                @can('library.update')

                                    <a href="{{ route(
                                            'admin.library.categories.edit',
                                            $category
                                        ) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit Category">

                                        <i class="bx bx-edit-alt"></i>

                                    </a>

                                @endcan


                                {{-- Delete --}}
                                @can('library.delete')

                                    <form method="POST"
                                          action="{{ route(
                                              'admin.library.categories.destroy',
                                              $category
                                          ) }}"
                                          class="d-inline"
                                          onsubmit="return confirm(
                                              'Are you sure you want to delete this category?'
                                          );">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete Category">

                                            <i class="bx bx-trash"></i>

                                        </button>

                                    </form>

                                @endcan

                            </td>

                        </tr>

                    @empty

                        {{-- ================================================= --}}
                        {{-- EMPTY STATE --}}
                        {{-- ================================================= --}}
                        <tr>

                            <td colspan="5"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="bx bx-folder-open"
                                       style="font-size: 42px;">
                                    </i>


                                    <div class="mt-2">

                                        @if(request()->filled('search') ||
                                            request()->filled('code'))

                                            No categories match your filters.

                                        @else

                                            No library categories found.

                                        @endif

                                    </div>


                                    {{-- Clear Filters --}}
                                    @if(request()->filled('search') ||
                                        request()->filled('code'))

                                        <a href="{{ route(
                                            'admin.library.categories.index'
                                        ) }}"
                                           class="btn btn-light btn-sm mt-3">

                                            <i class="bx bx-reset me-1"></i>

                                            Clear Filters

                                        </a>

                                    @else

                                        @can('library.create')

                                            <a href="{{ route(
                                                'admin.library.categories.create'
                                            ) }}"
                                               class="btn btn-primary btn-sm mt-3">

                                                <i class="bx bx-plus me-1"></i>

                                                Add First Category

                                            </a>

                                        @endcan

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ============================================================= --}}
        {{-- PAGINATION --}}
        {{-- ============================================================= --}}
        @if($items->hasPages())

            <div class="card-footer">

                {{ $items->links() }}

            </div>

        @endif

    </div>

</div>
@endsection