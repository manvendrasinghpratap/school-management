@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Certificate</h4>
            <div><a class="btn btn-secondary" href="{{ route('admin.certificates.index') }}">Back</a></div>
        </div>
    </div></div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

<div class="card"><div class="card-body"><h4>{{ $certificate->certificate_number }}</h4><p><strong>{{ $certificate->recipient_name }}</strong></p><p>{{ $certificate->template->certificate_type }} · Issued {{ $certificate->issue_date->format('d M Y') }}</p><a class="btn btn-primary" target="_blank" href="{{ route('admin.certificates.print',$certificate) }}">Print Certificate</a></div></div></div>
@endsection
