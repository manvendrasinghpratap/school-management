@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">ID Card</h4>
            <div><a class="btn btn-secondary" href="{{ route('admin.id-cards.index') }}">Back</a></div>
        </div>
    </div></div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

<div class="card"><div class="card-body"><h4>{{ $idCard->holder_name }}</h4><p class="text-muted">{{ $idCard->card_number }}</p><dl class="row"><dt class="col-sm-3">Type</dt><dd class="col-sm-9">{{ $idCard->student_id ? 'Student' : 'Staff' }}</dd><dt class="col-sm-3">Issued</dt><dd class="col-sm-9">{{ $idCard->issued_at->format('d M Y') }}</dd><dt class="col-sm-3">Expires</dt><dd class="col-sm-9">{{ optional($idCard->expires_at)->format('d M Y') ?? '-' }}</dd></dl><a class="btn btn-primary" target="_blank" href="{{ route('admin.id-cards.print',$idCard) }}">Print ID Card</a></div></div></div>
@endsection
