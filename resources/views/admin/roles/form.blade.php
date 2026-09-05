@php($selected = old('permissions', isset($role) ? $role->permissions->pluck('name')->all() : []))
<div class="mb-3">
    <label class="form-label">Role Name</label>
    <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}" class="form-control" required>
    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Permissions</label>
    <div class="row">
        @foreach($permissions as $permission)
            <div class="col-md-4 mb-2">
                <label>
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                        @checked(in_array($permission->name, $selected, true))>
                    {{ $permission->name }}
                </label>
            </div>
        @endforeach
    </div>
</div>

<button class="btn btn-primary">Save</button>
<a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
