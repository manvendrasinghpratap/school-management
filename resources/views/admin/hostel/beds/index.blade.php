@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Beds — Room {{ $room->room_number }}</h4>
            <a href="{{ route('admin.hostel.rooms.show', $room) }}">← Back to Room</a>
        </div>
        @can('hostel.rooms.manage')
            <a href="{{ route('admin.hostel.beds.create', $room) }}" class="btn btn-primary">Add Bed</a>
        @endcan
    </div>
    @include('admin.hostel._flash')
    <div class="card"><div class="card-body">
        <table class="table align-middle">
            <thead><tr><th>Bed</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($beds as $bed)
                <tr>
                    <td>{{ $bed->bed_number }}</td>
                    <td>{{ ucfirst($bed->status) }}</td>
                    <td>
                        <a href="{{ route('admin.hostel.beds.show', $bed) }}" class="btn btn-sm btn-outline-info">View</a>
                        @can('hostel.rooms.manage')
                            <a href="{{ route('admin.hostel.beds.edit', $bed) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.hostel.beds.destroy', $bed) }}" class="d-inline" onsubmit="return confirm('Delete this bed?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center text-muted">No beds found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $beds->links() }}
    </div></div>
</div>
@endsection
