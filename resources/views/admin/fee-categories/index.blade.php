@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Fee Categories</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">Finance</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Fee Categories
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endif

    {{-- Error Alert --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
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

    {{-- Fee Categories Card --}}
    <div class="card">

        <div class="card-header d-flex align-items-center justify-content-between">

            <div>
                <h5 class="card-title mb-1">
                    Fee Category Master
                </h5>

                <p class="text-muted mb-0">
                    Manage the types of fees used throughout the school.
                </p>
            </div>

            @can('fees.manage')
                <a href="{{ route('admin.fee-categories.create') }}"
                   class="btn btn-primary">

                    <i class="ri-add-line align-middle me-1"></i>
                    Add Fee Category

                </a>
            @endcan

        </div>

        <div class="card-body">

            {{-- Filters --}}
            <form method="GET"
                  action="{{ route('admin.fee-categories.index') }}"
                  class="mb-4">

                <div class="row g-2">

                    <div class="col-md-5">
                        <label for="search" class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               id="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Search by name, code or description">
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select name="status"
                                id="status"
                                class="form-select" style="top: 70%; left: 10%; width: 91%;">

                            <option value="">All Statuses</option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ri-search-line align-middle me-1"></i>
                            Search

                        </button>

                        <a href="{{ route('admin.fee-categories.index') }}"
                           class="btn btn-light">

                            <i class="ri-refresh-line align-middle me-1"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

            @if($categories->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th style="width: 60px;">
                                    #
                                </th>

                                <th>
                                    Name
                                </th>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Fee Structures
                                </th>

                                <th>
                                    Status
                                </th>

                                <th style="width: 190px;">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($categories as $category)

                                <tr>

                                    <td>
                                        {{ $categories->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $category->name }}
                                        </strong>
                                    </td>

                                    <td>

                                        @if($category->code)
                                            <span class="badge bg-secondary">
                                                {{ $category->code }}
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                —
                                            </span>
                                        @endif

                                    </td>

                                    <td>
                                        {{ $category->description ?: '—' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-info-subtle text-info">
                                            {{ $category->fee_structures_count }}
                                        </span>
                                    </td>

                                    <td>

                                        @if($category->is_active)

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <div class="d-flex gap-1">

                                            @can('fees.view')

                                                <a href="{{ route('admin.fee-categories.show', $category) }}"
                                                   class="btn btn-sm btn-soft-info"
                                                   title="View">

                                                    <i class="ri-eye-line"></i>

                                                </a>

                                            @endcan

                                            @can('fees.manage')

                                                <a href="{{ route('admin.fee-categories.edit', $category) }}"
                                                   class="btn btn-sm btn-soft-primary"
                                                   title="Edit">

                                                    <i class="ri-edit-line"></i>

                                                </a>

                                                <form action="{{ route('admin.fee-categories.toggle-status', $category) }}"
                                                      method="POST"
                                                      class="d-inline">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-soft-warning"
                                                            title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">

                                                        <i class="{{ $category->is_active ? 'ri-toggle-line' : 'ri-toggle-fill' }}"></i>

                                                    </button>

                                                </form>

                                                @if($category->fee_structures_count == 0)

                                                    <form action="{{ route('admin.fee-categories.destroy', $category) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this fee category?');">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn btn-sm btn-soft-danger"
                                                                title="Delete">

                                                            <i class="ri-delete-bin-line"></i>

                                                        </button>

                                                    </form>

                                                @endif

                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $categories->links() }}
                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="ri-money-dollar-circle-line display-5 text-muted"></i>
                    </div>

                    <h5>
                        No fee categories found
                    </h5>

                    <p class="text-muted mb-3">
                        No fee categories have been configured for this school yet.
                    </p>

                    @can('fees.manage')

                        <a href="{{ route('admin.fee-categories.create') }}"
                           class="btn btn-primary">

                            <i class="ri-add-line align-middle me-1"></i>
                            Create First Fee Category

                        </a>

                    @endcan

                </div>

            @endif

        </div>

    </div>

</div>

@endsection