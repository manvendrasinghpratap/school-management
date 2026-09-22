@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <h4>Create Hostel</h4>
    @include('admin.hostel._flash')

    <form method="POST" action="{{ route('admin.hostel.hostels.store') }}">
        @csrf
        @include('admin.hostel.hostels.form')
        <button class="btn btn-primary">Create Hostel</button>
        <a href="{{ route('admin.hostel.hostels.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>
@endsection
