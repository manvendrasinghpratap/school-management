@extends('backend.layout.default')
@section('title','Add Hostel')
@section('content')
<div class="container-fluid"><h4>Add Hostel</h4><form method="POST" action="{{ route('admin.hostel.store') }}" class="card card-body">@csrf
<div class="row g-3"><div class="col-md-6"><label class="form-label">Name *</label><input name="name" class="form-control" required></div><div class="col-md-3"><label class="form-label">Code</label><input name="code" class="form-control"></div><div class="col-md-3"><label class="form-label">Type *</label><select name="hostel_type" class="form-select"><option value="boys">Boys</option><option value="girls">Girls</option><option value="mixed">Mixed</option><option value="staff">Staff</option></select></div>
<div class="col-md-4"><label class="form-label">Warden</label><select name="warden_staff_id" class="form-select"><option value="">Select</option>@foreach($staff as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }}</option>@endforeach</select></div><div class="col-md-4"><label class="form-label">Capacity *</label><input type="number" name="capacity" value="0" class="form-control"></div><div class="col-md-4"><label class="form-label">Monthly Fee *</label><input type="number" step="0.01" name="monthly_fee" value="0" class="form-control"></div>
<div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control"></textarea></div></div><button class="btn btn-primary mt-3">Save Hostel</button></form></div>
@endsection
