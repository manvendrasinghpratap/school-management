
@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Transport Fee Details</h4>

        <div>
            @can('transport.fees.manage')
                <a
                    href="{{ route('admin.transport.fees.edit', $fee) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>
            @endcan

            <a
                href="{{ route('admin.transport.fees.index') }}"
                class="btn btn-light"
            >
                Back
            </a>
        </div>
    </div>

    @include('admin.transport._flash')

    @php
        $student = $fee->routeStudent?->student;
        $route = $fee->routeStudent?->route;
        $vehicle = $route?->vehicle;
        $driver = $route?->driver;
    @endphp

    <div class="row">

        {{-- Student --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Student Information</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label class="text-muted">Student</label>
                        <div class="fw-medium">
                            {{ trim(
                                ($student->first_name ?? '') . ' ' .
                                ($student->middle_name ?? '') . ' ' .
                                ($student->last_name ?? '')
                            ) ?: '—' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Student Number</label>
                        <div>
                            {{ $student?->student_number ?? '—' }}
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted">Route Assignment</label>
                        <div>
                            #{{ $fee->route_student_id }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Route --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Transport Route</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label class="text-muted">Route</label>
                        <div class="fw-medium">
                            {{ $route?->name ?? '—' }}

                            @if($route?->code)
                                <span class="text-muted">
                                    ({{ $route->code }})
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Pickup Point</label>
                        <div>
                            {{ $fee->routeStudent?->pickup_point ?? '—' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Dropoff Point</label>
                        <div>
                            {{ $fee->routeStudent?->dropoff_point ?? '—' }}
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted">Route Status</label>
                        <div>
                            @if($route?->status)
                                <span class="badge bg-{{ $route->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($route->status) }}
                                </span>
                            @else
                                —
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Fee --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Fee Information</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label class="text-muted">Fee Month</label>
                        <div class="fw-medium">
                            {{ $fee->fee_month?->format('d M Y') ?? '—' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Amount</label>
                        <div class="fs-5 fw-semibold">
                            ₹{{ number_format((float) $fee->amount, 2) }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Status</label>
                        <div>
                            @php
                                $badge = match($fee->status) {
                                    'paid' => 'success',
                                    'waived' => 'secondary',
                                    'invoiced' => 'info',
                                    default => 'warning',
                                };
                            @endphp

                            <span class="badge bg-{{ $badge }}">
                                {{ ucfirst($fee->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted">Invoice</label>
                        <div>
                            {{ $fee->invoice_id ? '#' . $fee->invoice_id : 'Not linked' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Vehicle / Driver --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Transport Details</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label class="text-muted">Vehicle</label>
                        <div>
                            {{ $vehicle?->vehicle_number ?? '—' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Driver</label>
                        <div>
                            {{ $driver?->name ?? '—' }}
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted">Assignment Status</label>
                        <div>
                            @if($fee->routeStudent?->status)
                                <span class="badge bg-{{ $fee->routeStudent->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($fee->routeStudent->status) }}
                                </span>
                            @else
                                —
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($fee->notes)
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notes</h5>
                    </div>

                    <div class="card-body">
                        {!! nl2br(e($fee->notes)) !!}
                    </div>
                </div>
            </div>
        @endif

    </div>

</div>
@endsection