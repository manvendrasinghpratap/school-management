@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h4>Hostel Fee Details</h4>
        <div>
            @can('hostel.fees.manage')
                <a href="{{ route('admin.hostel.fees.edit', $fee) }}" class="btn btn-primary">Edit</a>
            @endcan
            <a href="{{ route('admin.hostel.fees.index') }}" class="btn btn-light">Back</a>
        </div>
    </div>
    @include('admin.hostel._flash')

    <div class="card"><div class="card-body">
        <p><strong>Student:</strong> {{ trim(($fee->allocation->student->first_name ?? '').' '.($fee->allocation->student->last_name ?? '')) }}</p>
        <p><strong>Hostel:</strong> {{ $fee->allocation->hostel->name ?? '—' }}</p>
        <p><strong>Room:</strong> {{ $fee->allocation->room->room_number ?? '—' }}</p>
        <p><strong>Bed:</strong> {{ $fee->allocation->bed->bed_number ?? '—' }}</p>
        <p><strong>Academic Year:</strong> {{ $fee->allocation->academicYear->name ?? '—' }}</p>
        <p><strong>Fee Month:</strong> {{ $fee->fee_month?->format('d M Y') }}</p>
        <p><strong>Amount:</strong> ₹{{ number_format((float)$fee->amount,2) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($fee->status) }}</p>
        <p><strong>Invoice:</strong> {{ $fee->invoice_id ? '#'.$fee->invoice_id : 'Not linked' }}</p>
        <p><strong>Notes:</strong> {{ $fee->notes ?: '—' }}</p>
    </div></div>
</div>
@endsection
