@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <h4>Edit Room — {{ $hostel->name }}</h4>
    @include('admin.hostel._flash')
    <form method="POST" action="{{ route('admin.hostel.rooms.update', $room) }}">
        @csrf @method('PUT')
        <input type="hidden" name="hostel_id" value="{{ $hostel->id }}">
        @include('admin.hostel.rooms.form', ['room' => $room])
        <button class="btn btn-primary">Update Room</button>
        <a href="{{ route('admin.hostel.rooms.show', $room) }}" class="btn btn-light">Cancel</a>
    </form>
</div>
@endsection
