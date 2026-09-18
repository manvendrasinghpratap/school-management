{{-- Add this section to resources/views/backend/layout/navbar.blade.php.
     Keep the existing topnav dropdown CSS unchanged. --}}
@canany(['library.view','library.members.view','library.issues.view'])
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Library</a>
    <div class="dropdown-menu">
        @can('library.view') <a class="dropdown-item" href="{{ route('admin.library.books.index') }}">Books</a> @endcan
        @can('library.members.view') <a class="dropdown-item" href="{{ route('admin.library.members.index') }}">Library Members</a> @endcan
        @can('library.issues.view') <a class="dropdown-item" href="{{ route('admin.library.issues.index') }}">Issue / Return</a> @endcan
    </div>
</li>
@endcanany

@canany(['transport.view','transport.assign'])
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Transport</a>
    <div class="dropdown-menu">
        @can('transport.view') <a class="dropdown-item" href="{{ route('admin.transport.routes.index') }}">Routes</a> @endcan
        @can('transport.assign') <a class="dropdown-item" href="{{ route('admin.transport.assignments.index') }}">Student Assignments</a> @endcan
    </div>
</li>
@endcanany

@canany(['hostel.view','hostel.allocations.view'])
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Hostel</a>
    <div class="dropdown-menu">
        @can('hostel.view') <a class="dropdown-item" href="{{ route('admin.hostel.index') }}">Hostels</a> @endcan
        @can('hostel.allocations.view') <a class="dropdown-item" href="{{ route('admin.hostel.allocations.index') }}">Allocations</a> @endcan
    </div>
</li>
@endcanany
