@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Event Participants</h4>
            <div><a class="btn btn-secondary" href="{{ route('admin.events.show',$event) }}">Back</a></div>
        </div>
    </div></div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

<div class="row"><div class="col-lg-6"><div class="card"><div class="card-header">Add Participant</div><div class="card-body">
<form method="POST" action="{{ route('admin.events.participants.add',$event) }}">@csrf
<div class="mb-3"><label class="form-label">Type</label><select name="participant_type" id="participant_type" class="form-select"><option value="student">Student</option><option value="staff">Staff</option></select></div>
<div class="mb-3"><label class="form-label">Participant</label><select name="participant_id" id="participant_id" class="form-select">
@foreach($students as $s)<option data-type="student" value="{{ $s->id }}">{{ $s->student_number }} - {{ $s->first_name }} {{ $s->last_name }}</option>@endforeach
@foreach($staff as $s)<option data-type="staff" value="{{ $s->id }}">{{ $s->staff_number }} - {{ $s->first_name }} {{ $s->last_name }}</option>@endforeach
</select></div><button class="btn btn-primary">Add</button>
</form></div></div></div>
<div class="col-lg-6"><div class="card"><div class="card-header">Current Participants</div><div class="card-body">
@forelse($event->participants as $p)<div class="d-flex justify-content-between border-bottom py-2"><span>{{ $p->student ? $p->student->first_name.' '.$p->student->last_name : ($p->staff ? $p->staff->first_name.' '.$p->staff->last_name : '-') }}</span><form method="POST" action="{{ route('admin.events.participants.remove',[$event,$p]) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Remove</button></form></div>@empty<p class="text-muted">No participants.</p>@endforelse
</div></div></div></div></div>
<script>document.getElementById('participant_type').addEventListener('change',function(){let t=this.value;document.querySelectorAll('#participant_id option').forEach(o=>o.hidden=o.dataset.type!==t);let first=[...document.querySelectorAll('#participant_id option')].find(o=>o.dataset.type===t);if(first)document.getElementById('participant_id').value=first.value;});document.getElementById('participant_type').dispatchEvent(new Event('change'));</script>
@endsection
