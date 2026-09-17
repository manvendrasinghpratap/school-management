@extends('backend.layout.default')

@section('content')
<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-sm-1">Edit Event</h4>

                    <p class="text-muted mb-0">
                        Update the school event information.
                    </p>
                </div>

                <div class="page-title-right">

                    <div class="d-flex gap-2">

                        <a href="{{ route('admin.events.index') }}"
                           class="btn btn-light">
                            <i class="ri-arrow-left-line align-middle me-1"></i>
                            Back
                        </a>

                        <a href="{{ route('admin.events.show', $event) }}"
                           class="btn btn-info">
                            <i class="ri-eye-line align-middle me-1"></i>
                            View Event
                        </a>

                    </div>

                </div>

            </div>

        </div>
    </div>

    {{-- Validation Errors --}}
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

    {{-- Event Form --}}
    <form action="{{ route('admin.events.update', $event) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="row">

            {{-- Main Information --}}
            <div class="col-lg-8">

                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Event Information
                        </h5>
                    </div>

                    <div class="card-body">

                        {{-- Title --}}
                        <div class="mb-3">

                            <label for="title" class="form-label">
                                Event Title
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $event->title) }}"
                                   placeholder="Enter event title"
                                   required>

                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Event Type --}}
                        <div class="mb-3">

                            <label for="event_type" class="form-label">
                                Event Type
                            </label>

                            <input type="text"
                                   class="form-control @error('event_type') is-invalid @enderror"
                                   id="event_type"
                                   name="event_type"
                                   value="{{ old('event_type', $event->event_type) }}"
                                   placeholder="e.g. Meeting, Sports, Academic, Ceremony">

                            @error('event_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Description --}}
                        <div class="mb-3">

                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="5"
                                      placeholder="Enter event description">{{ old('description', $event->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="row">

                            {{-- Start Date --}}
                            <div class="col-md-6 mb-3">

                                <label for="starts_at" class="form-label">
                                    Start Date & Time
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="datetime-local"
                                       class="form-control @error('starts_at') is-invalid @enderror"
                                       id="starts_at"
                                       name="starts_at"
                                       value="{{ old('starts_at', $event->start_datetime ? $event->start_datetime->format('Y-m-d\TH:i') : '') }}"
                                       required>

                                @error('starts_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- End Date --}}
                            <div class="col-md-6 mb-3">

                                <label for="ends_at" class="form-label">
                                    End Date & Time
                                </label>

                                <input type="datetime-local"
                                       class="form-control @error('ends_at') is-invalid @enderror"
                                       id="ends_at"
                                       name="ends_at"
                                       value="{{ old('ends_at', $event->end_datetime ? $event->end_datetime->format('Y-m-d\TH:i') : '') }}">

                                @error('ends_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        {{-- Venue --}}
                        <div class="mb-3">

                            <label for="venue" class="form-label">
                                Location / Venue
                            </label>

                            <input type="text"
                                   class="form-control @error('venue') is-invalid @enderror"
                                   id="venue"
                                   name="venue"
                                   value="{{ old('venue', $event->location) }}"
                                   placeholder="e.g. School Hall, Auditorium, Sports Ground">

                            @error('venue')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>

            {{-- Event Settings --}}
            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Event Settings
                        </h5>
                    </div>

                    <div class="card-body">

                        {{-- Status --}}
                        <div class="mb-3">

                            <label for="status" class="form-label">
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                    required>

                                <option value="draft"
                                    {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>

                                <option value="scheduled"
                                    {{ old('status', $event->status) === 'scheduled' ? 'selected' : '' }}>
                                    Scheduled
                                </option>

                                <option value="completed"
                                    {{ old('status', $event->status) === 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                                <option value="cancelled"
                                    {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>

                            </select>

                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Public Event --}}
                        <div class="mb-3">

                            <label class="form-label d-block">
                                Visibility
                            </label>

                            <div class="form-check form-switch">

                                <input type="hidden"
                                       name="is_public"
                                       value="0">

                                <input class="form-check-input"
                                       type="checkbox"
                                       role="switch"
                                       id="is_public"
                                       name="is_public"
                                       value="1"
                                       {{ old('is_public', $event->is_public) ? 'checked' : '' }}>

                                <label class="form-check-label"
                                       for="is_public">
                                    Public Event
                                </label>

                            </div>

                            <div class="text-muted small mt-2">
                                Public events can be visible to the intended school audience.
                            </div>

                        </div>

                    </div>

                </div>

                {{-- Existing Event Summary --}}
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Event Summary
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <div class="text-muted small">
                                Event ID
                            </div>

                            <div class="fw-medium">
                                #{{ $event->id }}
                            </div>

                        </div>

                        <div class="mb-3">

                            <div class="text-muted small">
                                Created By
                            </div>

                            <div class="fw-medium">
                                {{ $event->creator?->name ?? '-' }}
                            </div>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Created
                            </div>

                            <div class="fw-medium">
                                {{ $event->created_at?->format('d M Y, h:i A') ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Form Actions --}}
        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <a href="{{ route('admin.events.show', $event) }}"
                               class="btn btn-light">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="ri-save-line align-middle me-1"></i>
                                Update Event
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>
@endsection