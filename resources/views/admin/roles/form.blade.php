@php
    $isEdit = isset($role);
    $selectedPermissions = old(
        'permissions',
        $isEdit
            ? $role->permissions->pluck('id')->map(fn ($id) => (string) $id)->all()
            : []
    );

    $selectedPermissions = collect($selectedPermissions)
        ->map(fn ($id) => (string) $id)
        ->all();

    $permissionGroups = $permissions->groupBy(function ($permission) {
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

{{-- Role Name --}}
<div class="mb-4">

    <label for="role_name" class="form-label">
        Role Name <span class="text-danger">*</span>
    </label>

    <input
        type="text"
        id="role_name"
        name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $role->name ?? '') }}"
        placeholder="e.g. Administrator"
        required
    >

    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

    <div class="form-text">
        Enter a unique role name for the system.
    </div>

</div>


{{-- Permissions Header --}}
<div class="d-flex align-items-center justify-content-between mb-3">

    <div>
        <h5 class="mb-1">
            Role Permissions
        </h5>

        <p class="text-muted mb-0">
            Select the permissions users assigned to this role should receive.
        </p>
    </div>

    <div class="d-flex gap-2">

        <button
            type="button"
            class="btn btn-sm btn-outline-primary"
            id="selectAllPermissions"
        >
            <i class="ri-checkbox-multiple-line me-1"></i>
            Select All
        </button>

        <button
            type="button"
            class="btn btn-sm btn-outline-secondary"
            id="clearAllPermissions"
        >
            <i class="ri-checkbox-blank-line me-1"></i>
            Clear All
        </button>

    </div>

</div>


{{-- Permission Summary --}}
<div class="alert alert-info d-flex align-items-center mb-4">

    <i class="ri-information-line fs-5 me-2"></i>

    <div>
        Selected permissions:
        <strong id="selectedPermissionCount">0</strong>
    </div>

</div>


{{-- Permission Groups --}}
@if($permissionGroups->count())

    <div class="accordion" id="rolePermissionAccordion">

        @foreach($permissionGroups as $group => $groupPermissions)

            @php
                $groupId = 'permissionGroup' . $loop->index;
            @endphp

            <div class="accordion-item mb-2 border rounded">

                <h2 class="accordion-header">

                    <button
                        class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#{{ $groupId }}"
                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                    >

                        <div class="d-flex align-items-center w-100">

                            <i class="ri-shield-check-line text-primary me-2"></i>

                            <strong>
                                {{ $group }}
                            </strong>

                            <span
                                class="badge bg-primary-subtle text-primary ms-2 group-count"
                                data-group="{{ $loop->index }}"
                            >
                                {{ $groupPermissions->count() }}
                            </span>

                            <span
                                class="badge bg-success-subtle text-success ms-2 group-selected-count"
                                data-group-selected="{{ $loop->index }}"
                            >
                                0 selected
                            </span>

                        </div>

                    </button>

                </h2>

                <div
                    id="{{ $groupId }}"
                    class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                >

                    <div class="accordion-body">

                        {{-- Group Controls --}}
                        <div class="d-flex justify-content-end gap-2 mb-3">

                            <button
                                type="button"
                                class="btn btn-sm btn-light select-group"
                                data-group="{{ $loop->index }}"
                            >
                                Select Group
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-light clear-group"
                                data-group="{{ $loop->index }}"
                            >
                                Clear Group
                            </button>

                        </div>


                        <div class="row">

                            @foreach($groupPermissions as $permission)

                                <div class="col-lg-6 col-xl-4 mb-2">

                                    <div class="border rounded p-3 h-100">

                                        <div class="form-check">

                                            <input
                                                class="form-check-input permission-checkbox"
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permission->id }}"
                                                id="permission_{{ $permission->id }}"
                                                data-group="{{ $loop->parent->index }}"
                                                @checked(in_array((string) $permission->id, $selectedPermissions, true))
                                            >

                                            <label
                                                class="form-check-label"
                                                for="permission_{{ $permission->id }}"
                                            >

                                                <strong class="d-block">
                                                    {{ $permission->name }}
                                                </strong>

                                                @php
                                                    $permissionParts = explode('.', $permission->name);
                                                @endphp

                                                @if(count($permissionParts) > 1)

                                                    <small class="text-muted">
                                                        {{ ucfirst(str_replace(['-', '_'], ' ', $permissionParts[1])) }}
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


{{-- Form Buttons --}}
<div class="border-top pt-4 mt-4">

    <div class="d-flex justify-content-between">

        <a
            href="{{ route('admin.roles.index') }}"
            class="btn btn-light"
        >
            <i class="ri-arrow-left-line me-1"></i>
            Cancel
        </a>

        <button
            type="submit"
            class="btn btn-primary"
        >
            <i class="ri-save-line me-1"></i>

            {{ $isEdit ? 'Update Role' : 'Create Role' }}

        </button>

    </div>

</div>


{{-- Permission Selection JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = document.querySelectorAll('.permission-checkbox');
    const selectedCount = document.getElementById('selectedPermissionCount');

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

        document.querySelectorAll('.group-selected-count').forEach(function (badge) {

            const group = badge.dataset.groupSelected;

            const selectedInGroup = document.querySelectorAll(
                '.permission-checkbox[data-group="' + group + '"]:checked'
            ).length;

            badge.textContent = selectedInGroup + ' selected';
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


    document.querySelectorAll('.select-group').forEach(function (button) {

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


    document.querySelectorAll('.clear-group').forEach(function (button) {

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


    updateCounts();

});
</script>