@extends('backend.layout.default')
@section('title',$title)
@section('content')
<div class="container-fluid"><h4>{{ $title }}</h4>@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card card-body mb-3">
@if(str_contains($routePrefix,'drivers'))
<form method="POST" action="{{ route($routePrefix.'.store') }}" class="row g-2">@csrf<div class="col-md-4"><input name="name" class="form-control" placeholder="Driver name" required></div><div class="col-md-3"><input name="license_number" class="form-control" placeholder="License"></div><div class="col-md-3"><input name="phone" class="form-control" placeholder="Phone"></div><div class="col-md-2"><button class="btn btn-primary w-100">Add Driver</button></div></form>
@else
<form method="POST" action="{{ route($routePrefix.'.store') }}" class="row g-2">@csrf<div class="col-md-3"><input name="registration_number" class="form-control" placeholder="Registration *" required></div><div class="col-md-2"><input name="vehicle_number" class="form-control" placeholder="Vehicle No."></div><div class="col-md-2"><input name="vehicle_type" class="form-control" placeholder="Type"></div><div class="col-md-2"><input name="capacity" type="number" class="form-control" placeholder="Capacity"></div><div class="col-md-3"><button class="btn btn-primary w-100">Add Vehicle</button></div></form>
@endif
</div>
<div class="card"><div class="table-responsive"><table class="table"><thead><tr>@if(str_contains($routePrefix,'drivers'))<th>Name</th><th>License</th><th>Phone</th><th>Status</th>@else<th>Registration</th><th>Vehicle No.</th><th>Type</th><th>Capacity</th><th>Status</th>@endif</tr></thead><tbody>
@forelse($items as $x)<tr>@if(str_contains($routePrefix,'drivers'))<td>{{ $x->name }}</td><td>{{ $x->license_number ?: '—' }}</td><td>{{ $x->phone ?: '—' }}</td><td>{{ ucfirst($x->status) }}</td>@else<td>{{ $x->registration_number }}</td><td>{{ $x->vehicle_number ?: '—' }}</td><td>{{ $x->vehicle_type ?: '—' }}</td><td>{{ $x->capacity ?: '—' }}</td><td>{{ ucfirst($x->status) }}</td>@endif</tr>@empty<tr><td colspan="5">No records found.</td></tr>@endforelse
</tbody></table></div><div class="p-3">{{ $items->links() }}</div></div></div>
@endsection
