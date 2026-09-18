@extends('backend.layout.default')
@section('title','Allocate Student Hostel')
@section('content')
<div class="container-fluid"><h4>Allocate Student to Hostel</h4><form method="POST" action="{{ route('admin.hostel.allocations.store') }}" class="card card-body">@csrf
<div class="row g-3"><div class="col-md-6"><label class="form-label">Student *</label><select name="student_id" class="form-select" required><option value="">Select</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} — {{ $s->student_number }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Hostel *</label><select name="hostel_id" class="form-select" required>@foreach($hostels as $h)<option value="{{ $h->id }}">{{ $h->name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Room *</label><select name="room_id" class="form-select" required><option value="">Select</option>@foreach($rooms as $r)<option value="{{ $r->id }}">{{ $r->room_number }} ({{ $r->capacity }})</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Bed</label><select name="bed_id" class="form-select"><option value="">Select</option>@foreach($beds as $b)<option value="{{ $b->id }}">{{ $b->room?->room_number }} — {{ $b->bed_number }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Monthly Fee *</label><input name="monthly_fee" type="number" step="0.01" value="0" class="form-control"></div>
<div class="col-md-4"><label class="form-label">Start Date *</label><input name="start_date" type="date" value="{{ date('Y-m-d') }}" class="form-control" required></div>
<div class="col-md-4"><label class="form-label">Status *</label><select name="status" class="form-select"><option value="allocated">Allocated</option><option value="checked_in">Checked In</option></select></div>
</div><button class="btn btn-primary mt-3">Allocate Student</button></form></div>
@endsection
