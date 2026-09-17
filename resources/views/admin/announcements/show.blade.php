@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Announcement</h4>
            <div><a class="btn btn-secondary" href="{{ route('admin.announcements.index') }}">Back</a></div>
        </div>
    </div></div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

<div class="card"><div class="card-body">
<h4>{{ $announcement->title }}</h4>
<div class="text-muted mb-3">{{ ucfirst($announcement->status) }} · {{ ucfirst($announcement->audience_type) }} · {{ optional($announcement->publish_at)->format('d M Y H:i') ?? 'Not scheduled' }}</div>
<div class="border rounded p-3">{!! nl2br(e($announcement->body)) !!}</div>
</div></div></div>
@endsection
