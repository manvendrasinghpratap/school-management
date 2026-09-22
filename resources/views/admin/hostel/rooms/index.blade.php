@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">{{ $hostel->name }} — Rooms</h4>
            <a href="{{ route('admin.hostel.hostels.show', $hostel) }}">← Back to Hostel</a>
        </div>
        @can('hostel.rooms.manage')
            <a href="{{ route('admin.hostel.rooms.create', $hostel) }}" class="btn btn-primary">Add Room</a>
        @endcan
    </div>
    @include('admin.hostel._flash')

    <div class="card"><div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Room</th><th>Floor</th><th>Type</th><th>Capacity</th><th>Beds</th><th>Fee</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                @forelse($rooms as $room)
                    <tr>
                        <td>{{ $room->room_number }}</td>
                        <td>{{ $room->floor ?: '—' }}</td>
                        <td>{{ $room->room_type ?: '—' }}</td>
                        <td>{{ $room->capacity }}</td>
                        <td>{{ $room->beds_count }}</td>
                        <td>₹{{ number_format((float)$room->monthly_fee,2) }}</td>
                        <td>{{ ucfirst($room->status) }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.hostel.rooms.show', $room) }}" class="btn btn-sm btn-outline-info">View</a>
                            @can('hostel.rooms.manage')
                                <a href="{{ route('admin.hostel.rooms.edit', $room) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="{{ route('admin.hostel.beds.index', $room) }}" class="btn btn-sm btn-outline-secondary">Beds</a>
                                <form method="POST" action="{{ route('admin.hostel.rooms.destroy', $room) }}" class="d-inline" onsubmit="return confirm('Delete this room?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">No rooms found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $rooms->links() }}
    </div></div>
</div>
@endsection
