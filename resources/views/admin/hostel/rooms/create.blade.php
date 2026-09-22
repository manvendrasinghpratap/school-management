@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <h4>Create Room — {{ $hostel->name }}</h4>
    @include('admin.hostel._flash')
    <form method="POST" action="{{ route('admin.hostel.rooms.store') }}">
        @csrf
        <input type="hidden" name="hostel_id" value="{{ $hostel->id }}">
        @include('admin.hostel.rooms.form')
        <button class="btn btn-primary">Create Room</button>
        <a href="{{ route('admin.hostel.rooms.index', $hostel) }}" class="btn btn-light">Cancel</a>
    </form>
</div>
@endsection
