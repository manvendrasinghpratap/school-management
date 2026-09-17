@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Issue ID Card</h4>
            <div><a class="btn btn-secondary" href="{{ route('admin.id-cards.index') }}">Back</a></div>
        </div>
    </div></div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

<form method="POST" action="{{ route('admin.id-cards.store') }}">@csrf
<div class="card"><div class="card-body"><div class="row g-3">
<div class="col-md-4"><label class="form-label">Template</label><select name="template_id" class="form-select" required>@foreach($templates as $t)<option value="{{ $t->id }}">{{ $t->name }} ({{ ucfirst($t->card_type) }})</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Holder Type</label><select name="holder_type" id="holder_type" class="form-select"><option value="student">Student</option><option value="staff">Staff</option></select></div>
<div class="col-md-4"><label class="form-label">Issued At</label><input type="date" name="issued_at" value="{{ now()->toDateString() }}" class="form-control" required></div>
<div class="col-md-6" id="student_box"><label class="form-label">Student</label><select name="student_id" class="form-select">@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->student_number }} - {{ $s->first_name }} {{ $s->last_name }}</option>@endforeach</select></div>
<div class="col-md-6 d-none" id="staff_box"><label class="form-label">Staff</label><select name="staff_id" class="form-select">@foreach($staff as $s)<option value="{{ $s->id }}">{{ $s->staff_number }} - {{ $s->first_name }} {{ $s->last_name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Expires At</label><input type="date" name="expires_at" class="form-control"></div>
</div></div><div class="card-footer"><button class="btn btn-primary">Issue Card</button></div></div></form>
<script>document.getElementById('holder_type').addEventListener('change',function(){document.getElementById('student_box').classList.toggle('d-none',this.value!=='student');document.getElementById('staff_box').classList.toggle('d-none',this.value!=='staff');});</script>
</div>
@endsection
