@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Create Transport Fee</h4>
        <a href="{{ route('admin.transport.fees.index') }}" class="btn btn-light">Back</a>
    </div>

    @include('admin.transport._flash')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.transport.fees.store') }}">
        @csrf

        @include('admin.transport.fees.form')

        <div class="mt-3">
            <button class="btn btn-primary">Create Transport Fee</button>
            <a href="{{ route('admin.transport.fees.index') }}" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>
@endsection
