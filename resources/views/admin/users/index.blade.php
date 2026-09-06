@extends('backend.layout.default')

@section('title', 'Users')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1">Users</h4>
                    <p class="text-muted mb-0">
                        Manage system users and account access.
                    </p>
                </div>

                <a href="{{ route('admin.users.create') }}"
                   class="btn btn-primary">
                    <i class="bx bx-user-plus me-1"></i>
                    Add User
                </a>

            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Please check the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Statistics --}}
    <div class="row">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-primary-subtle text-primary font-size-20">
                                <i class="bx bx-group"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Total Users
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['total']) }}
                            </h4>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Active --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-success-subtle text-success font-size-20">
                                <i class="bx bx-user-check"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Active Users
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['active']) }}
                            </h4>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Inactive --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-warning-subtle text-warning font-size-20">
                                <i class="bx bx-user-x"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Inactive Users
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['inactive']) }}
                            </h4>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Staff --}}
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm">
                            <span class="avatar-title rounded bg-info-subtle text-info font-size-20">
                                <i class="bx bx-id-card"></i>
                            </span>
                        </div>

                        <div class="ms-3">
                            <p class="text-muted mb-1">
                                Staff Accounts
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($statistics['staff']) }}
                            </h4>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- Filters --}}
    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bx bx-filter-alt me-2"></i>
                Search & Filter
            </h5>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.users.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-lg-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Name, email or username">

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

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


                    {{-- Staff --}}
                    <div class="col-lg-2">

                        <label class="form-label">
                            Staff
                        </label>

                        <select name="staff"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="yes"
                                {{ request('staff') === 'yes' ? 'selected' : '' }}>
                                Staff
                            </option>

                            <option value="no"
                                {{ request('staff') === 'no' ? 'selected' : '' }}>
                                Non-Staff
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-2 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="bx bx-search me-1"></i>
                            Search

                        </button>

                        <a href="{{ route('admin.users.index') }}"
                           class="btn btn-light"
                           title="Reset">

                            <i class="bx bx-reset"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>


    {{-- Users Table --}}
    <div class="card">

        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">
                    User Accounts
                </h5>

                <span class="text-muted">
                    {{ $users->total() }} user(s)
                </span>

            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle table-hover">

                    <thead class="table-light">

                        <tr>
                            <th>User</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Staff</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($users as $user)

                        <tr>

                            {{-- User --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="avatar-sm">

                                        @if($user->avatar && $user->avatar !== 'default.png')

                                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                                 alt="{{ $user->name }}"
                                                 class="rounded-circle avatar-sm"
                                                 style="object-fit: cover;">

                                        @else

                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>

                                        @endif

                                    </div>

                                    <div class="ms-3">

                                        <h6 class="mb-1">
                                            {{ $user->name }}
                                        </h6>

                                        <small class="text-muted">
                                            ID: {{ $user->id }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Username --}}
                            <td>
                                <span class="fw-semibold">
                                    {{ $user->username }}
                                </span>
                            </td>


                            {{-- Email --}}
                            <td>
                                {{ $user->email }}
                            </td>


                            {{-- Role --}}
                            <td>

                                @forelse($user->roles as $role)

                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $role->name }}
                                    </span>

                                @empty

                                    <span class="text-muted">
                                        No Role
                                    </span>

                                @endforelse

                            </td>


                            {{-- Staff --}}
                            <td>

                                @if($user->is_staff)

                                    <span class="badge bg-info-subtle text-info">
                                        Staff
                                    </span>

                                @else

                                    <span class="badge bg-light text-muted">
                                        No
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($user->is_active)

                                    <span class="badge bg-success-subtle text-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger-subtle text-danger">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex justify-content-end gap-1">

                                    {{-- View --}}
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="btn btn-sm btn-info"
                                       title="View User">

                                        <i class="bx bx-show-alt"></i>

                                    </a>


                                    {{-- Edit --}}
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Edit User">

                                        <i class="bx bx-edit-alt"></i>

                                    </a>


                                    {{-- Activate --}}
                                    @if(!$user->is_active)

                                        <form method="POST"
                                              action="{{ route('admin.users.activate', $user) }}"
                                              class="d-inline">

                                            @csrf
                                            @method('PUT')

                                            <button type="submit"
                                                    class="btn btn-sm btn-success"
                                                    title="Activate User">

                                                <i class="bx bx-check"></i>

                                            </button>

                                        </form>

                                    @endif


                                    {{-- Deactivate --}}
                                    @if($user->is_active && $user->id !== auth()->id())

                                        <form method="POST"
                                              action="{{ route('admin.users.deactivate', $user) }}"
                                              class="d-inline">

                                            @csrf
                                            @method('PUT')

                                            <button type="submit"
                                                    class="btn btn-sm btn-warning"
                                                    title="Deactivate User"
                                                    onclick="return confirm('Deactivate {{ $user->name }}?')">

                                                <i class="bx bx-block"></i>

                                            </button>

                                        </form>

                                    @endif


                                    {{-- Delete --}}
                                    @if($user->id !== auth()->id())

                                        <form method="POST"
                                              action="{{ route('admin.users.destroy', $user) }}"
                                              class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete User"
                                                    onclick="return confirm('Are you sure you want to delete {{ $user->name }}?')">

                                                <i class="bx bx-trash"></i>

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                <i class="bx bx-user-x font-size-32 text-muted"></i>

                                <h5 class="mt-2">
                                    No users found
                                </h5>

                                <p class="text-muted mb-0">
                                    There are no user accounts matching your search.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="mt-3">

                {{ $users->links() }}

            </div>

        </div>

    </div>

</div>
@endsection