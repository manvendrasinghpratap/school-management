@extends('backend.layout.default')

@section('title', $title)

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">{{ $title }}</h4>
        </div>

        {{-- Category Add Button --}}
        @if(str_contains($routePrefix, 'categories'))
            @can('library.create')
                <a href="{{ route('admin.library.categories.create') }}"
                   class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i>
                    Add Category
                </a>
            @endcan

        {{-- Author Add Button --}}
        @elseif(str_contains($routePrefix, 'authors'))
            @can('library.create')
                <button type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addAuthorModal">
                    <i class="bx bx-plus me-1"></i>
                    Add Author
                </button>
            @endcan

        {{-- Publisher Add Button --}}
        @else
            @can('library.create')
                <button type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addPublisherModal">
                    <i class="bx bx-plus me-1"></i>
                    Add Publisher
                </button>
            @endcan
        @endif

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Validation Errors --}}
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


    {{-- Listing --}}
    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Name</th>

                            @if(str_contains($routePrefix, 'categories'))
                                <th>Code</th>
                                <th>Description</th>

                            @elseif(str_contains($routePrefix, 'authors'))
                                <th>Biography</th>

                            @else
                                <th>Phone</th>
                                <th>Email</th>
                            @endif

                            <th class="text-end" style="width: 180px;">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($items as $x)

                        <tr>

                            <td>
                                {{ $items->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $x->name }}
                                </div>
                            </td>


                            {{-- Category columns --}}
                            @if(str_contains($routePrefix, 'categories'))

                                <td>
                                    @if($x->code)
                                        <span class="badge bg-light text-dark">
                                            {{ $x->code }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($x->description)
                                        {{ \Illuminate\Support\Str::limit($x->description, 80) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>


                            {{-- Author columns --}}
                            @elseif(str_contains($routePrefix, 'authors'))

                                <td>
                                    @if($x->biography)
                                        {{ \Illuminate\Support\Str::limit($x->biography, 100) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>


                            {{-- Publisher columns --}}
                            @else

                                <td>
                                    {{ $x->phone ?: '—' }}
                                </td>

                                <td>
                                    {{ $x->email ?: '—' }}
                                </td>

                            @endif


                            {{-- Actions --}}
                            <td class="text-end">

                                @if(str_contains($routePrefix, 'categories'))

                                    @can('library.update')
                                        <a href="{{ route('admin.library.categories.edit', $x) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                    @endcan

                                    @can('library.delete')
                                        <form method="POST"
                                              action="{{ route('admin.library.categories.destroy', $x) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this category?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </button>

                                        </form>
                                    @endcan


                                @else

                                    <span class="text-muted small">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-5">

                                <div class="text-muted">

                                    <i class="bx bx-folder-open"
                                       style="font-size: 40px;"></i>

                                    <div class="mt-2">
                                        No records found.
                                    </div>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($items->hasPages())

            <div class="card-footer">
                {{ $items->links() }}
            </div>

        @endif

    </div>

</div>


{{-- ================================================================== --}}
{{-- ADD AUTHOR MODAL --}}
{{-- ================================================================== --}}

@if(str_contains($routePrefix, 'authors'))

    @can('library.create')

        <div class="modal fade"
             id="addAuthorModal"
             tabindex="-1"
             aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form method="POST"
                          action="{{ route('admin.library.authors.store') }}">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Add Library Author
                            </h5>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"></button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">
                                    Author Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       placeholder="Enter author name"
                                       maxlength="255"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Biography
                                </label>

                                <textarea name="biography"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Enter author biography"></textarea>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-light"
                                    data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Save Author
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endcan

@endif


{{-- ================================================================== --}}
{{-- ADD PUBLISHER MODAL --}}
{{-- ================================================================== --}}

@if(str_contains($routePrefix, 'publishers'))

    @can('library.create')

        <div class="modal fade"
             id="addPublisherModal"
             tabindex="-1"
             aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <form method="POST"
                          action="{{ route('admin.library.publishers.store') }}">

                        @csrf

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Add Library Publisher
                            </h5>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"></button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">
                                    Publisher Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       placeholder="Enter publisher name"
                                       maxlength="255"
                                       required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Phone
                                </label>

                                <input type="text"
                                       name="phone"
                                       class="form-control"
                                       placeholder="Enter phone number"
                                       maxlength="50">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       placeholder="Enter email address"
                                       maxlength="255">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Address
                                </label>

                                <textarea name="address"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Enter publisher address"></textarea>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Website
                                </label>

                                <input type="text"
                                       name="website"
                                       class="form-control"
                                       placeholder="https://example.com"
                                       maxlength="255">

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-light"
                                    data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Save Publisher
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endcan

@endif

@endsection