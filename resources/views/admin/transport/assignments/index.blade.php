@extends('backend.layout.default')
@section('title','Transport Assignments')
@section('content')
<div class="container-fluid"><div class="d-flex justify-content-between mb-3"><h4>Student Transport Assignments</h4><a href="{{ route('admin.transport.assignments.create') }}" class="btn btn-primary">Assign Student</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"><div class="table-responsive"><table class="table"><thead><tr><th>Student</th><th>Route</th><th>Stop</th><th>Start</th><th>Monthly Fee</th><th>Status</th><th></th></tr></thead><tbody>@forelse($assignments as $a)<tr><td>{{ $a->student?->first_name }} {{ $a->student?->last_name }}</td><td>{{ $a->route?->name }}</td><td>{{ $a->stop?->name ?: '—' }}</td><td>{{ optional($a->start_date)->format('d M Y') }}</td><td>{{ number_format($a->monthly_fee,2) }}</td><td>{{ ucfirst($a->status) }}</td><td><form method="POST" action="{{ route('admin.transport.assignments.destroy',$a) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@empty<tr><td colspan="7">No assignments found.</td></tr>@endforelse</tbody></table></div><div class="p-3">{{ $assignments->links() }}</div></div></div>
@endsection
