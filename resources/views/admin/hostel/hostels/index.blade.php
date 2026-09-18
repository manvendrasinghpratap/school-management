@extends('backend.layout.default')
@section('title','Hostels')
@section('content')
<div class="container-fluid"><div class="d-flex justify-content-between mb-3"><h4>Hostels</h4><a href="{{ route('admin.hostel.create') }}" class="btn btn-primary">Add Hostel</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"><div class="table-responsive"><table class="table"><thead><tr><th>Name</th><th>Code</th><th>Type</th><th>Rooms</th><th>Allocations</th><th>Monthly Fee</th><th></th></tr></thead><tbody>@forelse($hostels as $h)<tr><td>{{ $h->name }}</td><td>{{ $h->code ?: '—' }}</td><td>{{ ucfirst($h->hostel_type) }}</td><td>{{ $h->rooms_count }}</td><td>{{ $h->allocations_count }}</td><td>{{ number_format($h->monthly_fee,2) }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.hostel.rooms.index',$h) }}">Rooms</a></td></tr>@empty<tr><td colspan="7">No hostels found.</td></tr>@endforelse</tbody></table></div><div class="p-3">{{ $hostels->links() }}</div></div></div>
@endsection
