@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>User Management</h1>
        @can('users.create')
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create User</a>
        @endcan
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name or email">
        </div>
        <div class="col-md-3">
            <select name="role" class="form-control">
                <option value="">All roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-control">
                <option value="">All status</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Roles</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('name')->join(', ') ?: 'No role' }}</td>
                    <td>
                        @if(\Schema::hasColumn('users', 'is_active'))
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        @else
                            Active
                        @endif
                    </td>
                    <td>
                        @can('users.update')
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <a href="{{ route('admin.users.roles.edit', $user) }}" class="btn btn-sm btn-outline-primary">Roles</a>
                            <a href="{{ route('admin.users.permissions.edit', $user) }}" class="btn btn-sm btn-outline-info">Permissions</a>
                        @endcan

                        @can('users.delete')
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                                </form>
                            @endif
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No users found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</div>
@endsection
