<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control" required>
    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control" required>
    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label>Password {{ isset($user) ? '(leave blank to keep current password)' : '' }}</label>
    <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
</div>

<div class="mb-3">
    <label>Confirm Password</label>
    <input type="password" name="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}>
</div>

<div class="mb-3">
    <label>Roles</label>
    <div class="row">
        @php($selectedRoles = old('roles', isset($user) ? $user->roles->pluck('name')->all() : []))
        @foreach($roles as $role)
            <div class="col-md-4 mb-2">
                <label>
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        @checked(in_array($role->name, $selectedRoles, true))>
                    {{ $role->name }}
                </label>
            </div>
        @endforeach
    </div>
</div>

@if(\Schema::hasColumn('users', 'is_active'))
<div class="mb-3">
    <label>
        <input type="checkbox" name="is_active" value="1"
            @checked(old('is_active', isset($user) ? $user->is_active : true))>
        Active account
    </label>
</div>
@endif

<button class="btn btn-primary">Save</button>
<a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
