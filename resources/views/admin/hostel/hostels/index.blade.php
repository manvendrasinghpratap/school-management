@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Hostels</h4>
        @can('hostel.manage')
            <a href="{{ route('admin.hostel.hostels.create') }}" class="btn btn-primary">Add Hostel</a>
        @endcan
    </div>

    @include('admin.hostel._flash')

    <form class="row g-2 mb-3">
        <div class="col-md-6">
            <input name="search" class="form-control" value="{{ request('search') }}" placeholder="Search hostel or code">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>
        </div>
        <div class="col-md-auto">
            <button class="btn btn-outline-primary">Filter</button>
            <a href="{{ route('admin.hostel.hostels.index') }}" class="btn btn-light">Reset</a>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Rooms</th>
                        <th>Warden</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($hostels as $hostel)
                        <tr>
                            <td>{{ $hostel->name }}</td>
                            <td>{{ $hostel->code ?: '—' }}</td>
                            <td>{{ ucfirst($hostel->hostel_type) }}</td>
                            <td>{{ $hostel->capacity }}</td>
                            <td>{{ $hostel->rooms_count }}</td>
                            <td>{{ $hostel->warden?->first_name }} {{ $hostel->warden?->last_name }}</td>
                            <td>
                                <span class="badge bg-{{ $hostel->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($hostel->status) }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                @can('hostel.view')
                                    <a href="{{ route('admin.hostel.hostels.show', $hostel) }}" class="btn btn-sm btn-outline-info">View</a>
                                @endcan
                                @can('hostel.manage')
                                    <a href="{{ route('admin.hostel.hostels.edit', $hostel) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('admin.hostel.hostels.destroy', $hostel) }}" class="d-inline" onsubmit="return confirm('Delete this hostel?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">No hostels found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $hostels->links() }}
        </div>
    </div>
</div>
@endsection
