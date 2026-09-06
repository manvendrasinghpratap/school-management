<div class="topnav">
    <div class="container-fluid">

        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">

                <ul class="navbar-nav">

                    {{-- ===================================================== --}}
                    {{-- Dashboard --}}
                    {{-- ===================================================== --}}
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.dashboard') }}">

                            <i class="bx bx-home-circle me-2"></i>
                            <span>Dashboard</span>

                        </a>
                    </li>


                    {{-- ===================================================== --}}
                    {{-- School --}}
                    {{-- ===================================================== --}}
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

                            <a href="{{ route('admin.school.setup') }}"
                               class="dropdown-item">

                                <i class="bx bx-building me-2"></i>
                                School Setup

                            </a>

                            @if(auth()->user()?->school_id)

                                <a href="{{ route('admin.school.settings.edit', auth()->user()->school_id) }}"
                                   class="dropdown-item">

                                    <i class="bx bx-cog me-2"></i>
                                    System Settings

                                </a>

                            @endif

                        </div>

                    </li>


                    {{-- ===================================================== --}}
                    {{-- Students --}}
                    {{-- ===================================================== --}}
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

                            <a href="{{ route('admin.students.index') }}"
                               class="dropdown-item">

                                <i class="bx bx-list-ul me-2"></i>
                                Student List

                            </a>

                            <a href="{{ route('admin.students.create') }}"
                               class="dropdown-item">

                                <i class="bx bx-user-plus me-2"></i>
                                Student Registration

                            </a>

                            <a href="{{ route('admin.guardians.index') }}"
                               class="dropdown-item">

                                <i class="bx bx-group me-2"></i>
                                Guardians

                            </a>

                        </div>

                    </li>


                    {{-- ===================================================== --}}
                    {{-- Academic --}}
                    {{-- ===================================================== --}}
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
                            <a href="{{ route('admin.academic-years.index') }}"
                               class="dropdown-item">

                                <i class="bx bx-calendar me-2"></i>
                                Academic Years

                            </a>

                            {{-- Terms --}}
                            <a href="{{ route('admin.terms.index') }}"
                               class="dropdown-item">

                                <i class="bx bx-calendar-event me-2"></i>
                                Terms / Semesters

                            </a>

                            <div class="dropdown-divider"></div>

                            {{-- Departments --}}
                            <a href="{{ route('admin.departments.index') }}"
                               class="dropdown-item">

                                <i class="bx bx-sitemap me-2"></i>
                                Departments

                            </a>

                            {{-- Classes --}}
                            <a href="{{ route('admin.classes.index') }}"
                               class="dropdown-item">

                                <i class="bx bx-building me-2"></i>
                                Classes

                            </a>

                            {{-- Sections --}}
                            <a href="{{ route('admin.sections.index') }}"
                               class="dropdown-item">

                                <i class="bx bx-grid-alt me-2"></i>
                                Sections / Streams

                            </a>

                            {{-- Subjects --}}
                            <a href="{{ route('admin.courses.index') }}"
                               class="dropdown-item">

                                <i class="bx bx-book me-2"></i>
                                Subjects / Courses

                            </a>

                        </div>

                    </li>


                    {{-- ===================================================== --}}
                    {{-- Staff --}}
                    {{-- ===================================================== --}}
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

                            {{-- All Staff --}}
                            <a href="{{ route('admin.staff.index') }}"
                               class="dropdown-item">

                                <i class="bx bx-group me-2"></i>
                                Staff List

                            </a>

                            {{-- Add Staff --}}
                            <a href="{{ route('admin.staff.create') }}"
                               class="dropdown-item">

                                <i class="bx bx-user-plus me-2"></i>
                                Add Staff

                            </a>

                            <div class="dropdown-divider"></div>

                            {{-- Instructors --}}
                            <a href="{{ route('admin.instructors.index') }}"
                               class="dropdown-item">

                                <i class="bx bx-chalkboard me-2"></i>
                                Instructors

                            </a>

                            {{-- Add Instructor --}}
                            <a href="{{ route('admin.instructors.create') }}"
                               class="dropdown-item">

                                <i class="bx bx-user-check me-2"></i>
                                Add Instructor

                            </a>

                        </div>

                    </li>


                    {{-- ===================================================== --}}
{{-- Administration --}}
{{-- ===================================================== --}}

@if(
    auth()->user()?->can('users.view') ||
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