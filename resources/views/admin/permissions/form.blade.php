<div class="mb-3">
    <label class="form-label">Permission Name</label>
    <input type="text" name="name" value="{{ old('name', $permission->name ?? '') }}" class="form-control" required>
    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<button class="btn btn-primary">Save</button>
<a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
