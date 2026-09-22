@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <h4>Edit Hostel Fee</h4>
    @include('admin.hostel._flash')
    <form method="POST" action="{{ route('admin.hostel.fees.update', $fee) }}">
        @csrf @method('PUT')
        @include('admin.hostel.fees.form', ['fee' => $fee])
        <button class="btn btn-primary mt-3">Update Hostel Fee</button>
        <a href="{{ route('admin.hostel.fees.show', $fee) }}" class="btn btn-light mt-3">Cancel</a>
    </form>
</div>
@endsection
