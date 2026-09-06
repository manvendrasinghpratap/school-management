@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Roles</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-1"></i>
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-1"></i>
            {{ session('error') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please check the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="card">

        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-1">System Roles</h5>
                <p class="text-muted mb-0">
                    Manage roles and their assigned permissions.
                </p>
            </div>

            <div>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="ri-add-line align-bottom me-1"></i>
                    Create Role
                </a>
            </div>
        </div>

        <div class="card-body">

            @if($roles->count())

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Role Name</th>
                                <th>Guard</th>
                                <th class="text-center">Permissions</th>
                                <th class="text-center">Users</th>
                                <th class="text-center" style="width: 220px;">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($roles as $role)

                                <tr>

                                    {{-- Number --}}
                                    <td>
                                        {{ $roles->firstItem() + $loop->index }}
                                    </td>

                                    {{-- Role --}}
                                    <td>
                                        <div class="d-flex align-items-center">

                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                    <i class="ri-shield-user-line"></i>
                                                </span>
                                            </div>

                                            <div>
                                                <h6 class="mb-0">
                                                    {{ $role->name }}
                                                </h6>

                                                @if($role->name === 'Super Admin')
                                                    <span class="badge bg-danger-subtle text-danger mt-1">
                                                        Protected
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    </td>

                                    {{-- Guard --}}
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $role->guard_name }}
                                        </span>
                                    </td>

                                    {{-- Permissions --}}
                                    <td class="text-center">

                                        <span class="badge bg-info-subtle text-info fs-12">
                                            {{ $role->permissions->count() }}
                                        </span>

                                    </td>

                                    {{-- Users --}}
                                    <td class="text-center">

                                        <span class="badge bg-success-subtle text-success fs-12">
                                            {{ $role->users_count }}
                                        </span>

                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-1">

                                            {{-- View --}}
                                            <a href="{{ route('admin.roles.show', $role) }}"
                                               class="btn btn-sm btn-info"
                                               title="View Role">
                                                <i class="ri-eye-line"></i>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.roles.edit', $role) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit Role">
                                                <i class="ri-edit-line"></i>
                                            </a>

                                            {{-- Delete --}}
                                            @if($role->name !== 'Super Admin')

                                                <form action="{{ route('admin.roles.destroy', $role) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete the role {{ addslashes($role->name) }}?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete Role">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>

                                                </form>

                                            @else

                                                <button type="button"
                                                        class="btn btn-sm btn-secondary"
                                                        disabled
                                                        title="Super Admin cannot be deleted">
                                                    <i class="ri-lock-line"></i>
                                                </button>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                @if($roles->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">

                        <div class="text-muted">
                            Showing
                            <strong>{{ $roles->firstItem() }}</strong>
                            to
                            <strong>{{ $roles->lastItem() }}</strong>
                            of
                            <strong>{{ $roles->total() }}</strong>
                            roles
                        </div>

                        <div>
                            {{ $roles->links() }}
                        </div>

                    </div>
                @endif

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="avatar-lg mx-auto mb-4">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-1">
                            <i class="ri-shield-user-line"></i>
                        </span>
                    </div>

                    <h5>No Roles Found</h5>

                    <p class="text-muted mb-4">
                        There are currently no roles configured in the system.
                    </p>

                    <a href="{{ route('admin.roles.create') }}"
                       class="btn btn-primary">
                        <i class="ri-add-line align-bottom me-1"></i>
                        Create First Role
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection