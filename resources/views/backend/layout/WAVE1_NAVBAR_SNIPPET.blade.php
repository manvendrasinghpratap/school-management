{{-- WAVE 1 NAVIGATION SNIPPET --}}
@if(auth()->user()?->can('announcements.view') || auth()->user()?->can('events.view') || auth()->user()?->can('id-cards.view') || auth()->user()?->can('certificates.view'))
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown">
        <i class="bx bx-broadcast me-2"></i><span>Communication & Documents</span><div class="arrow-down"></div>
    </a>
    <div class="dropdown-menu">
        @can('announcements.view')
            <a href="{{ route('admin.announcements.index') }}" class="dropdown-item"><i class="bx bx-news me-2"></i>Announcements</a>
        @endcan
        @can('events.view')
            <a href="{{ route('admin.events.index') }}" class="dropdown-item"><i class="bx bx-calendar-event me-2"></i>Events</a>
        @endcan
        @can('id-cards.view')
            <a href="{{ route('admin.id-cards.index') }}" class="dropdown-item"><i class="bx bx-id-card me-2"></i>ID Cards</a>
        @endcan
        @can('certificates.view')
            <a href="{{ route('admin.certificates.index') }}" class="dropdown-item"><i class="bx bx-award me-2"></i>Certificates</a>
        @endcan
    </div>
</li>
@endif
