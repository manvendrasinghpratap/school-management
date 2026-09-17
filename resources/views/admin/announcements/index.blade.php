@extends('backend.layout.default')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Announcements</h4>

                <div class="page-title-right">
                    @can('announcements.create')
                        <a href="{{ route('admin.announcements.create') }}"
                           class="btn btn-primary">
                            <i class="ri-add-line align-middle me-1"></i>
                            Create Announcement
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-1"></i>
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
            <strong>Please correct the following:</strong>

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

    {{-- Announcements Card --}}
    <div class="card">

        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">
                Announcement List
            </h4>

            @can('announcements.create')
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.announcements.create') }}"
                       class="btn btn-sm btn-primary">
                        <i class="ri-add-line align-middle me-1"></i>
                        New Announcement
                    </a>
                </div>
            @endcan
        </div>

        <div class="card-body">

            @if($announcements->count())

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%;">Title</th>
                                <th>Audience</th>
                                <th>Status</th>
                                <th>Publish</th>
                                <th>Pinned</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($announcements as $announcement)

                            <tr>

                                {{-- Title --}}
                                <td>
                                    <div class="d-flex align-items-center">

                                        @if($announcement->is_pinned)
                                            <span class="badge bg-warning-subtle text-warning me-2"
                                                  title="Pinned">
                                                <i class="ri-pushpin-fill"></i>
                                            </span>
                                        @endif

                                        <div>
                                            <h6 class="mb-1">
                                                {{ $announcement->title }}
                                            </h6>

                                            <div class="text-muted small">
                                                {{ \Illuminate\Support\Str::limit(
                                                    strip_tags($announcement->body),
                                                    100
                                                ) }}
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                {{-- Audience --}}
                                <td>
                                    @php
                                        $audienceLabels = [
                                            'all'      => 'Everyone',
                                            'students' => 'Students',
                                            'parents'  => 'Parents',
                                            'staff'    => 'Staff',
                                            'class'    => 'Class',
                                            'section'  => 'Section',
                                        ];

                                        $audience = $audienceLabels[
                                            $announcement->target_type
                                        ] ?? ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $announcement->target_type ?? ''
                                            )
                                        );
                                    @endphp

                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $audience }}
                                    </span>

                                    @if($announcement->target_type === 'class' && $announcement->classModel)
                                        <div class="small text-muted mt-1">
                                            {{ $announcement->classModel->name }}
                                        </div>
                                    @endif

                                    @if($announcement->target_type === 'section' && $announcement->section)
                                        <div class="small text-muted mt-1">
                                            {{ $announcement->section->name }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($announcement->status === 'published')
                                        <span class="badge bg-success-subtle text-success">
                                            Published
                                        </span>
                                    @elseif($announcement->status === 'draft')
                                        <span class="badge bg-warning-subtle text-warning">
                                            Draft
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            Archived
                                        </span>
                                    @endif
                                </td>

                                {{-- Published At --}}
                                <td>
                                    @if($announcement->published_at)
                                        <div>
                                            {{ $announcement->published_at->format('d M Y') }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $announcement->published_at->format('h:i A') }}
                                        </div>
                                    @else
                                        <span class="text-muted">Not scheduled</span>
                                    @endif
                                </td>

                                {{-- Pinned --}}
                                <td>
                                    @if($announcement->is_pinned)
                                        <span class="badge bg-warning">
                                            <i class="ri-pushpin-fill me-1"></i>
                                            Yes
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            No
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">

                                    <div class="btn-group" role="group">

                                        {{-- View --}}
                                        @can('announcements.view')
                                            <a href="{{ route(
                                                'admin.announcements.show',
                                                $announcement
                                            ) }}"
                                               class="btn btn-sm btn-info"
                                               title="View Announcement">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        @endcan

                                        {{-- Edit --}}
                                        @can('announcements.update')
                                            <a href="{{ route(
                                                'admin.announcements.edit',
                                                $announcement
                                            ) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit Announcement">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                        @endcan

                                        {{-- Delete --}}
                                        @can('announcements.delete')
                                            <form action="{{ route(
                                                'admin.announcements.destroy',
                                                $announcement
                                            ) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm(
                                                      'Are you sure you want to delete this announcement?'
                                                  );">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete Announcement">
                                                    <i class="ri-delete-bin-line"></i>
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
                @if($announcements->hasPages())
                    <div class="mt-3">
                        {{ $announcements->links() }}
                    </div>
                @endif

            @else

                <div class="text-center py-5">

                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                            <i class="ri-notification-3-line"></i>
                        </div>
                    </div>

                    <h5>No announcements found</h5>

                    <p class="text-muted mb-4">
                        There are no announcements available for your school.
                    </p>

                    @can('announcements.create')
                        <a href="{{ route('admin.announcements.create') }}"
                           class="btn btn-primary">
                            <i class="ri-add-line align-middle me-1"></i>
                            Create Your First Announcement
                        </a>
                    @endcan

                </div>

            @endif

        </div>
    </div>

</div>

@endsection