@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Role</h1>
    <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf
        @include('admin.roles.form')
    </form>
</div>
@endsection
