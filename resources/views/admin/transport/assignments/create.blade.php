@extends('backend.layout.default')
@section('title','Assign Student Transport')
@section('content')
<div class="container-fluid"><h4>Assign Student to Transport</h4><form method="POST" action="{{ route('admin.transport.assignments.store') }}" class="card card-body">@csrf
<div class="row g-3"><div class="col-md-6"><label class="form-label">Student *</label><select name="student_id" class="form-select" required><option value="">Select</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} — {{ $s->student_number }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Route *</label><select name="route_id" id="route_id" class="form-select" required><option value="">Select</option>@foreach($routes as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Stop</label><select name="stop_id" class="form-select"><option value="">Select</option>@foreach($routes as $r)@foreach($r->stops as $st)<option value="{{ $st->id }}">{{ $r->name }} — {{ $st->name }}</option>@endforeach @endforeach</select></div>
<div class="col-md-4"><label class="form-label">Start Date *</label><input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="form-control" required></div><div class="col-md-4"><label class="form-label">Monthly Fee *</label><input type="number" step="0.01" name="monthly_fee" value="0" class="form-control"></div>
</div><button class="btn btn-primary mt-3">Assign Student</button></form></div>
@endsection
