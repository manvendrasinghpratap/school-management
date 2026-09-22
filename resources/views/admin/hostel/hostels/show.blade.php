@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h4>Hostel Details</h4>
        <div>
            @can('hostel.manage')
                <a href="{{ route('admin.hostel.hostels.edit', $hostel) }}" class="btn btn-primary">Edit</a>
            @endcan
            <a href="{{ route('admin.hostel.rooms.index', $hostel) }}" class="btn btn-outline-primary">Manage Rooms</a>
            <a href="{{ route('admin.hostel.hostels.index') }}" class="btn btn-light">Back</a>
        </div>
    </div>
    @include('admin.hostel._flash')

    <div class="row">
        <div class="col-lg-6">
            <div class="card"><div class="card-header">Hostel Information</div><div class="card-body">
                <p><strong>Name:</strong> {{ $hostel->name }}</p>
                <p><strong>Code:</strong> {{ $hostel->code ?: '—' }}</p>
                <p><strong>Type:</strong> {{ ucfirst($hostel->hostel_type) }}</p>
                <p><strong>Capacity:</strong> {{ $hostel->capacity }}</p>
                <p><strong>Monthly Fee:</strong> ₹{{ number_format((float)$hostel->monthly_fee,2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($hostel->status) }}</p>
            </div></div>
        </div>
        <div class="col-lg-6">
            <div class="card"><div class="card-header">Additional Details</div><div class="card-body">
                <p><strong>Warden:</strong> {{ trim(($hostel->warden?->first_name ?? '').' '.($hostel->warden?->last_name ?? '')) ?: '—' }}</p>
                <p><strong>Address:</strong> {{ $hostel->address ?: '—' }}</p>
                <p><strong>Description:</strong> {{ $hostel->description ?: '—' }}</p>
                <p><strong>Rooms:</strong> {{ $hostel->rooms->count() }}</p>
            </div></div>
        </div>
    </div>
</div>
@endsection
