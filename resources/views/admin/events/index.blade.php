@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">School Events</h4>

                @can('events.create')
                    <div class="page-title-right">
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                            <i class="ri-add-line align-middle me-1"></i>
                            Create Event
                        </a>
                    </div>
                @endcan
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

    {{-- Events Table --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">
                School Events
            </h5>

            <span class="text-muted small">
                {{ $events->total() }} {{ Str::plural('event', $events->total()) }}
            </span>
        </div>

        <div class="card-body">

            @if($events->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>Event</th>
                                <th>Type</th>
                                <th>Starts</th>
                                <th>Ends</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($events as $e)

                            <tr>

                                {{-- Event --}}
                                <td>
                                    <div>
                                        <strong>{{ $e->title }}</strong>
                                    </div>

                                    @if($e->description)
                                        <div class="small text-muted mt-1">
                                            {{ Str::limit($e->description, 70) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Type --}}
                                <td>
                                    {{ $e->event_type ?: '-' }}
                                </td>

                                {{-- Start Date --}}
                                <td>
                                    @if($e->start_datetime)
                                        <div>
                                            {{ $e->start_datetime->format('d M Y') }}
                                        </div>

                                        <div class="small text-muted">
                                            {{ $e->start_datetime->format('h:i A') }}
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- End Date --}}
                                <td>
                                    @if($e->end_datetime)
                                        <div>
                                            {{ $e->end_datetime->format('d M Y') }}
                                        </div>

                                        <div class="small text-muted">
                                            {{ $e->end_datetime->format('h:i A') }}
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Location --}}
                                <td>
                                    {{ $e->location ?: '-' }}
                                </td>

                                {{-- Status --}}
                                <td>
                                    @switch($e->status)

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
                                                {{ ucfirst($e->status ?? 'Unknown') }}
                                            </span>

                                    @endswitch
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">

                                    <div class="d-flex justify-content-end gap-1">

                                        {{-- View --}}
                                        @can('events.view')
                                            <a href="{{ route('admin.events.show', $e) }}"
                                               class="btn btn-sm btn-info"
                                               title="View Event">
                                                <i class="ri-eye-line"></i>
                                                <span class="d-none d-xl-inline">View</span>
                                            </a>
                                        @endcan

                                        {{-- Edit --}}
                                        @can('events.update')
                                            <a href="{{ route('admin.events.edit', $e) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit Event">
                                                <i class="ri-edit-line"></i>
                                                <span class="d-none d-xl-inline">Edit</span>
                                            </a>
                                        @endcan

                                        {{-- Delete --}}
                                        @can('events.delete')
                                            <form action="{{ route('admin.events.destroy', $e) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this event?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete Event">
                                                    <i class="ri-delete-bin-line"></i>
                                                    <span class="d-none d-xl-inline">Delete</span>
                                                </button>

                                            </form>
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                @if($events->hasPages())
                    <div class="mt-3">
                        {{ $events->links() }}
                    </div>
                @endif

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                            <i class="ri-calendar-event-line"></i>
                        </div>
                    </div>

                    <h5>No Events Found</h5>

                    <p class="text-muted mb-4">
                        There are currently no school events available.
                    </p>

                    @can('events.create')
                        <a href="{{ route('admin.events.create') }}"
                           class="btn btn-primary">
                            <i class="ri-add-line align-middle me-1"></i>
                            Create Your First Event
                        </a>
                    @endcan

                </div>

            @endif

        </div>
    </div>

</div>
@endsection