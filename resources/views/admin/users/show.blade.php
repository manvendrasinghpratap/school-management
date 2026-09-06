@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">User Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">Users</a>
                        </li>
                        <li class="breadcrumb-item active">View User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle-outline me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        {{-- Profile Card --}}
        <div class="col-xl-4 col-lg-5">

            <div class="card">
                <div class="card-body text-center">

                    <div class="mb-4">

                        @if($user->avatar && $user->avatar !== 'default.png')
                            <img
                                src="{{ asset('storage/' . $user->avatar) }}"
                                alt="{{ $user->name }}"
                                class="rounded-circle avatar-xl"
                                style="width: 110px; height: 110px; object-fit: cover;"
                            >
                        @else
                            <div class="avatar-xl mx-auto">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-32">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif

                    </div>

                    <h5 class="font-size-18 mb-1">
                        {{ $user->name }}
                    </h5>

                    <p class="text-muted mb-3">
                        {{ '@' . $user->username }}
                    </p>

                    {{-- Status --}}
                    @if($user->is_deleted)
                        <span class="badge bg-danger-subtle text-danger font-size-12">
                            Deleted
                        </span>
                    @elseif($user->is_active)
                        <span class="badge bg-success-subtle text-success font-size-12">
                            Active
                        </span>
                    @else
                        <span class="badge bg-warning-subtle text-warning font-size-12">
                            Inactive
                        </span>
                    @endif

                    {{-- Staff --}}
                    @if($user->is_staff)
                        <span class="badge bg-info-subtle text-info font-size-12 ms-1">
                            Staff
                        </span>
                    @endif

                    <hr class="my-4">

                    <div class="text-start">

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Email
                            </small>
                            <span>
                                <i class="mdi mdi-email-outline me-1"></i>
                                {{ $user->email }}
                            </span>
                        </div>

                        @if($user->timezone)
                            <div class="mb-3">
                                <small class="text-muted d-block">
                                    Timezone
                                </small>
                                <span>
                                    <i class="mdi mdi-clock-outline me-1"></i>
                                    {{ $user->timezone }}
                                </span>
                            </div>
                        @endif

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                User ID
                            </small>
                            <span>
                                #{{ $user->id }}
                            </span>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        {{-- Details --}}
        <div class="col-xl-8 col-lg-7">

            {{-- User Information --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        User Information
                    </h5>

                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="btn btn-primary btn-sm">
                        <i class="mdi mdi-pencil-outline me-1"></i>
                        Edit User
                    </a>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Full Name
                            </label>
                            <strong>
                                {{ $user->name }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Username
                            </label>
                            <strong>
                                {{ $user->username }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Email Address
                            </label>
                            <strong>
                                {{ $user->email }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Account Status
                            </label>

                            @if($user->is_active)
                                <span class="badge bg-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Staff Account
                            </label>

                            @if($user->is_staff)
                                <span class="badge bg-info">
                                    Yes
                                </span>
                            @else
                                <span class="badge bg-light text-dark">
                                    No
                                </span>
                            @endif
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted d-block mb-1">
                                Timezone
                            </label>
                            <strong>
                                {{ $user->timezone ?: 'Not specified' }}
                            </strong>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Roles & Permissions --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Roles & Permissions
                    </h5>
                </div>

                <div class="card-body">

                    <h6 class="text-muted mb-3">
                        Assigned Roles
                    </h6>

                    @forelse($user->roles as $role)
                        <span class="badge bg-primary font-size-12 me-1 mb-2">
                            {{ $role->name }}
                        </span>
                    @empty
                        <p class="text-muted mb-0">
                            No roles assigned.
                        </p>
                    @endforelse

                </div>
            </div>

            {{-- Staff Information --}}
            @if($user->staff)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Staff Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Staff Number
                                </small>
                                <strong>
                                    {{ $user->staff->staff_number }}
                                </strong>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Staff Name
                                </small>
                                <strong>
                                    {{ trim(
                                        $user->staff->first_name . ' ' .
                                        ($user->staff->middle_name ?? '') . ' ' .
                                        $user->staff->last_name
                                    ) }}
                                </strong>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Staff Type
                                </small>
                                <strong>
                                    {{ $user->staff->staff_type }}
                                </strong>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">
                                    Employment Date
                                </small>
                                <strong>
                                    {{ $user->staff->employment_date
                                        ? $user->staff->employment_date->format('d M Y')
                                        : 'Not specified' }}
                                </strong>
                            </div>

                        </div>

                    </div>
                </div>
            @endif

            {{-- Account Information --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Account Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">
                                Created
                            </small>
                            <strong>
                                {{ $user->created_at
                                    ? $user->created_at->format('d M Y, h:i A')
                                    : 'N/A' }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">
                                Last Updated
                            </small>
                            <strong>
                                {{ $user->updated_at
                                    ? $user->updated_at->format('d M Y, h:i A')
                                    : 'N/A' }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">
                                Account ID
                            </small>
                            <strong>
                                {{ $user->id }}
                            </strong>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">
                                Status Code
                            </small>
                            <strong>
                                {{ $user->status }}
                            </strong>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-body">

                    <div class="d-flex flex-wrap gap-2">

                        <a href="{{ route('admin.users.index') }}"
                           class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i>
                            Back to Users
                        </a>

                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="btn btn-primary">
                            <i class="mdi mdi-pencil-outline me-1"></i>
                            Edit
                        </a>

                        @if(!$user->is_deleted)

                            @if($user->is_active && auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.deactivate', $user) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit"
                                            class="btn btn-warning"
                                            onclick="return confirm('Deactivate this user?')">
                                        <i class="mdi mdi-account-off-outline me-1"></i>
                                        Deactivate
                                    </button>
                                </form>
                            @elseif(!$user->is_active)
                                <form action="{{ route('admin.users.activate', $user) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit"
                                            class="btn btn-success">
                                        <i class="mdi mdi-account-check-outline me-1"></i>
                                        Activate
                                    </button>
                                </form>
                            @endif

                            @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger"
                                            onclick="return confirm('Delete this user? This will deactivate the account.')">
                                        <i class="mdi mdi-delete-outline me-1"></i>
                                        Delete
                                    </button>
                                </form>
                            @endif

                        @else

                            <form action="{{ route('admin.users.restore', $user) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('PUT')

                                <button type="submit"
                                        class="btn btn-success">
                                    <i class="mdi mdi-backup-restore me-1"></i>
                                    Restore
                                </button>
                            </form>

                        @endif

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection