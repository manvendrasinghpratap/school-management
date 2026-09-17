@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Issue Certificate</h4>
            <div><a class="btn btn-secondary" href="{{ route('admin.certificates.index') }}">Back</a></div>
        </div>
    </div></div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

<form method="POST" action="{{ route('admin.certificates.store') }}">@csrf
<div class="card"><div class="card-body"><div class="row g-3">
<div class="col-md-5"><label class="form-label">Template</label><select name="template_id" class="form-select" required>@foreach($templates as $t)<option value="{{ $t->id }}">{{ $t->name }} ({{ $t->certificate_type }})</option>@endforeach</select></div>
<div class="col-md-7"><label class="form-label">Student</label><select name="student_id" class="form-select" required>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->student_number }} - {{ $s->first_name }} {{ $s->last_name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Issue Date</label><input type="date" name="issue_date" value="{{ now()->toDateString() }}" class="form-control" required></div>
<div class="col-md-8"><label class="form-label">Class/Course</label><input name="course_or_class" class="form-control"></div>
<div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control"></textarea></div>
</div></div><div class="card-footer"><button class="btn btn-primary">Issue Certificate</button></div></div></form></div>
@endsection
