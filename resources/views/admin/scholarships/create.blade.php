@extends('backend.layout.default')
@section('content')
<div class="container-fluid">
<div class="page-title-box d-sm-flex align-items-center justify-content-between"><h4>Add Scholarship</h4><ol class="breadcrumb m-0"><li class="breadcrumb-item">Finance</li><li class="breadcrumb-item"><a href="{{ route('admin.scholarships.index') }}">Scholarships</a></li><li class="breadcrumb-item active">Create</li></ol></div>
@if($errors->any())<div class="alert alert-danger"><strong>Please correct the following:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="card"><div class="card-header"><h5 class="card-title mb-0">Scholarship Details</h5></div><div class="card-body">
<form method="POST" action="{{ route('admin.scholarships.store') }}">@csrf
<div class="row g-3"><div class="col-md-6"><label class="form-label">Scholarship Name <span class="text-danger">*</span></label><input name="name" value="{{ old('name') }}" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Type <span class="text-danger">*</span></label><select name="type" id="type" class="form-select" required><option value="percentage" @selected(old('type','percentage')==='percentage')>Percentage</option><option value="fixed" @selected(old('type')==='fixed')>Fixed Amount</option></select></div>
<div class="col-md-3"><label class="form-label">Value <span class="text-danger">*</span></label><div class="input-group"><input type="number" step="0.01" min="0" name="value" id="value" value="{{ old('value') }}" class="form-control" required><span class="input-group-text" id="valueSuffix">%</span></div></div>
<div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea></div>
<div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active',1))><label class="form-check-label">Active</label></div></div></div>
<div class="mt-4"><button class="btn btn-primary">Save Scholarship</button><a href="{{ route('admin.scholarships.index') }}" class="btn btn-light ms-2">Cancel</a></div>
</form></div></div></div>
@endsection
@push('scripts')<script>document.getElementById('type').addEventListener('change',function(){document.getElementById('valueSuffix').textContent=this.value==='percentage'?'%':'₹';});</script>@endpush
