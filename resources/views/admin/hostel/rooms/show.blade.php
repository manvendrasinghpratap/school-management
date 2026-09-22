@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h4>Room {{ $room->room_number }} — {{ $room->hostel->name }}</h4>
        <div>
            <a href="{{ route('admin.hostel.beds.index', $room) }}" class="btn btn-outline-primary">Manage Beds</a>
            @can('hostel.rooms.manage')
                <a href="{{ route('admin.hostel.rooms.edit', $room) }}" class="btn btn-primary">Edit</a>
            @endcan
            <a href="{{ route('admin.hostel.rooms.index', $room->hostel) }}" class="btn btn-light">Back</a>
        </div>
    </div>
    @include('admin.hostel._flash')
    <div class="card"><div class="card-body">
        <div class="row">
            <div class="col-md-3"><strong>Floor</strong><div>{{ $room->floor ?: '—' }}</div></div>
            <div class="col-md-3"><strong>Type</strong><div>{{ $room->room_type ?: '—' }}</div></div>
            <div class="col-md-3"><strong>Capacity</strong><div>{{ $room->capacity }}</div></div>
            <div class="col-md-3"><strong>Monthly Fee</strong><div>₹{{ number_format((float)$room->monthly_fee,2) }}</div></div>
        </div>
    </div></div>
</div>
@endsection
