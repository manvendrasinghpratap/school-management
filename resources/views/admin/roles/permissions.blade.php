@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-sm-0">Manage Role Permissions</h4>
                </div>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.index') }}">
                                Roles
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.show', $role) }}">
                                {{ $role->name }}
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


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-check-line me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Role Summary --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex align-items-center justify-content-between">

                <div>

                    <h5 class="card-title mb-1">
                        {{ $role->name }}
                    </h5>

                    <p class="text-muted mb-0">
                        Configure the permissions inherited by users assigned to this role.
                    </p>

                </div>

                <div>

                    @if($role->name === 'Super Admin')

                        <span class="badge bg-danger-subtle text-danger">
                            <i class="ri-lock-line me-1"></i>
                            Protected Role
                        </span>

                    @else

                        <span class="badge bg-primary-subtle text-primary">
                            <i class="ri-shield-user-line me-1"></i>
                            Role Permissions
                        </span>

                    @endif

                </div>

            </div>

        </div>


        <div class="card-body">

            {{-- Controls --}}
            <div class="row mb-4">

                <div class="col-md-6">

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="ri-search-line"></i>
                        </span>

                        <input
                            type="text"
                            id="permissionSearch"
                            class="form-control"
                            placeholder="Search permissions..."
                        >

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="d-flex justify-content-md-end gap-2 mt-2 mt-md-0">

                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            id="selectAllPermissions"
                        >
                            <i class="ri-checkbox-multiple-line me-1"></i>
                            Select All
                        </button>

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            id="clearAllPermissions"
                        >
                            <i class="ri-checkbox-blank-line me-1"></i>
                            Clear All
                        </button>

                    </div>

                </div>

            </div>


            {{-- Selection Summary --}}
            <div class="alert alert-info">

                <div class="d-flex align-items-center">

                    <i class="ri-information-line fs-5 me-2"></i>

                    <div>

                        <strong id="selectedPermissionCount">
                            {{ count($assignedPermissionIds) }}
                        </strong>

                        permissions selected.

                    </div>

                </div>

            </div>


            {{-- Permission Form --}}
            <form
                action="{{ route('admin.roles.permissions.update', $role) }}"
                method="POST"
                id="rolePermissionsForm"
            >

                @csrf
                @method('PUT')


                @if($permissions->count())

                    <div
                        class="accordion"
                        id="rolePermissionAccordion"
                    >

                        @foreach($permissions as $group => $groupPermissions)

                            @php
                                $groupIndex = $loop->index;
                                $collapseId = 'rolePermissionGroup' . $groupIndex;
                            @endphp

                            <div
                                class="accordion-item mb-3 border rounded permission-group"
                                data-group="{{ $groupIndex }}"
                            >

                                {{-- Group Header --}}
                                <h2 class="accordion-header">

                                    <button
                                        class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#{{ $collapseId }}"
                                    >

                                        <div class="d-flex align-items-center w-100">

                                            <i class="ri-shield-check-line text-primary me-2"></i>

                                            <strong>
                                                {{ $group }}
                                            </strong>

                                            <span class="badge bg-primary-subtle text-primary ms-2">
                                                {{ $groupPermissions->count() }}
                                            </span>

                                            <span
                                                class="badge bg-success-subtle text-success ms-2 group-selected"
                                                data-group-selected="{{ $groupIndex }}"
                                            >
                                                0 selected
                                            </span>

                                        </div>

                                    </button>

                                </h2>


                                {{-- Group Body --}}
                                <div
                                    id="{{ $collapseId }}"
                                    class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                >

                                    <div class="accordion-body">

                                        {{-- Group Controls --}}
                                        <div class="d-flex justify-content-end gap-2 mb-3">

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-light select-group"
                                                data-group="{{ $groupIndex }}"
                                            >
                                                Select Group
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-light clear-group"
                                                data-group="{{ $groupIndex }}"
                                            >
                                                Clear Group
                                            </button>

                                        </div>


                                        <div class="row">

                                            @foreach($groupPermissions as $permission)

                                                <div
                                                    class="col-lg-6 col-xl-4 mb-3 permission-item"
                                                    data-permission-name="{{ strtolower($permission->name) }}"
                                                >

                                                    <div class="border rounded p-3 h-100">

                                                        <div class="form-check">

                                                            <input
                                                                class="form-check-input permission-checkbox"
                                                                type="checkbox"
                                                                name="permissions[]"
                                                                value="{{ $permission->id }}"
                                                                id="role_permission_{{ $permission->id }}"
                                                                data-group="{{ $groupIndex }}"
                                                                @checked(in_array(
                                                                    (string) $permission->id,
                                                                    $assignedPermissionIds,
                                                                    true
                                                                ))
                                                            >

                                                            <label
                                                                class="form-check-label"
                                                                for="role_permission_{{ $permission->id }}"
                                                            >

                                                                <strong class="d-block">
                                                                    {{ $permission->name }}
                                                                </strong>

                                                                @php
                                                                    $parts = explode('.', $permission->name);
                                                                @endphp

                                                                @if(count($parts) > 1)

                                                                    <small class="text-muted">
                                                                        {{ ucfirst(
                                                                            str_replace(
                                                                                ['-', '_'],
                                                                                ' ',
                                                                                $parts[1]
                                                                            )
                                                                        ) }}
                                                                    </small>

                                                                @endif

                                                            </label>

                                                        </div>

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

                        <h5>No Permissions Available</h5>

                        <p class="text-muted mb-0">
                            There are currently no permissions configured.
                        </p>

                    </div>

                @endif


                {{-- Buttons --}}
                <div class="border-top pt-4 mt-4">

                    <div class="d-flex justify-content-between">

                        <div class="d-flex gap-2">

                            <a
                                href="{{ route('admin.roles.show', $role) }}"
                                class="btn btn-light"
                            >
                                <i class="ri-arrow-left-line me-1"></i>
                                Back to Role
                            </a>

                            <a
                                href="{{ route('admin.roles.index') }}"
                                class="btn btn-light"
                            >
                                Roles
                            </a>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="ri-save-line me-1"></i>
                            Save Permissions
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = document.querySelectorAll('.permission-checkbox');
    const selectedCount = document.getElementById('selectedPermissionCount');
    const searchInput = document.getElementById('permissionSearch');


    function updateCounts() {

        let totalSelected = 0;

        checkboxes.forEach(function (checkbox) {

            if (checkbox.checked) {
                totalSelected++;
            }

        });

        if (selectedCount) {
            selectedCount.textContent = totalSelected;
        }


        document.querySelectorAll('.group-selected').forEach(function (badge) {

            const group = badge.dataset.groupSelected;

            const selected = document.querySelectorAll(
                '.permission-checkbox[data-group="' + group + '"]:checked'
            ).length;

            badge.textContent = selected + ' selected';

        });

    }


    document.getElementById('selectAllPermissions')
        ?.addEventListener('click', function () {

            checkboxes.forEach(function (checkbox) {

                checkbox.checked = true;

            });

            updateCounts();

        });


    document.getElementById('clearAllPermissions')
        ?.addEventListener('click', function () {

            checkboxes.forEach(function (checkbox) {

                checkbox.checked = false;

            });

            updateCounts();

        });


    document.querySelectorAll('.select-group')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const group = this.dataset.group;

                document.querySelectorAll(
                    '.permission-checkbox[data-group="' + group + '"]'
                ).forEach(function (checkbox) {

                    checkbox.checked = true;

                });

                updateCounts();

            });

        });


    document.querySelectorAll('.clear-group')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const group = this.dataset.group;

                document.querySelectorAll(
                    '.permission-checkbox[data-group="' + group + '"]'
                ).forEach(function (checkbox) {

                    checkbox.checked = false;

                });

                updateCounts();

            });

        });


    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener('change', updateCounts);

    });


    /*
    |--------------------------------------------------------------------------
    | Permission Search
    |--------------------------------------------------------------------------
    */

    searchInput?.addEventListener('input', function () {

        const search = this.value.toLowerCase().trim();

        document.querySelectorAll('.permission-item')
            .forEach(function (item) {

                const permissionName =
                    item.dataset.permissionName || '';

                if (
                    search === '' ||
                    permissionName.includes(search)
                ) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }

            });


        document.querySelectorAll('.permission-group')
            .forEach(function (group) {

                const visibleItems = group.querySelectorAll(
                    '.permission-item:not([style*="display: none"])'
                );

                group.style.display =
                    visibleItems.length ? '' : 'none';

            });

    });


    updateCounts();

});
</script>

@endsection