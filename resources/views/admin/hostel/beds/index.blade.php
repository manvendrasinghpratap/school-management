@extends('backend.layout.default')
@section('title','Room Beds')
@section('content')
<div class="container-fluid"><h4>Room {{ $room->room_number }} — Beds</h4>@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form method="POST" action="{{ route('admin.hostel.rooms.beds.store',$room) }}" class="card card-body mb-3">@csrf<div class="row g-2"><div class="col-md-4"><input name="bed_number" class="form-control" placeholder="Bed number" required></div><div class="col-md-4"><select name="status" class="form-select"><option value="available">Available</option><option value="maintenance">Maintenance</option><option value="inactive">Inactive</option></select></div><div class="col-md-4"><button class="btn btn-primary w-100">Add Bed</button></div></div></form>
<div class="card"><table class="table"><thead><tr><th>Bed</th><th>Status</th></tr></thead><tbody>@forelse($beds as $b)<tr><td>{{ $b->bed_number }}</td><td>{{ ucfirst($b->status) }}</td></tr>@empty<tr><td colspan="2">No beds found.</td></tr>@endforelse</tbody></table><div class="p-3">{{ $beds->links() }}</div></div></div>
@endsection
