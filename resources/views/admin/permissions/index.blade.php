@extends('backend.layout.default')

@section('title', 'Permissions')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0 font-size-18">
                    Permissions
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Permissions
                        </li>

                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <div class="d-flex align-items-center">
                <i class="bx bx-check-circle font-size-20 me-2"></i>

                <div>
                    {{ session('success') }}
                </div>
            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <div class="d-flex align-items-center">
                <i class="bx bx-error-circle font-size-20 me-2"></i>

                <div>
                    {{ session('error') }}
                </div>
            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>
    @endif

    {{-- Permissions Card --}}
    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    {{-- Card Header --}}
                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <div class="d-flex align-items-center">

                            <div class="avatar-sm me-3">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">
                                    <i class="bx bx-key"></i>
                                </span>
                            </div>

                            <div>
                                <h4 class="card-title mb-1">
                                    System Permissions
                                </h4>

                                <p class="text-muted mb-0">
                                    Manage permissions available to roles in the system.
                                </p>
                            </div>

                        </div>

                        @can('permissions.create')
                            <div>
                                <a
                                    href="{{ route('admin.permissions.create') }}"
                                    class="btn btn-primary"
                                >
                                    <i class="bx bx-plus me-1"></i>
                                    Create Permission
                                </a>
                            </div>
                        @endcan

                    </div>

                    {{-- Permissions Table --}}
                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th style="width: 45%;">
                                        Permission Name
                                    </th>

                                    <th style="width: 20%;">
                                        Roles
                                    </th>

                                    <th style="width: 35%;">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($permissions as $permission)

                                    <tr>

                                        <td>
                                            <span class="fw-medium">
                                                {{ $permission->name }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge bg-info-subtle text-info">
                                                {{ $permission->roles_count }}
                                            </span>
                                        </td>

                                        <td>

                                            @can('permissions.update')
                                                <a
                                                    href="{{ route('admin.permissions.edit', $permission) }}"
                                                    class="btn btn-sm btn-soft-secondary me-1"
                                                >
                                                    <i class="bx bx-edit-alt me-1"></i>
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('permissions.delete')
                                                <form
                                                    action="{{ route('admin.permissions.destroy', $permission) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-soft-danger"
                                                        onclick="return confirm('Delete this permission?')"
                                                    >
                                                        <i class="bx bx-trash me-1"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan

                                            @cannot('permissions.update')
                                                @cannot('permissions.delete')
                                                    <span class="text-muted">
                                                        No actions available
                                                    </span>
                                                @endcannot
                                            @endcannot

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="3"
                                            class="text-center py-5"
                                        >
                                            <div class="text-muted">

                                                <i class="bx bx-key font-size-48 d-block mb-3"></i>

                                                <h5 class="mb-2">
                                                    No Permissions Found
                                                </h5>

                                                <p class="mb-0">
                                                    There are currently no permissions available.
                                                </p>

                                            </div>
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    @if($permissions->hasPages())
                        <div class="mt-4">
                            {{ $permissions->links() }}
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection