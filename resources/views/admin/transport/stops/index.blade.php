@extends('backend.layout.default')
@section('title','Route Stops')
@section('content')
<div class="container-fluid"><h4>{{ $route->name }} — Stops</h4>@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form method="POST" action="{{ route('admin.transport.routes.stops.store',$route) }}" class="card card-body mb-3">@csrf<div class="row g-2"><div class="col-md-4"><input name="name" class="form-control" placeholder="Stop name" required></div><div class="col-md-2"><input name="sequence_no" type="number" min="1" value="1" class="form-control"></div><div class="col-md-2"><input name="pickup_time" type="time" class="form-control"></div><div class="col-md-2"><input name="dropoff_time" type="time" class="form-control"></div><div class="col-md-2"><button class="btn btn-primary w-100">Add Stop</button></div></div></form>
<div class="card"><table class="table"><thead><tr><th>#</th><th>Stop</th><th>Pickup</th><th>Drop-off</th></tr></thead><tbody>@forelse($stops as $s)<tr><td>{{ $s->sequence_no }}</td><td>{{ $s->name }}</td><td>{{ $s->pickup_time ?: '—' }}</td><td>{{ $s->dropoff_time ?: '—' }}</td></tr>@empty<tr><td colspan="4">No stops found.</td></tr>@endforelse</tbody></table><div class="p-3">{{ $stops->links() }}</div></div></div>
@endsection
