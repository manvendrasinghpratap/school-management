@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Permission</h1>
    <form method="POST" action="{{ route('admin.permissions.store') }}">
        @csrf
        @include('admin.permissions.form')
    </form>
</div>
@endsection
