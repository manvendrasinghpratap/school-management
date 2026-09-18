@extends('backend.layout.default')
@section('title','Add Transport Route')
@section('content')
<div class="container-fluid"><h4>Add Transport Route</h4><form method="POST" action="{{ route('admin.transport.routes.store') }}" class="card card-body">@csrf
<div class="row g-3"><div class="col-md-6"><label class="form-label">Name *</label><input name="name" class="form-control" required></div><div class="col-md-3"><label class="form-label">Code</label><input name="code" class="form-control"></div><div class="col-md-3"><label class="form-label">Monthly Fee *</label><input name="monthly_fee" type="number" step="0.01" class="form-control" value="0"></div>
<div class="col-md-6"><label class="form-label">Vehicle</label><select name="vehicle_id" class="form-select"><option value="">Select</option>@foreach($vehicles as $v)<option value="{{ $v->id }}">{{ $v->registration_number }} — {{ $v->vehicle_number }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Driver</label><select name="driver_id" class="form-select"><option value="">Select</option>@foreach($drivers as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Start Point</label><input name="start_point" class="form-control"></div><div class="col-md-6"><label class="form-label">End Point</label><input name="end_point" class="form-control"></div>
<div class="col-md-3"><label class="form-label">Departure</label><input type="time" name="departure_time" class="form-control"></div><div class="col-md-3"><label class="form-label">Arrival</label><input type="time" name="arrival_time" class="form-control"></div>
<div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div></div>
<button class="btn btn-primary mt-3">Save Route</button></form></div>
@endsection
