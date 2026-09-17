@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Certificates</h4>
            <div>@can('certificates.create')<a class="btn btn-primary" href="{{ route('admin.certificates.create') }}">Issue Certificate</a>@endcan</div>
        </div>
    </div></div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

<div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Number</th><th>Student</th><th>Type</th><th>Issue Date</th><th>Actions</th></tr></thead><tbody>
@forelse($certificates as $c)<tr><td>{{ $c->certificate_number }}</td><td>{{ $c->recipient_name }}</td><td>{{ $c->template->certificate_type }}</td><td>{{ $c->issue_date->format('d M Y') }}</td><td><a class="btn btn-sm btn-info" href="{{ route('admin.certificates.show',$c) }}">View</a> <a class="btn btn-sm btn-primary" target="_blank" href="{{ route('admin.certificates.print',$c) }}">Print</a></td></tr>@empty<tr><td colspan="5" class="text-center">No certificates found.</td></tr>@endforelse</tbody></table></div>{{ $certificates->links() }}</div></div></div>
@endsection
