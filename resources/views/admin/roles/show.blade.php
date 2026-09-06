@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">Role Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.index') }}">Roles</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $role->name }}
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Main Row --}}
    <div class="row">

        {{-- Role Information --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Role Information
                    </h5>
                </div>

                <div class="card-body text-center">

                    <div class="avatar-lg mx-auto mb-3">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-1">
                            <i class="ri-shield-user-line"></i>
                        </span>
                    </div>

                    <h4 class="mb-2">
                        {{ $role->name }}
                    </h4>

                    @if($role->name === 'Super Admin')
                        <span class="badge bg-danger-subtle text-danger mb-3">
                            <i class="ri-lock-line me-1"></i>
                            Protected Role
                        </span>
                    @else
                        <span class="badge bg-success-subtle text-success mb-3">
                            Custom Role
                        </span>
                    @endif

                    <div class="table-responsive mt-3">

                        <table class="table table-borderless text-start mb-0">

                            <tr>
                                <th>Role ID</th>
                                <td>{{ $role->id }}</td>
                            </tr>

                            <tr>
                                <th>Guard</th>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $role->guard_name }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Permissions</th>
                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        {{ $role->permissions->count() }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Users</th>
                                <td>
                                    <span class="badge bg-success-subtle text-success">
                                        {{ $role->users->count() }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Created</th>
                                <td>
                                    {{ $role->created_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                            </tr>

                            <tr>
                                <th>Updated</th>
                                <td>
                                    {{ $role->updated_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                            </tr>

                        </table>

                    </div>

                </div>

                <div class="card-footer">

                    <div class="d-flex gap-2">

                        <a href="{{ route('admin.roles.edit', $role) }}"
                           class="btn btn-warning flex-fill">
                            <i class="ri-edit-line me-1"></i>
                            Edit Role
                        </a>
                        <a href="{{ route('admin.roles.permissions.edit', $role) }}"
   class="btn btn-primary flex-fill">
    <i class="ri-shield-check-line me-1"></i>
    Manage Permissions
</a>

                        <a href="{{ route('admin.roles.index') }}"
                           class="btn btn-light flex-fill">
                            <i class="ri-arrow-left-line me-1"></i>
                            Back
                        </a>

                    </div>

                </div>

            </div>

        </div>

        {{-- Permissions --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">

                    <div>
                        <h5 class="card-title mb-1">
                            Assigned Permissions
                        </h5>

                        <p class="text-muted mb-0">
                            Permissions currently assigned to this role.
                        </p>
                    </div>

                    <span class="badge bg-primary">
                        {{ $role->permissions->count() }}
                    </span>

                </div>

                <div class="card-body">

                    @if($role->permissions->count())

                        @php
                            $permissionGroups = $role->permissions
                                ->groupBy(function ($permission) {
                                    $parts = explode('.', $permission->name);

                                    if (count($parts) >= 2) {
                                        return ucfirst(
                                            str_replace(
                                                ['-', '_'],
                                                ' ',
                                                $parts[0]
                                            )
                                        );
                                    }

                                    return 'Other';
                                });
                        @endphp

                        <div class="accordion" id="permissionAccordion">

                            @foreach($permissionGroups as $group => $groupPermissions)

                                <div class="accordion-item">

                                    <h2 class="accordion-header"
                                        id="heading{{ $loop->index }}">

                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $loop->index }}"
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $loop->index }}">

                                            <i class="ri-shield-check-line me-2 text-primary"></i>

                                            <strong>{{ $group }}</strong>

                                            <span class="badge bg-primary-subtle text-primary ms-2">
                                                {{ $groupPermissions->count() }}
                                            </span>

                                        </button>

                                    </h2>

                                    <div id="collapse{{ $loop->index }}"
                                         class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                         aria-labelledby="heading{{ $loop->index }}"
                                         data-bs-parent="#permissionAccordion">

                                        <div class="accordion-body">

                                            <div class="row">

                                                @foreach($groupPermissions as $permission)

                                                    <div class="col-md-6 mb-2">

                                                        <div class="border rounded p-2">

                                                            <i class="ri-checkbox-circle-line text-success me-1"></i>

                                                            <span>
                                                                {{ $permission->name }}
                                                            </span>

                                                        </div>

                                                    </div>

                                                @endforeach

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-5">

                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-warning-subtle text-warning fs-1">
                                    <i class="ri-shield-line"></i>
                                </span>
                            </div>

                            <h5>No Permissions Assigned</h5>

                            <p class="text-muted mb-0">
                                This role currently has no permissions assigned.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- Assigned Users --}}
    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-1">
                        Users Assigned to This Role
                    </h5>

                    <p class="text-muted mb-0">
                        Users currently assigned to
                        <strong>{{ $role->name }}</strong>.
                    </p>

                </div>

                <div class="card-body">

                    @if($role->users->count())

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($role->users as $user)

                                        <tr>

                                            <td>
                                                {{ $loop->iteration }}
                                            </td>

                                            <td>
                                                <strong>
                                                    {{ $user->name }}
                                                </strong>
                                            </td>

                                            <td>
                                                {{ $user->email }}
                                            </td>

                                            <td>
                                                {{ $user->username }}
                                            </td>

                                            <td>

                                                @if($user->is_active && !$user->is_deleted)

                                                    <span class="badge bg-success-subtle text-success">
                                                        Active
                                                    </span>

                                                @elseif($user->is_deleted)

                                                    <span class="badge bg-danger-subtle text-danger">
                                                        Deleted
                                                    </span>

                                                @else

                                                    <span class="badge bg-warning-subtle text-warning">
                                                        Inactive
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-4">

                            <i class="ri-user-unfollow-line fs-1 text-muted"></i>

                            <h6 class="mt-2">
                                No Users Assigned
                            </h6>

                            <p class="text-muted mb-0">
                                No users currently have this role.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection