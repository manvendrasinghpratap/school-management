@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <h4>Edit Bed — Room {{ $room->room_number }}</h4>
    @include('admin.hostel._flash')
    <form method="POST" action="{{ route('admin.hostel.beds.update', $bed) }}">
        @csrf @method('PUT')
        <input type="hidden" name="room_id" value="{{ $room->id }}">
        @include('admin.hostel.beds.form', ['bed' => $bed])
        <button class="btn btn-primary">Update Bed</button>
        <a href="{{ route('admin.hostel.beds.show', $bed) }}" class="btn btn-light">Cancel</a>
    </form>
</div>
@endsection
