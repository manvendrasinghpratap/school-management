@extends('backend.layout.default')

@section('title', 'User Permissions')

@section('content')

<div class="container-fluid">

    {{-- ========================================================
         PAGE HEADER
         ======================================================== --}}

    <div class="row mb-3">

        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1">
                        User Permissions
                    </h4>

                    <p class="text-muted mb-0">
                        Manage direct permissions for
                        <strong>{{ $user->name }}</strong>.
                    </p>

                </div>

                <div class="d-flex gap-2">

                    <a
                        href="{{ route('admin.users.show', $user) }}"
                        class="btn btn-secondary"
                    >
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to User
                    </a>

                    <a
                        href="{{ route('admin.users.roles.edit', $user) }}"
                        class="btn btn-primary"
                    >
                        <i class="bx bx-shield me-1"></i>
                        Manage Roles
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================
         SUCCESS MESSAGE
         ======================================================== --}}

    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <i class="bx bx-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ========================================================
         ERRORS
         ======================================================== --}}

    @if($errors->any())

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    <div class="row">

        {{-- ====================================================
             USER SUMMARY
             ==================================================== --}}

        <div class="col-xl-4 col-lg-4">

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        User Account
                    </h5>

                </div>

                <div class="card-body text-center">

                    @if($user->avatar && $user->avatar !== 'default.png')

                        <img
                            src="{{ asset('storage/' . $user->avatar) }}"
                            alt="{{ $user->name }}"
                            class="rounded-circle mb-3"
                            style="
                                width: 90px;
                                height: 90px;
                                object-fit: cover;
                            "
                        >

                    @else

                        <div
                            class="avatar-lg mx-auto mb-3"
                        >

                            <span
                                class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-28"
                            >
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>

                        </div>

                    @endif


                    <h5 class="mb-1">
                        {{ $user->name }}
                    </h5>

                    <p class="text-muted mb-3">
                        {{ '@' . $user->username }}
                    </p>


                    {{-- Status --}}

                    @if($user->is_active)

                        <span class="badge bg-success">
                            Active
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Inactive
                        </span>

                    @endif


                    <hr class="my-4">


                    <div class="text-start">

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Email
                            </small>

                            <strong>
                                {{ $user->email }}
                            </strong>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Current Roles
                            </small>

                            @forelse($user->roles as $role)

                                <span
                                    class="badge bg-primary me-1 mb-1"
                                >
                                    {{ $role->name }}
                                </span>

                            @empty

                                <span class="text-muted">
                                    No roles assigned.
                                </span>

                            @endforelse

                        </div>


                        <div>

                            <small class="text-muted d-block">
                                Direct Permissions
                            </small>

                            <strong>
                                {{ count($directPermissions) }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Information --}}

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">
                        Permission Information
                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted mb-3">
                        Permissions selected here are assigned
                        <strong>directly to this user</strong>.
                    </p>

                    <p class="text-muted mb-0">
                        Permissions inherited from the user's roles
                        remain unchanged.
                    </p>

                </div>

            </div>

        </div>


        {{-- ====================================================
             PERMISSIONS
             ==================================================== --}}

        <div class="col-xl-8 col-lg-8">

            <form
                method="POST"
                action="{{ route('admin.users.permissions.update', $user) }}"
            >

                @csrf

                @method('PUT')


                @forelse($permissions as $group => $groupPermissions)

                    <div class="card">

                        <div class="card-header">

                            <div class="d-flex justify-content-between align-items-center">

                                <h5 class="card-title mb-0">

                                    <i class="bx bx-lock-alt me-1"></i>

                                    {{ $group }}

                                </h5>


                                <div>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light select-group"
                                        data-group="{{ \Illuminate\Support\Str::slug($group) }}"
                                    >
                                        Select All
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light unselect-group"
                                        data-group="{{ \Illuminate\Support\Str::slug($group) }}"
                                    >
                                        Clear
                                    </button>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row">

                                @foreach($groupPermissions as $permission)

                                    @php
                                        $permissionKey = \Illuminate\Support\Str::slug(
                                            $group
                                        );
                                    @endphp

                                    <div
                                        class="col-md-6 col-xl-4 mb-3"
                                    >

                                        <div class="form-check">

                                            <input
                                                type="checkbox"
                                                class="form-check-input permission-checkbox permission-group-{{ $permissionKey }}"
                                                name="permissions[]"
                                                value="{{ $permission->id }}"
                                                id="permission_{{ $permission->id }}"
                                                @checked(
                                                    in_array(
                                                        $permission->name,
                                                        $directPermissions,
                                                        true
                                                    )
                                                )
                                            >

                                            <label
                                                class="form-check-label"
                                                for="permission_{{ $permission->id }}"
                                            >

                                                {{ $permission->name }}

                                            </label>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="card">

                        <div class="card-body">

                            <div class="alert alert-warning mb-0">

                                <i class="bx bx-info-circle me-1"></i>

                                No permissions are available.

                            </div>

                        </div>

                    </div>

                @endforelse


                {{-- =================================================
                     SAVE
                     ================================================= --}}

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <a
                                href="{{ route('admin.users.show', $user) }}"
                                class="btn btn-secondary"
                            >
                                <i class="bx bx-x me-1"></i>
                                Cancel
                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bx bx-save me-1"></i>

                                Save Permissions

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ============================================================
     SELECT / CLEAR JAVASCRIPT
     ============================================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.select-group')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const group = this.dataset.group;

                document
                    .querySelectorAll(
                        '.permission-group-' + group
                    )
                    .forEach(function (checkbox) {

                        checkbox.checked = true;

                    });

            });

        });


    document.querySelectorAll('.unselect-group')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const group = this.dataset.group;

                document
                    .querySelectorAll(
                        '.permission-group-' + group
                    )
                    .forEach(function (checkbox) {

                        checkbox.checked = false;

                    });

            });

        });

});

</script>

@endsection