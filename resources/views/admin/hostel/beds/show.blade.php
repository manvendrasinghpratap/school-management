@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h4>Bed {{ $bed->bed_number }}</h4>
        <div>
            @can('hostel.rooms.manage')
                <a href="{{ route('admin.hostel.beds.edit', $bed) }}" class="btn btn-primary">Edit</a>
            @endcan
            <a href="{{ route('admin.hostel.beds.index', $bed->room) }}" class="btn btn-light">Back</a>
        </div>
    </div>
    @include('admin.hostel._flash')
    <div class="card"><div class="card-body">
        <p><strong>Hostel:</strong> {{ $bed->room->hostel->name }}</p>
        <p><strong>Room:</strong> {{ $bed->room->room_number }}</p>
        <p><strong>Bed:</strong> {{ $bed->bed_number }}</p>
        <p><strong>Status:</strong> {{ ucfirst($bed->status) }}</p>
    </div></div>
</div>
@endsection
