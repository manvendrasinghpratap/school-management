@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <h4>Create Hostel Fee</h4>
    @include('admin.hostel._flash')
    <form method="POST" action="{{ route('admin.hostel.fees.store') }}">
        @csrf
        @include('admin.hostel.fees.form')
        <button class="btn btn-primary mt-3">Create Hostel Fee</button>
        <a href="{{ route('admin.hostel.fees.index') }}" class="btn btn-light mt-3">Cancel</a>
    </form>
</div>
@endsection
