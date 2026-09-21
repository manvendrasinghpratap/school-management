@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Driver Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.transport.drivers.index') }}">
                                Transport Drivers
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Driver Details
                        </li>
                    </ol>
                </div>
            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-xl-8">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-1">
                            {{ $driver->name }}
                        </h4>

                        <p class="text-muted mb-0">
                            Driver Information
                        </p>
                    </div>

                    <div class="d-flex gap-2">

                        <a href="{{ route('admin.transport.drivers.edit', $driver) }}"
                           class="btn btn-primary">
                            <i class="bx bx-edit-alt me-1"></i>
                            Edit
                        </a>

                        <a href="{{ route('admin.transport.drivers.index') }}"
                           class="btn btn-light">
                            <i class="bx bx-arrow-back me-1"></i>
                            Back
                        </a>

                    </div>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered mb-0">

                            <tbody>

                                <tr>
                                    <th style="width: 35%;">
                                        Driver Name
                                    </th>
                                    <td>
                                        {{ $driver->name ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Staff Member
                                    </th>
                                    <td>
                                        @if($driver->staff)
                                            {{ trim(($driver->staff->first_name ?? '') . ' ' . ($driver->staff->last_name ?? '')) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        License Number
                                    </th>
                                    <td>
                                        {{ $driver->license_number ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        License Expiry
                                    </th>
                                    <td>
                                        {{ $driver->license_expiry ? \Carbon\Carbon::parse($driver->license_expiry)->format('d M Y') : '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Phone
                                    </th>
                                    <td>
                                        {{ $driver->phone ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Address
                                    </th>
                                    <td>
                                        {{ $driver->address ?: '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Status
                                    </th>
                                    <td>
                                        @if($driver->status === 'active')
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @elseif($driver->status === 'inactive')
                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>
                                        @elseif($driver->status === 'suspended')
                                            <span class="badge bg-warning text-dark">
                                                Suspended
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">
                                                {{ ucfirst($driver->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Created
                                    </th>
                                    <td>
                                        {{ $driver->created_at ? $driver->created_at->format('d M Y, h:i A') : '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Last Updated
                                    </th>
                                    <td>
                                        {{ $driver->updated_at ? $driver->updated_at->format('d M Y, h:i A') : '—' }}
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
                        Driver Status
                    </h4>
                </div>

                <div class="card-body text-center">

                    @if($driver->status === 'active')

                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-success-subtle text-success font-size-24">
                                <i class="bx bx-check-circle"></i>
                            </span>
                        </div>

                        <h5 class="text-success">
                            Active Driver
                        </h5>

                        <p class="text-muted mb-0">
                            This driver is currently active and available for transport assignments.
                        </p>

                    @elseif($driver->status === 'inactive')

                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-secondary-subtle text-secondary font-size-24">
                                <i class="bx bx-pause-circle"></i>
                            </span>
                        </div>

                        <h5 class="text-secondary">
                            Inactive Driver
                        </h5>

                        <p class="text-muted mb-0">
                            This driver is currently inactive.
                        </p>

                    @else

                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-warning-subtle text-warning font-size-24">
                                <i class="bx bx-error-circle"></i>
                            </span>
                        </div>

                        <h5 class="text-warning">
                            Suspended Driver
                        </h5>

                        <p class="text-muted mb-0">
                            This driver is currently suspended.
                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection