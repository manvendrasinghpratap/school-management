@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Create Event</h4>
            <div><a class="btn btn-secondary" href="{{ route('admin.events.index') }}">Back</a></div>
        </div>
    </div></div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

<form method="POST" action="{{ route('admin.events.store') }}">@csrf
<div class="card"><div class="card-body"><div class="row g-3">
<div class="col-md-8"><label class="form-label">Title</label><input name="title" class="form-control" required></div>
<div class="col-md-4"><label class="form-label">Type</label><input name="event_type" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Starts</label><input type="datetime-local" name="starts_at" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Ends</label><input type="datetime-local" name="ends_at" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Venue</label><input name="venue" class="form-control"></div>
<div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['draft','scheduled','completed','cancelled'] as $v)<option value="{{ $v }}">{{ ucfirst($v) }}</option>@endforeach</select></div>
<div class="col-md-3 pt-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_public" value="1" checked id="public"><label class="form-check-label" for="public">Public event</label></div></div>
<div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="5" class="form-control"></textarea></div>
</div></div><div class="card-footer"><button class="btn btn-primary">Save Event</button></div></div></form></div>
@endsection
