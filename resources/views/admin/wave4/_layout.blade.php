@extends('backend.layout.default')
@section('content')
<div class="container-fluid">
<div class="row"><div class="col-12"><div class="page-title-box d-sm-flex align-items-center justify-content-between"><h4 class="mb-sm-0">{{ $title }}</h4>@if(isset($createRoute))<a href="{{ route($createRoute) }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i>Add</a>@endif</div></div></div>
@include('admin.wave4._messages')
{{ $slot ?? '' }}
</div>
@endsection
