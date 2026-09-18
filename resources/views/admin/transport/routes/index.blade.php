@extends('backend.layout.default')
@section('title','Transport Routes')
@section('content')
<div class="container-fluid"><div class="d-flex justify-content-between mb-3"><h4>Transport Routes</h4><a href="{{ route('admin.transport.routes.create') }}" class="btn btn-primary">Add Route</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"><div class="table-responsive"><table class="table"><thead><tr><th>Name</th><th>Code</th><th>Vehicle</th><th>Driver</th><th>Stops</th><th>Monthly Fee</th><th>Status</th></tr></thead><tbody>@forelse($routes as $r)<tr><td>{{ $r->name }}</td><td>{{ $r->code ?: '—' }}</td><td>{{ $r->vehicle?->registration_number ?: '—' }}</td><td>{{ $r->driver?->name ?: '—' }}</td><td>{{ $r->stops->count() }}</td><td>{{ number_format($r->monthly_fee,2) }}</td><td>{{ ucfirst($r->status) }}</td></tr>@empty<tr><td colspan="7">No routes found.</td></tr>@endforelse</tbody></table></div><div class="p-3">{{ $routes->links() }}</div></div></div>
@endsection
