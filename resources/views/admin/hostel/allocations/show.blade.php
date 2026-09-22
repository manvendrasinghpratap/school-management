@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h4>Hostel Allocation</h4>
        <a href="{{ route('admin.hostel.allocations.index') }}" class="btn btn-light">Back</a>
    </div>
    @include('admin.hostel._flash')

    <div class="row">
        <div class="col-lg-6">
            <div class="card"><div class="card-header">Student</div><div class="card-body">
                <p><strong>Name:</strong> {{ trim(($allocation->student->first_name ?? '').' '.($allocation->student->last_name ?? '')) }}</p>
                <p><strong>Student Number:</strong> {{ $allocation->student->student_number ?? '—' }}</p>
                <p><strong>Academic Year:</strong> {{ $allocation->academicYear->name ?? '—' }}</p>
            </div></div>
        </div>
        <div class="col-lg-6">
            <div class="card"><div class="card-header">Accommodation</div><div class="card-body">
                <p><strong>Hostel:</strong> {{ $allocation->hostel->name ?? '—' }}</p>
                <p><strong>Room:</strong> {{ $allocation->room->room_number ?? '—' }}</p>
                <p><strong>Bed:</strong> {{ $allocation->bed->bed_number ?? '—' }}</p>
                <p><strong>Monthly Fee:</strong> ₹{{ number_format((float)$allocation->monthly_fee,2) }}</p>
            </div></div>
        </div>
        <div class="col-12">
            <div class="card"><div class="card-body">
                <p><strong>Start:</strong> {{ $allocation->start_date?->format('d M Y') }}</p>
                <p><strong>End:</strong> {{ $allocation->end_date?->format('d M Y') ?? '—' }}</p>
                <p><strong>Status:</strong> {{ ucfirst(str_replace('_',' ',$allocation->status)) }}</p>
                <p><strong>Notes:</strong> {{ $allocation->notes ?: '—' }}</p>

                @if(in_array($allocation->status, ['allocated','checked_in'], true))
                    @can('hostel.allocations.manage')
                        <form method="POST" action="{{ route('admin.hostel.allocations.checkout', $allocation) }}" onsubmit="return confirm('Check out this student?')">
                            @csrf
                            <button class="btn btn-warning">Checkout Student</button>
                        </form>
                    @endcan
                @endif
            </div></div>
        </div>
    </div>
</div>
@endsection
