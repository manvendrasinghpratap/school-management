@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit User: {{ $user->name }}</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mb-4">
        @csrf @method('PUT')
        @include('admin.users.form')
    </form>

    @can('users.update')
    <hr>
    <h3>Change Password</h3>
    <form method="POST" action="{{ route('admin.users.password.update', $user) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-warning">Change Password</button>
    </form>
    @endcan
</div>
@endsection
