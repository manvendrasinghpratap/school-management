@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Permission</h1>
    <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
        @csrf @method('PUT')
        @include('admin.permissions.form')
    </form>
</div>
@endsection
