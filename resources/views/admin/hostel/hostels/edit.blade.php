@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <h4>Edit Hostel</h4>
    @include('admin.hostel._flash')

    <form method="POST" action="{{ route('admin.hostel.hostels.update', $hostel) }}">
        @csrf @method('PUT')
        @include('admin.hostel.hostels.form', ['hostel' => $hostel])
        <button class="btn btn-primary">Update Hostel</button>
        <a href="{{ route('admin.hostel.hostels.show', $hostel) }}" class="btn btn-light">Cancel</a>
    </form>
</div>
@endsection
