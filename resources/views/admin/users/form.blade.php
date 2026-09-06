{{-- ============================================================
     USER FORM
     Used by Create User / Edit User
     ============================================================ --}}

@php
    $isEdit = isset($user);

    /*
    |--------------------------------------------------------------------------
    | User Types
    |--------------------------------------------------------------------------
    */
    $userTypes = DB::table('user_types')
        ->where('status', 1)
        ->orderBy('name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Designations
    |--------------------------------------------------------------------------
    */
    $designations = DB::table('designations')
        ->where('status', 1)
        ->where('is_deleted', 0)
        ->orderBy('name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Selected Roles
    |--------------------------------------------------------------------------
    | Spatie uses role names here.
    |--------------------------------------------------------------------------
    */
    $selectedRoles = old(
        'roles',
        $isEdit
            ? $user->roles->pluck('name')->all()
            : []
    );
@endphp


{{-- ============================================================
     NAME
     ============================================================ --}}

<div class="mb-3">

    <label for="name" class="form-label">
        Name
        <span class="text-danger">*</span>
    </label>

    <input
        type="text"
        name="name"
        id="name"
        value="{{ old('name', $user->name ?? '') }}"
        class="form-control @error('name') is-invalid @enderror"
        placeholder="Enter full name"
        maxlength="255"
        required
    >

    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


{{-- ============================================================
     USERNAME
     ============================================================ --}}

<div class="mb-3">

    <label for="username" class="form-label">
        Username
        <span class="text-danger">*</span>
    </label>

    <input
        type="text"
        name="username"
        id="username"
        value="{{ old('username', $user->username ?? '') }}"
        class="form-control @error('username') is-invalid @enderror"
        placeholder="e.g. john.doe"
        maxlength="100"
        required
    >

    @error('username')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


{{-- ============================================================
     EMAIL
     ============================================================ --}}

<div class="mb-3">

    <label for="email" class="form-label">
        Email
        <span class="text-danger">*</span>
    </label>

    <input
        type="email"
        name="email"
        id="email"
        value="{{ old('email', $user->email ?? '') }}"
        class="form-control @error('email') is-invalid @enderror"
        placeholder="user@example.com"
        maxlength="255"
        required
    >

    @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


{{-- ============================================================
     PASSWORD
     ============================================================ --}}

<div class="row">

    <div class="col-md-6 mb-3">

        <label for="password" class="form-label">

            Password

            @if(!$isEdit)
                <span class="text-danger">*</span>
            @endif

        </label>

        <input
            type="password"
            name="password"
            id="password"
            class="form-control @error('password') is-invalid @enderror"
            minlength="8"
            autocomplete="new-password"
            {{ !$isEdit ? 'required' : '' }}
        >

        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">

            @if($isEdit)
                Leave blank to keep the current password.
            @else
                Minimum 8 characters.
            @endif

        </div>

    </div>


    {{-- ========================================================
         PASSWORD CONFIRMATION
         ======================================================== --}}

    <div class="col-md-6 mb-3">

        <label for="password_confirmation" class="form-label">

            Confirm Password

            @if(!$isEdit)
                <span class="text-danger">*</span>
            @endif

        </label>

        <input
            type="password"
            name="password_confirmation"
            id="password_confirmation"
            class="form-control"
            minlength="8"
            autocomplete="new-password"
            {{ !$isEdit ? 'required' : '' }}
        >

    </div>

</div>


{{-- ============================================================
     USER TYPE / DESIGNATION
     ============================================================ --}}

<div class="row">

    {{-- User Type --}}
    <div class="col-md-6 mb-3">

        <label for="user_type_id" class="form-label">
            User Type
        </label>

        <select
            name="user_type_id"
            id="user_type_id"
            class="form-select @error('user_type_id') is-invalid @enderror"
        >

            <option value="">
                Select User Type
            </option>

            @foreach($userTypes as $userType)

                <option
                    value="{{ $userType->id }}"
                    @selected(
                        old(
                            'user_type_id',
                            $user->user_type_id ?? ''
                        ) == $userType->id
                    )
                >
                    {{ $userType->name }}
                </option>

            @endforeach

        </select>

        @error('user_type_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Designation --}}
    <div class="col-md-6 mb-3">

        <label for="designation_id" class="form-label">
            Designation
        </label>

        <select
            name="designation_id"
            id="designation_id"
            class="form-select @error('designation_id') is-invalid @enderror"
        >

            <option value="">
                Select Designation
            </option>

            @foreach($designations as $designation)

                <option
                    value="{{ $designation->id }}"
                    @selected(
                        old(
                            'designation_id',
                            $user->designation_id ?? ''
                        ) == $designation->id
                    )
                >
                    {{ $designation->name }}
                </option>

            @endforeach

        </select>

        @error('designation_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>


{{-- ============================================================
     TIMEZONE
     ============================================================ --}}

<div class="mb-3">

    <label for="timezone" class="form-label">
        Timezone
    </label>

    <input
        type="text"
        name="timezone"
        id="timezone"
        value="{{ old(
            'timezone',
            $user->timezone
                ?? auth()->user()->timezone
                ?? 'Africa/Lagos'
        ) }}"
        class="form-control @error('timezone') is-invalid @enderror"
        placeholder="Africa/Lagos"
        maxlength="100"
    >

    @error('timezone')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


{{-- ============================================================
     ROLES
     ============================================================ --}}

<div class="mb-4">

    <label class="form-label">
        Roles
    </label>

    @if(isset($roles) && $roles->count() > 0)

        <div class="row">

            @foreach($roles as $role)

                <div class="col-md-4 col-lg-3 mb-2">

                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="roles[]"
                            value="{{ $role->name }}"
                            id="role_{{ $role->id }}"
                            class="form-check-input"
                            @checked(
                                in_array(
                                    $role->name,
                                    $selectedRoles,
                                    true
                                )
                            )
                        >

                        <label
                            for="role_{{ $role->id }}"
                            class="form-check-label"
                        >
                            {{ $role->name }}
                        </label>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="alert alert-warning">
            <i class="mdi mdi-alert-outline me-1"></i>
            No roles are available.
        </div>

    @endif

    @error('roles')
        <div class="text-danger mt-2">
            {{ $message }}
        </div>
    @enderror

</div>


{{-- ============================================================
     ACTIVE ACCOUNT
     ============================================================ --}}

@if(\Schema::hasColumn('users', 'is_active'))

    <div class="mb-3">

        <div class="form-check form-switch">

            <input
                type="hidden"
                name="is_active"
                value="0"
            >

            <input
                type="checkbox"
                name="is_active"
                id="is_active"
                value="1"
                class="form-check-input"
                @checked(
                    old(
                        'is_active',
                        $isEdit
                            ? $user->is_active
                            : true
                    )
                )
            >

            <label
                for="is_active"
                class="form-check-label"
            >
                <strong>Active Account</strong>
            </label>

        </div>

        <div class="form-text">
            Active users can access the system.
        </div>

    </div>

@endif


{{-- ============================================================
     STAFF ACCOUNT
     ============================================================ --}}

@if(\Schema::hasColumn('users', 'is_staff'))

    <div class="mb-3">

        <div class="form-check form-switch">

            <input
                type="hidden"
                name="is_staff"
                value="0"
            >

            <input
                type="checkbox"
                name="is_staff"
                id="is_staff"
                value="1"
                class="form-check-input"
                @checked(
                    old(
                        'is_staff',
                        $isEdit
                            ? $user->is_staff
                            : false
                    )
                )
            >

            <label
                for="is_staff"
                class="form-check-label"
            >
                <strong>Staff Account</strong>
            </label>

        </div>

        <div class="form-text">
            Mark this account as belonging to staff.
        </div>

    </div>

@endif


{{-- ============================================================
     BUTTONS
     ============================================================ --}}

<div class="d-flex justify-content-between mt-4">

    <a
        href="{{ $isEdit
            ? route('admin.users.show', $user)
            : route('admin.users.index')
        }}"
        class="btn btn-secondary"
    >
        <i class="mdi mdi-arrow-left me-1"></i>
        Cancel
    </a>


    <button
        type="submit"
        class="btn btn-primary"
    >

        <i class="mdi mdi-content-save-outline me-1"></i>

        {{ $isEdit ? 'Update User' : 'Create User' }}

    </button>

</div>