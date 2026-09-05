@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Roles</h1>
        @can('roles.create')
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Create Role</a>
        @endcan
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <table class="table table-bordered">
        <thead><tr><th>Name</th><th>Users</th><th>Permissions</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>{{ $role->users_count }}</td>
                <td>{{ $role->permissions->count() }}</td>
                <td>
                    @can('roles.update')
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-secondary">Edit</a>
                    @endcan
                    @can('roles.delete')
                        @if($role->name !== 'Super Admin')
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this role?')">Delete</button>
                            </form>
                        @endif
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $roles->links() }}
</div>
@endsection
