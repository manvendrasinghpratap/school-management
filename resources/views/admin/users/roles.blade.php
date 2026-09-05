@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Assign Roles: {{ $user->name }}</h1>

    <form method="POST" action="{{ route('admin.users.roles.update', $user) }}">
        @csrf @method('PUT')
        @foreach($roles as $role)
            <div class="mb-2">
                <label>
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        @checked($user->hasRole($role->name))>
                    {{ $role->name }}
                </label>
            </div>
        @endforeach

        <button class="btn btn-primary">Save Roles</button>
    </form>
</div>
@endsection
