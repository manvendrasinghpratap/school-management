<div class="topnav">
    <div class="container-fluid">

        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">

                <ul class="navbar-nav">

                    {{-- ===================================================== --}}
                    {{-- Dashboard --}}
                    {{-- ===================================================== --}}
                    @if(auth()->user()?->can('dashboard.view'))
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('admin.dashboard') }}">

                                <i class="bx bx-home-circle me-2"></i>
                                <span>Dashboard</span>

                            </a>
                        </li>
                    @endif


                    {{-- ===================================================== --}}
                    {{-- School --}}
                    {{-- ===================================================== --}}
                    @if(
                        auth()->user()?->can('schools.view') ||
                        auth()->user()?->can('schools.update') ||
                        auth()->user()?->can('settings.view')
                    )

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-school"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-building-house me-2"></i>
                                <span>School</span>

                                <div class="arrow-down"></div>

                            </a>

                            <div class="dropdown-menu"
                                 aria-labelledby="topnav-school">

                                {{-- School Setup --}}
                                @if(
                                    auth()->user()?->can('schools.view') ||
                                    auth()->user()?->can('schools.update')
                                )

                                    <a href="{{ route('admin.school.setup') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-building me-2"></i>
                                        School Setup

                                    </a>

                                @endif


                                {{-- System Settings --}}
                                @if(
                                    auth()->user()?->school_id &&
                                    auth()->user()?->can('settings.view')
                                )

                                    <a href="{{ route('admin.school.settings.edit', auth()->user()->school_id) }}"
                                       class="dropdown-item">

                                        <i class="bx bx-cog me-2"></i>
                                        System Settings

                                    </a>

                                @endif

                            </div>

                        </li>

                    @endif


                    {{-- ===================================================== --}}
                    {{-- Students --}}
                    {{-- ===================================================== --}}
                    @if(
                        auth()->user()?->can('students.view') ||
                        auth()->user()?->can('students.create') ||
                        auth()->user()?->can('students.update') ||
                        auth()->user()?->can('students.delete') ||
                        auth()->user()?->can('students.documents.view') ||
                        auth()->user()?->can('students.documents.manage') ||
                        auth()->user()?->can('guardians.view')
                    )

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-students"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-user me-2"></i>
                                <span>Students</span>

                                <div class="arrow-down"></div>

                            </a>

                            <div class="dropdown-menu"
                                 aria-labelledby="topnav-students">

                                {{-- Student List --}}
                                @if(auth()->user()?->can('students.view'))

                                    <a href="{{ route('admin.students.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-list-ul me-2"></i>
                                        Student List

                                    </a>

                                @endif


                                {{-- Student Registration --}}
                                @if(auth()->user()?->can('students.create'))

                                    <a href="{{ route('admin.students.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-plus me-2"></i>
                                        Student Registration

                                    </a>

                                @endif


                                {{-- Student Documents --}}
                                @if(
                                    auth()->user()?->can('students.documents.view') ||
                                    auth()->user()?->can('students.documents.manage')
                                )

                                    @if(auth()->user()?->can('students.view'))

                                        <div class="dropdown-divider"></div>

                                        {{-- Documents are accessed from Student Profile --}}
                                        <span class="dropdown-item-text text-muted font-size-12">
                                            <i class="bx bx-file me-2"></i>
                                            Student Documents
                                        </span>

                                    @endif

                                @endif


                                {{-- Guardians --}}
                                @if(auth()->user()?->can('guardians.view'))

                                    <div class="dropdown-divider"></div>

                                    <a href="{{ route('admin.guardians.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-group me-2"></i>
                                        Guardians

                                    </a>

                                @endif

                            </div>

                        </li>

                    @endif


                    {{-- ===================================================== --}}
                    {{-- Academic --}}
                    {{-- ===================================================== --}}
                    @if(
                        auth()->user()?->can('academic-years.view') ||
                        auth()->user()?->can('terms.view') ||
                        auth()->user()?->can('departments.view') ||
                        auth()->user()?->can('classes.view') ||
                        auth()->user()?->can('sections.view') ||
                        auth()->user()?->can('courses.view')
                    )

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-academics"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-book-open me-2"></i>
                                <span>Academics</span>

                                <div class="arrow-down"></div>

                            </a>

                            <div class="dropdown-menu"
                                 aria-labelledby="topnav-academics">

                                {{-- Academic Years --}}
                                @if(auth()->user()?->can('academic-years.view'))

                                    <a href="{{ route('admin.academic-years.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar me-2"></i>
                                        Academic Years

                                    </a>

                                @endif


                                {{-- Terms --}}
                                @if(auth()->user()?->can('terms.view'))

                                    <a href="{{ route('admin.terms.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-event me-2"></i>
                                        Terms / Semesters

                                    </a>

                                @endif


                                {{-- Departments --}}
                                @if(auth()->user()?->can('departments.view'))

                                    <div class="dropdown-divider"></div>

                                    <a href="{{ route('admin.departments.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-sitemap me-2"></i>
                                        Departments

                                    </a>

                                @endif


                                {{-- Classes --}}
                                @if(auth()->user()?->can('classes.view'))

                                    <a href="{{ route('admin.classes.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-building me-2"></i>
                                        Classes

                                    </a>

                                @endif


                                {{-- Sections --}}
                                @if(auth()->user()?->can('sections.view'))

                                    <a href="{{ route('admin.sections.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-grid-alt me-2"></i>
                                        Sections / Streams

                                    </a>

                                @endif


                                {{-- Subjects --}}
                                @if(auth()->user()?->can('courses.view'))

                                    <a href="{{ route('admin.courses.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-book me-2"></i>
                                        Subjects / Courses

                                    </a>

                                @endif

                            </div>

                        </li>

                    @endif


                    {{-- ===================================================== --}}
                    {{-- Staff --}}
                    {{-- ===================================================== --}}
                    @if(
                        auth()->user()?->can('staff.view') ||
                        auth()->user()?->can('staff.create') ||
                        auth()->user()?->can('staff.update') ||
                        auth()->user()?->can('staff.delete') ||
                        auth()->user()?->can('instructors.view') ||
                        auth()->user()?->can('instructors.create') ||
                        auth()->user()?->can('instructors.update') ||
                        auth()->user()?->can('instructors.delete')
                    )

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-staff"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-group me-2"></i>
                                <span>Staff</span>

                                <div class="arrow-down"></div>

                            </a>

                            <div class="dropdown-menu"
                                 aria-labelledby="topnav-staff">

                                {{-- Staff List --}}
                                @if(auth()->user()?->can('staff.view'))

                                    <a href="{{ route('admin.staff.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-group me-2"></i>
                                        Staff List

                                    </a>

                                @endif


                                {{-- Add Staff --}}
                                @if(auth()->user()?->can('staff.create'))

                                    <a href="{{ route('admin.staff.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-plus me-2"></i>
                                        Add Staff

                                    </a>

                                @endif


                                {{-- Instructors --}}
                                @if(auth()->user()?->can('instructors.view'))

                                    <div class="dropdown-divider"></div>

                                    <a href="{{ route('admin.instructors.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-chalkboard me-2"></i>
                                        Instructors

                                    </a>

                                @endif


                                {{-- Add Instructor --}}
                                @if(auth()->user()?->can('instructors.create'))

                                    <a href="{{ route('admin.instructors.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-check me-2"></i>
                                        Add Instructor

                                    </a>

                                @endif

                            </div>

                        </li>

                    @endif


                    {{-- ===================================================== --}}
                    {{-- Administration --}}
                    {{-- ===================================================== --}}
                    @if(
                        auth()->user()?->can('users.view') ||
                        auth()->user()?->can('users.create') ||
                        auth()->user()?->can('roles.view') ||
                        auth()->user()?->can('permissions.view')
                    )

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-administration"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-cog me-2"></i>
                                <span>Administration</span>

                                <div class="arrow-down"></div>

                            </a>

                            <div class="dropdown-menu"
                                 aria-labelledby="topnav-administration">

                                {{-- Users --}}
                                @if(auth()->user()?->can('users.view'))

                                    <a href="{{ route('admin.users.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user me-2"></i>
                                        Users

                                    </a>

                                @endif


                                {{-- Roles --}}
                                @if(auth()->user()?->can('roles.view'))

                                    <a href="{{ route('admin.roles.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-shield me-2"></i>
                                        Roles

                                    </a>

                                @endif


                                {{-- Permissions --}}
                                @if(auth()->user()?->can('permissions.view'))

                                    <a href="{{ route('admin.permissions.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-lock-alt me-2"></i>
                                        Permissions

                                    </a>

                                @endif

                            </div>

                        </li>

                    @endif

                </ul>

            </div>

        </nav>

    </div>
</div>