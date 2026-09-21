@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">Transport Route Details</h4>

            <p class="text-muted mb-0">
                {{ $transportRoute->name }}
                @if($transportRoute->code)
                    — {{ $transportRoute->code }}
                @endif
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.transport.routes.stops.index', $transportRoute) }}"
                class="btn btn-info"
            >
                <i class="bx bx-map-pin me-1"></i>
                Manage Stops
            </a>

            <a
                href="{{ route('admin.transport.routes.edit', $transportRoute) }}"
                class="btn btn-primary"
            >
                <i class="bx bx-edit-alt me-1"></i>
                Edit
            </a>

            <a
                href="{{ route('admin.transport.routes.index') }}"
                class="btn btn-light"
            >
                <i class="bx bx-arrow-back me-1"></i>
                Back
            </a>

        </div>

    </div>

    <div class="row">

        <div class="col-xl-8">

            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">
                        Route Information
                    </h4>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered mb-0">

                            <tbody>

                                <tr>
                                    <th style="width:35%;">
                                        Route Name
                                    </th>
                                    <td>
                                        {{ $transportRoute->name ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Route Code
                                    </th>
                                    <td>
                                        {{ $transportRoute->code ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Vehicle
                                    </th>
                                    <td>
                                        {{ $transportRoute->vehicle?->vehicle_number ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Driver
                                    </th>
                                    <td>
                                        {{ $transportRoute->driver?->name ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Start Point
                                    </th>
                                    <td>
                                        {{ $transportRoute->start_point ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        End Point
                                    </th>
                                    <td>
                                        {{ $transportRoute->end_point ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Departure Time
                                    </th>
                                    <td>
                                        {{ $transportRoute->departure_time ? \Carbon\Carbon::parse($transportRoute->departure_time)->format('h:i A') : '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Arrival Time
                                    </th>
                                    <td>
                                        {{ $transportRoute->arrival_time ? \Carbon\Carbon::parse($transportRoute->arrival_time)->format('h:i A') : '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Monthly Fee
                                    </th>
                                    <td>
                                        @if($transportRoute->monthly_fee !== null)
                                            ₹{{ number_format((float) $transportRoute->monthly_fee, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Status
                                    </th>
                                    <td>

                                        @if($transportRoute->status === 'active')

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Notes
                                    </th>
                                    <td>
                                        {!! nl2br(e($transportRoute->notes ?: '—')) !!}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Created
                                    </th>
                                    <td>
                                        {{ $transportRoute->created_at?->format('d M Y, h:i A') ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Last Updated
                                    </th>
                                    <td>
                                        {{ $transportRoute->updated_at?->format('d M Y, h:i A') ?: '—' }}
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-4">

            <div class="card">

                <div class="card-header">

                    <h4 class="card-title mb-0">
                        Route Summary
                    </h4>

                </div>

                <div class="card-body">

                    <div class="text-center mb-4">

                        <div class="avatar-md mx-auto mb-3">

                            <span class="avatar-title rounded-circle bg-info-subtle text-info font-size-24">
                                <i class="bx bx-map"></i>
                            </span>

                        </div>

                        <h5 class="mb-1">
                            {{ $transportRoute->name }}
                        </h5>

                        <p class="text-muted mb-0">
                            {{ $transportRoute->code ?: 'No route code' }}
                        </p>

                    </div>

                    <div class="border-top pt-3">

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                Stops
                            </span>

                            <strong>
                                {{ $transportRoute->stops->count() }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                Vehicle
                            </span>

                            <strong>
                                {{ $transportRoute->vehicle?->vehicle_number ?: '—' }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">
                                Driver
                            </span>

                            <strong>
                                {{ $transportRoute->driver?->name ?: '—' }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card mt-3">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>
                <h4 class="card-title mb-1">
                    Route Stops
                </h4>

                <p class="text-muted mb-0">
                    Stops configured for this transport route.
                </p>
            </div>

            <a
                href="{{ route('admin.transport.routes.stops.index', $transportRoute) }}"
                class="btn btn-outline-primary"
            >
                <i class="bx bx-map-pin me-1"></i>
                Manage Stops
            </a>

        </div>

        <div class="card-body">

            @if($transportRoute->stops->count())

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Stop</th>
                                <th>Pickup</th>
                                <th>Drop-off</th>
                                <th>Address</th>
                                <th>Monthly Fee</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($transportRoute->stops as $stop)

                                <tr>

                                    <td>
                                        {{ $stop->sequence_no }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $stop->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $stop->pickup_time ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $stop->dropoff_time ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $stop->address ?: '—' }}
                                    </td>

                                    <td>
                                        @if($stop->monthly_fee !== null)
                                            ₹{{ number_format((float) $stop->monthly_fee, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-4 text-muted">

                    <i class="bx bx-map-pin font-size-24 d-block mb-2"></i>

                    No stops have been added to this route yet.

                    <div class="mt-3">

                        <a
                            href="{{ route('admin.transport.routes.stops.create', $transportRoute) }}"
                            class="btn btn-sm btn-primary"
                        >
                            Add First Stop
                        </a>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection