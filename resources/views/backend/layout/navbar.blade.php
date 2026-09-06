<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
        <div class="collapse navbar-collapse" id="topnav-menu-content">
            <ul class="navbar-nav">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ url('/admin') }}">
                        <i class="bx bx-home-circle me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>


                {{-- School --}}
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
                            School Setup
                        </a>

                        @if(auth()->user()?->school_id)
                            <a href="{{ route('admin.school.settings.edit', auth()->user()->school_id) }}"
                               class="dropdown-item">
                                System Settings
                            </a>
                        @endif

                    </div>
                </li>


                {{-- Students --}}
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
                            Student List
                        </a>

                        <a href="{{ route('admin.students.create') }}"
                           class="dropdown-item">
                            Student Registration
                        </a>

                        <a href="{{ route('admin.guardians.index') }}"
                           class="dropdown-item">
                            Guardians
                        </a>

                    </div>
                </li>


                {{-- Academics --}}
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

                        <a href="{{ route('admin.classes.index') }}"
                           class="dropdown-item">
                            Classes
                        </a>

                        <a href="{{ route('admin.sections.index') }}"
                           class="dropdown-item">
                            Sections / Streams
                        </a>

                        <a href="{{ route('admin.departments.index') }}"
                           class="dropdown-item">
                            Departments
                        </a>

                        <a href="{{ route('admin.courses.index') }}"
                           class="dropdown-item">
                            Subjects / Courses
                        </a>

                        {{-- Module 15 --}}
                        <a href="{{ route('admin.staff.index') }}"
                           class="dropdown-item">
                            Teachers / Instructors
                        </a>

                    </div>
                </li>


                {{-- Administration --}}
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

                        <a href="{{ route('admin.users.index') }}"
                           class="dropdown-item">
                            Users
                        </a>

                        <a href="{{ route('admin.roles.index') }}"
                           class="dropdown-item">
                            Roles
                        </a>

                        <a href="{{ route('admin.permissions.index') }}"
                           class="dropdown-item">
                            Permissions
                        </a>

                    </div>
                </li>

            </ul>
        </div>

    </nav>
</div>


</div>
