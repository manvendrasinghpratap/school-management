@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Direct Permissions: {{ $user->name }}</h1>

    <div class="alert alert-warning">
        Prefer assigning permissions to roles. Direct permissions are intended for exceptional cases.
    </div>

    <form method="POST" action="{{ route('admin.users.permissions.update', $user) }}">
        @csrf @method('PUT')
        @foreach($permissions as $permission)
            <div class="mb-2">
                <label>
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                        @checked($user->hasDirectPermission($permission->name))>
                    {{ $permission->name }}
                </label>
            </div>
        @endforeach

        <button class="btn btn-primary">Save Permissions</button>
    </form>
</div>
@endsection
