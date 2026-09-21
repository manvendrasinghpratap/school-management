
@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">Vehicle Details</h4>
            <p class="text-muted mb-0">
                {{ $vehicle->vehicle_number }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.transport.vehicles.edit', $vehicle) }}"
               class="btn btn-primary">
                <i class="bx bx-edit-alt me-1"></i>
                Edit
            </a>

            <a href="{{ route('admin.transport.vehicles.index') }}"
               class="btn btn-light">
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
                        Vehicle Information
                    </h4>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered mb-0">

                            <tbody>

                                <tr>
                                    <th style="width:35%;">
                                        Vehicle Number
                                    </th>

                                    <td>
                                        {{ $vehicle->vehicle_number ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Registration Number
                                    </th>

                                    <td>
                                        {{ $vehicle->registration_number ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Vehicle Type
                                    </th>

                                    <td>
                                        {{ $vehicle->vehicle_type ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Capacity
                                    </th>

                                    <td>
                                        {{ $vehicle->capacity ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Driver Name
                                    </th>

                                    <td>
                                        {{ $vehicle->driver_name ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Driver Phone
                                    </th>

                                    <td>
                                        {{ $vehicle->driver_phone ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Status
                                    </th>

                                    <td>

                                        @if($vehicle->status === 'active')

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @elseif($vehicle->status === 'maintenance')

                                            <span class="badge bg-warning text-dark">
                                                Maintenance
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
                                        Created
                                    </th>

                                    <td>
                                        {{ $vehicle->created_at?->format('d M Y, h:i A') ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Last Updated
                                    </th>

                                    <td>
                                        {{ $vehicle->updated_at?->format('d M Y, h:i A') ?: '—' }}
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
                        Vehicle Status
                    </h4>

                </div>

                <div class="card-body text-center">

                    @if($vehicle->status === 'active')

                        <div class="avatar-md mx-auto mb-3">

                            <span class="avatar-title rounded-circle bg-success-subtle text-success font-size-24">
                                <i class="bx bx-check-circle"></i>
                            </span>

                        </div>

                        <h5 class="text-success">
                            Active Vehicle
                        </h5>

                        <p class="text-muted mb-0">
                            This vehicle is currently active and available for transport operations.
                        </p>

                    @elseif($vehicle->status === 'maintenance')

                        <div class="avatar-md mx-auto mb-3">

                            <span class="avatar-title rounded-circle bg-warning-subtle text-warning font-size-24">
                                <i class="bx bx-wrench"></i>
                            </span>

                        </div>

                        <h5 class="text-warning">
                            Maintenance
                        </h5>

                        <p class="text-muted mb-0">
                            This vehicle is currently under maintenance.
                        </p>

                    @else

                        <div class="avatar-md mx-auto mb-3">

                            <span class="avatar-title rounded-circle bg-secondary-subtle text-secondary font-size-24">
                                <i class="bx bx-pause-circle"></i>
                            </span>

                        </div>

                        <h5 class="text-secondary">
                            Inactive Vehicle
                        </h5>

                        <p class="text-muted mb-0">
                            This vehicle is currently inactive.
                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection