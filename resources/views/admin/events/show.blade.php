@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-sm-1">Event Details</h4>
                    <p class="text-muted mb-0">
                        View school event information and participants.
                    </p>
                </div>

                <div class="page-title-right">
                    <div class="d-flex gap-2">

                        <a href="{{ route('admin.events.index') }}"
                           class="btn btn-light">
                            <i class="ri-arrow-left-line align-middle me-1"></i>
                            Back
                        </a>

                        @can('events.update')
                            <a href="{{ route('admin.events.edit', $event) }}"
                               class="btn btn-warning">
                                <i class="ri-edit-line align-middle me-1"></i>
                                Edit
                            </a>
                        @endcan

                        @can('events.participants.manage')
                            <a href="{{ route('admin.events.participants', $event) }}"
                               class="btn btn-primary">
                                <i class="ri-group-line align-middle me-1"></i>
                                Participants
                            </a>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    <div class="row">

        {{-- Event Information --}}
        <div class="col-lg-8">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Event Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="mb-4">
                        <h4 class="mb-2">
                            {{ $event->title }}
                        </h4>

                        @switch($event->status)

                            @case('draft')
                                <span class="badge bg-secondary-subtle text-secondary">
                                    Draft
                                </span>
                                @break

                            @case('scheduled')
                                <span class="badge bg-primary-subtle text-primary">
                                    Scheduled
                                </span>
                                @break

                            @case('completed')
                                <span class="badge bg-success-subtle text-success">
                                    Completed
                                </span>
                                @break

                            @case('cancelled')
                                <span class="badge bg-danger-subtle text-danger">
                                    Cancelled
                                </span>
                                @break

                            @default
                                <span class="badge bg-light text-dark">
                                    {{ ucfirst($event->status ?? 'Unknown') }}
                                </span>

                        @endswitch
                    </div>

                    <div class="row">

                        {{-- Event Type --}}
                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Event Type
                            </div>

                            <div class="fw-medium">
                                {{ $event->event_type ?: '-' }}
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Location
                            </div>

                            <div class="fw-medium">
                                {{ $event->location ?: '-' }}
                            </div>
                        </div>

                        {{-- Start --}}
                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Start Date & Time
                            </div>

                            <div class="fw-medium">
                                @if($event->start_datetime)
                                    {{ $event->start_datetime->format('d M Y, h:i A') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        {{-- End --}}
                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                End Date & Time
                            </div>

                            <div class="fw-medium">
                                @if($event->end_datetime)
                                    {{ $event->end_datetime->format('d M Y, h:i A') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        {{-- Public --}}
                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Visibility
                            </div>

                            @if($event->is_public)
                                <span class="badge bg-success-subtle text-success">
                                    Public
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">
                                    Private
                                </span>
                            @endif
                        </div>

                        {{-- Created By --}}
                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Created By
                            </div>

                            <div class="fw-medium">
                                {{ $event->creator?->name ?? '-' }}
                            </div>
                        </div>

                        {{-- Created At --}}
                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Created At
                            </div>

                            <div class="fw-medium">
                                @if($event->created_at)
                                    {{ $event->created_at->format('d M Y, h:i A') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        {{-- Updated At --}}
                        <div class="col-md-6 mb-4">
                            <div class="text-muted small mb-1">
                                Last Updated
                            </div>

                            <div class="fw-medium">
                                @if($event->updated_at)
                                    {{ $event->updated_at->format('d M Y, h:i A') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- Description --}}
                    <div class="border-top pt-4">

                        <h6 class="mb-3">
                            Description
                        </h6>

                        @if($event->description)
                            <div class="text-muted">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                        @else
                            <div class="text-muted">
                                No description provided.
                            </div>
                        @endif

                    </div>

                </div>
            </div>

        </div>

        {{-- Participants --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        Participants
                    </h5>

                    <span class="badge bg-primary-subtle text-primary">
                        {{ $event->participants->count() }}
                    </span>
                </div>

                <div class="card-body">

                    @if($event->participants->count())

                        <div class="list-group list-group-flush">

                            @foreach($event->participants as $participant)

                                <div class="list-group-item px-0">

                                    <div class="d-flex align-items-center">

                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-light text-primary rounded-circle">
                                                    <i class="ri-user-line"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1 ms-3">

                                            @if($participant->participant_type === 'student')

                                                <h6 class="mb-1">
                                                    {{ $participant->student?->first_name }}
                                                    {{ $participant->student?->middle_name }}
                                                    {{ $participant->student?->last_name }}
                                                </h6>

                                                <p class="text-muted mb-0 small">
                                                    Student
                                                    @if($participant->student?->student_number)
                                                        · {{ $participant->student->student_number }}
                                                    @endif
                                                </p>

                                            @elseif($participant->participant_type === 'staff')

                                                <h6 class="mb-1">
                                                    {{ $participant->staff?->first_name }}
                                                    {{ $participant->staff?->middle_name }}
                                                    {{ $participant->staff?->last_name }}
                                                </h6>

                                                <p class="text-muted mb-0 small">
                                                    Staff
                                                    @if($participant->staff?->staff_number)
                                                        · {{ $participant->staff->staff_number }}
                                                    @endif
                                                </p>

                                            @else

                                                <h6 class="mb-1">
                                                    Unknown Participant
                                                </h6>

                                            @endif

                                            @if($participant->attendance_status)
                                                <span class="badge bg-light text-dark mt-1">
                                                    {{ ucfirst($participant->attendance_status) }}
                                                </span>
                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-4">

                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-light text-primary rounded-circle fs-2">
                                    <i class="ri-group-line"></i>
                                </div>
                            </div>

                            <h6>No Participants</h6>

                            <p class="text-muted small mb-3">
                                No students or staff members have been added to this event.
                            </p>

                            @can('events.participants.manage')
                                <a href="{{ route('admin.events.participants', $event) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="ri-user-add-line align-middle me-1"></i>
                                    Add Participants
                                </a>
                            @endcan

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- Delete Event --}}
    @can('events.delete')
        <div class="row">
            <div class="col-12">

                <div class="card border-danger">

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>
                                <h6 class="text-danger mb-1">
                                    Delete Event
                                </h6>

                                <p class="text-muted mb-0">
                                    This will remove the event from the active event list.
                                </p>
                            </div>

                            <form action="{{ route('admin.events.destroy', $event) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this event?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger">
                                    <i class="ri-delete-bin-line align-middle me-1"></i>
                                    Delete Event
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    @endcan

</div>
@endsection