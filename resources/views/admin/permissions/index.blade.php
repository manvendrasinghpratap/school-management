@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Permissions</h1>
        @can('permissions.create')
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">Create Permission</a>
        @endcan
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <table class="table table-bordered">
        <thead><tr><th>Name</th><th>Roles</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($permissions as $permission)
            <tr>
                <td>{{ $permission->name }}</td>
                <td>{{ $permission->roles_count }}</td>
                <td>
                    @can('permissions.update')
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-secondary">Edit</a>
                    @endcan
                    @can('permissions.delete')
                        <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this permission?')">Delete</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $permissions->links() }}
</div>
@endsection
