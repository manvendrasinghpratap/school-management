@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Role: {{ $role->name }}</h1>
    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf @method('PUT')
        @include('admin.roles.form')
    </form>
</div>
@endsection
