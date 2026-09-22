@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <h4>Create Bed — Room {{ $room->room_number }}</h4>
    @include('admin.hostel._flash')
    <form method="POST" action="{{ route('admin.hostel.beds.store') }}">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->id }}">
        @include('admin.hostel.beds.form')
        <button class="btn btn-primary">Create Bed</button>
        <a href="{{ route('admin.hostel.beds.index', $room) }}" class="btn btn-light">Cancel</a>
    </form>
</div>
@endsection
