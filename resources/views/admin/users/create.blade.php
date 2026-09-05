@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create User</h1>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users.form')
    </form>
</div>
@endsection
