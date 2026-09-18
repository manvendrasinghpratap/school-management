@extends('backend.layout.default')
@section('title','Hostel Allocations')
@section('content')
<div class="container-fluid"><div class="d-flex justify-content-between mb-3"><h4>Hostel Allocations</h4><a href="{{ route('admin.hostel.allocations.create') }}" class="btn btn-primary">Allocate Student</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"><div class="table-responsive"><table class="table"><thead><tr><th>Student</th><th>Hostel</th><th>Room</th><th>Bed</th><th>Start</th><th>Status</th><th></th></tr></thead><tbody>@forelse($allocations as $a)<tr><td>{{ $a->student?->first_name }} {{ $a->student?->last_name }}</td><td>{{ $a->hostel?->name }}</td><td>{{ $a->room?->room_number }}</td><td>{{ $a->bed?->bed_number ?: '—' }}</td><td>{{ optional($a->start_date)->format('d M Y') }}</td><td>{{ ucfirst(str_replace('_',' ',$a->status)) }}</td><td>@if(in_array($a->status,['allocated','checked_in']))<form method="POST" action="{{ route('admin.hostel.allocations.checkout',$a) }}">@csrf<button class="btn btn-sm btn-outline-warning">Check Out</button></form>@endif</td></tr>@empty<tr><td colspan="7">No allocations found.</td></tr>@endforelse</tbody></table></div><div class="p-3">{{ $allocations->links() }}</div></div></div>
@endsection
