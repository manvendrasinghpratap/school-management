@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Create Announcement</h4>
            <div><a class="btn btn-secondary" href="{{ route('admin.announcements.index') }}">Back</a></div>
        </div>
    </div></div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

<form method="POST" action="{{ route('admin.announcements.store') }}">@csrf
<div class="card"><div class="card-body">
<div class="row g-3">
<div class="col-md-8"><label class="form-label">Title</label><input name="title" class="form-control" value="{{ old('title') }}" required></div>
<div class="col-md-4"><label class="form-label">Audience</label><select name="audience_type" class="form-select">@foreach(['all','students','parents','staff','class','section'] as $v)<option value="{{ $v }}">{{ ucfirst($v) }}</option>@endforeach</select></div>
<div class="col-12"><label class="form-label">Message</label><textarea name="body" rows="7" class="form-control" required>{{ old('body') }}</textarea></div>
<div class="col-md-4"><label class="form-label">Class</label><select name="class_id" class="form-select"><option value="">All classes</option>@foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Section</label><select name="section_id" class="form-select"><option value="">All sections</option>@foreach($sections as $s)<option value="{{ $s->id }}">{{ $s->name }} ({{ $s->code }})</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Publish At</label><input type="datetime-local" name="publish_at" class="form-control"></div>
<div class="col-md-4"><label class="form-label">Expires At</label><input type="datetime-local" name="expires_at" class="form-control"></div>
<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="draft">Draft</option><option value="published">Published</option><option value="archived">Archived</option></select></div>
<div class="col-md-4 pt-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_pinned" value="1" id="pin"><label class="form-check-label" for="pin">Pin announcement</label></div></div>
</div>
</div><div class="card-footer"><button class="btn btn-primary">Save Announcement</button></div></div>
</form></div>
@endsection
