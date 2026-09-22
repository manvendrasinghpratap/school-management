<div class="topnav">

    <div class="container-fluid">

        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">

                <ul class="navbar-nav">


                    {{-- =========================================================
                         DASHBOARD
                         ========================================================= --}}

                    @if(auth()->user()?->can('dashboard.view'))

                        <li class="nav-item">

                            <a class="nav-link"
                               href="{{ route('admin.dashboard') }}">

                                <i class="bx bx-home-circle me-2"></i>

                                <span>Dashboard</span>

                            </a>

                        </li>

                    @endif


                    {{-- =========================================================
                         SCHOOL
                         ========================================================= --}}

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

                                @if(
                                    auth()->user()?->can('schools.view') ||
                                    auth()->user()?->can('schools.update')
                                )

                                    <a href="{{ route('admin.school.setup') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-building"></i>

                                        <span>School Setup</span>

                                    </a>

                                @endif


                                @if(
                                    auth()->user()?->school_id &&
                                    auth()->user()?->can('settings.view')
                                )

                                    <a href="{{ route('admin.school.settings.edit', auth()->user()->school_id) }}"
                                       class="dropdown-item">

                                        <i class="bx bx-cog"></i>

                                        <span>System Settings</span>

                                    </a>

                                @endif

                            </div>

                        </li>

                    @endif


                    {{-- =========================================================
                         STUDENTS
                         ========================================================= --}}

                    @if(
                        auth()->user()?->can('students.view') ||
                        auth()->user()?->can('students.create') ||
                        auth()->user()?->can('students.update') ||
                        auth()->user()?->can('students.delete') ||
                        auth()->user()?->can('students.documents.view') ||
                        auth()->user()?->can('students.documents.manage') ||
                        auth()->user()?->can('guardians.view') ||
                        auth()->user()?->can('enrollments.view') ||
                        auth()->user()?->can('enrollments.create') ||
                        auth()->user()?->can('promotions.view') ||
                        auth()->user()?->can('promotions.create') ||
                        auth()->user()?->can('graduation.view') ||
                        auth()->user()?->can('graduation.manage') ||
                        auth()->user()?->can('alumni.view') ||
                        auth()->user()?->can('alumni.manage') ||
                        auth()->user()?->can('attendance.view') ||
                        auth()->user()?->can('attendance.mark') ||
                        auth()->user()?->can('attendance.update') ||
                        auth()->user()?->can('attendance.reports')
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


                            <div class="dropdown-menu sms-wide-menu sms-two-column"
                                 aria-labelledby="topnav-students">


                                {{-- Student List --}}

                                @if(auth()->user()?->can('students.view'))

                                    <a href="{{ route('admin.students.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-list-ul"></i>

                                        <span>Student List</span>

                                    </a>

                                @endif


                                {{-- Student Registration --}}

                                @if(auth()->user()?->can('students.create'))

                                    <a href="{{ route('admin.students.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-plus"></i>

                                        <span>Student Registration</span>

                                    </a>

                                @endif


                                {{-- Student Information Heading --}}

                                @if(
                                    auth()->user()?->can('students.documents.view') ||
                                    auth()->user()?->can('students.documents.manage') ||
                                    auth()->user()?->can('guardians.view')
                                )

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-file"></i>

                                        <span>Student Information</span>

                                    </div>

                                @endif


                                {{-- Guardians --}}

                                @if(auth()->user()?->can('guardians.view'))

                                    <a href="{{ route('admin.guardians.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-group"></i>

                                        <span>Guardians</span>

                                    </a>

                                @endif


                                {{-- Enrollments --}}

                                @if(auth()->user()?->can('enrollments.view'))

                                    <a href="{{ route('admin.student-enrollments.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-book-add"></i>

                                        <span>Enrollments</span>

                                    </a>

                                @endif


                                {{-- Add Enrollment --}}

                                @if(auth()->user()?->can('enrollments.create'))

                                    <a href="{{ route('admin.student-enrollments.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-check"></i>

                                        <span>Add Enrollment</span>

                                    </a>

                                @endif


                                {{-- Promotions --}}

                                @if(
                                    auth()->user()?->can('promotions.view') ||
                                    auth()->user()?->can('promotions.create')
                                )

                                    @if(auth()->user()?->can('promotions.view'))

                                        <a href="{{ route('admin.student-promotions.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-transfer"></i>

                                            <span>Promotions</span>

                                        </a>

                                    @elseif(auth()->user()?->can('promotions.create'))

                                        <a href="{{ route('admin.student-promotions.create') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-transfer"></i>

                                            <span>Promotions</span>

                                        </a>

                                    @endif

                                @endif


                                {{-- Graduation --}}

                                @if(
                                    auth()->user()?->can('graduation.view') ||
                                    auth()->user()?->can('graduation.manage')
                                )

                                    @if(auth()->user()?->can('graduation.view'))

                                        <a href="{{ route('admin.graduations.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-award"></i>

                                            <span>Graduation</span>

                                        </a>

                                    @elseif(auth()->user()?->can('graduation.manage'))

                                        <a href="{{ route('admin.graduations.create') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-award"></i>

                                            <span>Graduation</span>

                                        </a>

                                    @endif

                                @endif


                                {{-- Alumni --}}

                                @if(
                                    auth()->user()?->can('alumni.view') ||
                                    auth()->user()?->can('alumni.manage')
                                )

                                    @if(auth()->user()?->can('alumni.view'))

                                        <a href="{{ route('admin.alumni.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-group"></i>

                                            <span>Alumni</span>

                                        </a>

                                    @elseif(auth()->user()?->can('alumni.manage'))

                                        <a href="{{ route('admin.alumni.create') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-group"></i>

                                            <span>Alumni</span>

                                        </a>

                                    @endif

                                @endif


                                {{-- Attendance Heading --}}

                                @if(
                                    auth()->user()?->can('attendance.view') ||
                                    auth()->user()?->can('attendance.mark') ||
                                    auth()->user()?->can('attendance.reports')
                                )

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-calendar-check"></i>

                                        <span>Attendance</span>

                                    </div>

                                @endif


                                {{-- Student Attendance --}}

                                @if(auth()->user()?->can('attendance.view'))

                                    <a href="{{ route('admin.attendance.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-check"></i>

                                        <span>Student Attendance</span>

                                    </a>

                                @endif


                                {{-- Mark Attendance --}}

                                @if(auth()->user()?->can('attendance.mark'))

                                    <a href="{{ route('admin.attendance.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-check"></i>

                                        <span>Mark Attendance</span>

                                    </a>

                                @endif


                                {{-- Attendance Reports --}}

                                @if(auth()->user()?->can('attendance.reports'))

                                    <a href="{{ route('admin.attendance.reports') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-bar-chart-alt-2"></i>

                                        <span>Attendance Reports</span>

                                    </a>

                                @endif


                            </div>

                        </li>

                    @endif




                    {{-- =========================================================
                         LIBRARY
                         Completed: Books + Categories + Authors + Publishers
                         + Members + Book Issues (Issue / Return / Renew)
                         ========================================================= --}}

                    @if(
                        auth()->user()?->can('library.view') ||
                        auth()->user()?->can('library.create') ||
                        auth()->user()?->can('library.update') ||
                        auth()->user()?->can('library.delete') ||
                        auth()->user()?->can('library.members.view') ||
                        auth()->user()?->can('library.members.manage') ||
                        auth()->user()?->can('library.issues.view') ||
                        auth()->user()?->can('library.issues.manage')
                    )

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-library"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-library me-2"></i>

                                <span>Library</span>

                                <div class="arrow-down"></div>

                            </a>


                            <div class="dropdown-menu sms-wide-menu sms-two-column"
                                 aria-labelledby="topnav-library">

                                {{-- =================================================
                                     LIBRARY MANAGEMENT
                                     ================================================= --}}

                                <div class="sms-menu-heading">

                                    <i class="bx bx-library"></i>

                                    <span>Library Management</span>

                                </div>


                                {{-- Books --}}

                                @if(
                                    auth()->user()?->can('library.view') ||
                                    auth()->user()?->can('library.create') ||
                                    auth()->user()?->can('library.update') ||
                                    auth()->user()?->can('library.delete')
                                )

                                    <a href="{{ route('admin.library.books.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-book"></i>

                                        <span>Books</span>

                                    </a>

                                @endif


                                {{-- Categories --}}

                                @if(
                                    auth()->user()?->can('library.view') ||
                                    auth()->user()?->can('library.create') ||
                                    auth()->user()?->can('library.update') ||
                                    auth()->user()?->can('library.delete')
                                )

                                    <a href="{{ route('admin.library.categories.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-category"></i>

                                        <span>Book Categories</span>

                                    </a>

                                @endif


                                {{-- Authors --}}

                                @if(
                                    auth()->user()?->can('library.view') ||
                                    auth()->user()?->can('library.create') ||
                                    auth()->user()?->can('library.update') ||
                                    auth()->user()?->can('library.delete')
                                )

                                    <a href="{{ route('admin.library.authors.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user"></i>

                                        <span>Authors</span>

                                    </a>

                                @endif


                                {{-- Publishers --}}

                                @if(
                                    auth()->user()?->can('library.view') ||
                                    auth()->user()?->can('library.create') ||
                                    auth()->user()?->can('library.update') ||
                                    auth()->user()?->can('library.delete')
                                )

                                    <a href="{{ route('admin.library.publishers.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-buildings"></i>

                                        <span>Publishers</span>

                                    </a>

                                @endif
                                @if(
                                    auth()->user()?->can('library.reservations.view') ||
                                    auth()->user()?->can('library.reservations.manage')
                                )
                                    <a href="{{ route('admin.library.reservations.index') }}" class="dropdown-item">
                                        <i class="bx bx-bookmark"></i>
                                      <span>Reservations</span>
                                    </a>
                                @endif
                                {{-- =================================================
                                     MEMBERS
                                     ================================================= --}}

                                @if(
                                    auth()->user()?->can('library.members.view') ||
                                    auth()->user()?->can('library.members.manage')
                                )

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-group"></i>

                                        <span>Library Members</span>

                                    </div>

                                @endif


                                {{-- Member List --}}

                                @if(auth()->user()?->can('library.members.view'))

                                    <a href="{{ route('admin.library.members.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-group"></i>

                                        <span>Members</span>

                                    </a>

                                @endif


                                {{-- Add Member --}}

                                @if(auth()->user()?->can('library.members.manage'))

                                    <a href="{{ route('admin.library.members.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-plus"></i>

                                        <span>Add Member</span>

                                    </a>

                                @endif


                                {{-- =================================================
                                     CIRCULATION
                                     ================================================= --}}

                                @if(
                                    auth()->user()?->can('library.issues.view') ||
                                    auth()->user()?->can('library.issues.manage')
                                )

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-transfer"></i>

                                        <span>Circulation</span>

                                    </div>

                                @endif


                                {{-- Book Issues --}}

                                @if(auth()->user()?->can('library.issues.view'))

                                    <a href="{{ route('admin.library.issues.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-list-check"></i>

                                        <span>Book Issues</span>

                                    </a>

                                @endif


                                {{-- Issue Book --}}

                                @if(auth()->user()?->can('library.issues.manage'))

                                    <a href="{{ route('admin.library.issues.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-book-add"></i>

                                        <span>Issue Book</span>

                                    </a>

                                @endif


                            </div>

                        </li>

                    @endif


                    {{-- =========================================================
                         TRANSPORT
                         Completed: Drivers + Vehicles + Routes + Stops
                         + Student Assignments + Transport Fees
                         ========================================================= --}}

                    @if(
                        auth()->user()?->can('transport.view') ||
                        auth()->user()?->can('transport.manage') ||
                        auth()->user()?->can('transport.assign') ||
                        auth()->user()?->can('transport.fees.view') ||
                        auth()->user()?->can('transport.fees.manage')
                    )

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-transport"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-bus me-2"></i>
                                <span>Transport</span>
                                <div class="arrow-down"></div>

                            </a>

                            <div class="dropdown-menu sms-wide-menu sms-two-column"
                                 aria-labelledby="topnav-transport">

                                <div class="sms-menu-heading">
                                    <i class="bx bx-bus"></i>
                                    <span>Transport Management</span>
                                </div>

                                @if(auth()->user()?->can('transport.view') || auth()->user()?->can('transport.manage'))
                                    <a href="{{ route('admin.transport.drivers.index') }}" class="dropdown-item">
                                        <i class="bx bx-user"></i>
                                        <span>Drivers</span>
                                    </a>

                                    <a href="{{ route('admin.transport.vehicles.index') }}" class="dropdown-item">
                                        <i class="bx bx-car"></i>
                                        <span>Vehicles</span>
                                    </a>

                                    <a href="{{ route('admin.transport.routes.index') }}" class="dropdown-item">
                                        <i class="bx bx-map"></i>
                                        <span>Transport Routes</span>
                                    </a>
                                @endif

                                @if(auth()->user()?->can('transport.view') || auth()->user()?->can('transport.assign'))
                                    <a href="{{ route('admin.transport.assignments.index') }}" class="dropdown-item">
                                        <i class="bx bx-user-check"></i>
                                        <span>Student Assignments</span>
                                    </a>
                                @endif

                                @if(auth()->user()?->can('transport.fees.view') || auth()->user()?->can('transport.fees.manage'))
                                    <div class="sms-menu-heading">
                                        <i class="bx bx-money"></i>
                                        <span>Transport Fees</span>
                                    </div>

                                    <a href="{{ route('admin.transport.fees.index') }}" class="dropdown-item">
                                        <i class="bx bx-money"></i>
                                        <span>Transport Fees</span>
                                    </a>
                                @endif

                            </div>

                        </li>

                    @endif


                    {{-- =========================================================
                         HOSTEL
                         Completed: Hostels + Rooms + Beds
                         + Student Allocations + Hostel Fees
                         ========================================================= --}}

                    @if(
                        auth()->user()?->can('hostel.view') ||
                        auth()->user()?->can('hostel.manage') ||
                        auth()->user()?->can('hostel.rooms.manage') ||
                        auth()->user()?->can('hostel.allocations.view') ||
                        auth()->user()?->can('hostel.allocations.manage') ||
                        auth()->user()?->can('hostel.fees.view') ||
                        auth()->user()?->can('hostel.fees.manage')
                    )

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-hostel"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-home-alt me-2"></i>
                                <span>Hostel</span>
                                <div class="arrow-down"></div>

                            </a>

                            <div class="dropdown-menu sms-wide-menu sms-two-column"
                                 aria-labelledby="topnav-hostel">

                                @if(auth()->user()?->can('hostel.view') || auth()->user()?->can('hostel.manage'))
                                    <div class="sms-menu-heading">
                                        <i class="bx bx-home-alt"></i>
                                        <span>Hostel Management</span>
                                    </div>

                                    <a href="{{ route('admin.hostel.index') }}" class="dropdown-item">
                                        <i class="bx bx-building-house"></i>
                                        <span>Hostels</span>
                                    </a>
                                @endif

                                @if(auth()->user()?->can('hostel.rooms.manage'))
                                    <a href="{{ route('admin.hostel.index') }}" class="dropdown-item">
                                        <i class="bx bx-door-open"></i>
                                        <span>Rooms & Beds</span>
                                    </a>
                                @endif

                                @if(auth()->user()?->can('hostel.allocations.view') || auth()->user()?->can('hostel.allocations.manage'))
                                    <div class="sms-menu-heading">
                                        <i class="bx bx-user-check"></i>
                                        <span>Student Accommodation</span>
                                    </div>

                                    <a href="{{ route('admin.hostel.allocations.index') }}" class="dropdown-item">
                                        <i class="bx bx-home-heart"></i>
                                        <span>Hostel Allocations</span>
                                    </a>
                                @endif

                                @if(auth()->user()?->can('hostel.fees.view') || auth()->user()?->can('hostel.fees.manage'))
                                    <div class="sms-menu-heading">
                                        <i class="bx bx-money"></i>
                                        <span>Hostel Fees</span>
                                    </div>

                                    <a href="{{ route('admin.hostel.fees.index') }}" class="dropdown-item">
                                        <i class="bx bx-money"></i>
                                        <span>Hostel Fees</span>
                                    </a>
                                @endif

                            </div>

                        </li>

                    @endif


                    {{-- =========================================================
                         ACADEMICS
                         ========================================================= --}}

                    @if(
                        auth()->user()?->can('academic-years.view') ||
                        auth()->user()?->can('terms.view') ||
                        auth()->user()?->can('departments.view') ||
                        auth()->user()?->can('classes.view') ||
                        auth()->user()?->can('sections.view') ||
                        auth()->user()?->can('courses.view') ||
                        auth()->user()?->can('timetable.view') ||
                        auth()->user()?->can('timetable.manage') ||

                        auth()->user()?->can('examinations.view') ||
                        auth()->user()?->can('examinations.create') ||
                        auth()->user()?->can('examinations.update') ||
                        auth()->user()?->can('examinations.delete') ||

                        auth()->user()?->can('exam-schedules.view') ||
                        auth()->user()?->can('exam-schedules.manage') ||

                        auth()->user()?->can('marks.view') ||
                        auth()->user()?->can('marks.enter') ||
                        auth()->user()?->can('marks.update') ||
                        auth()->user()?->can('marks.approve') ||

                        auth()->user()?->can('grading.view') ||
                        auth()->user()?->can('grading.manage') ||

                        auth()->user()?->can('results.view') ||
                        auth()->user()?->can('results.calculate') ||
                        auth()->user()?->can('results.approve') ||
                        auth()->user()?->can('results.publish') ||

                        auth()->user()?->can('report-cards.view') ||
                        auth()->user()?->can('report-cards.generate') ||

                        auth()->user()?->can('transcripts.view') ||
                        auth()->user()?->can('transcripts.generate')
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


                            <div class="dropdown-menu sms-wide-menu sms-two-column"
                                 aria-labelledby="topnav-academics">


                                {{-- Academic Setup Heading --}}

                                <div class="sms-menu-heading">

                                    <i class="bx bx-book"></i>

                                    <span>Academic Setup</span>

                                </div>


                                {{-- Academic Years --}}

                                @if(auth()->user()?->can('academic-years.view'))

                                    <a href="{{ route('admin.academic-years.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar"></i>

                                        <span>Academic Years</span>

                                    </a>

                                @endif


                                {{-- Terms --}}

                                @if(auth()->user()?->can('terms.view'))

                                    <a href="{{ route('admin.terms.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-event"></i>

                                        <span>Terms / Semesters</span>

                                    </a>

                                @endif


                                {{-- Departments --}}

                                @if(auth()->user()?->can('departments.view'))

                                    <a href="{{ route('admin.departments.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-sitemap"></i>

                                        <span>Departments</span>

                                    </a>

                                @endif


                                {{-- Classes --}}

                                @if(auth()->user()?->can('classes.view'))

                                    <a href="{{ route('admin.classes.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-building"></i>

                                        <span>Classes</span>

                                    </a>

                                @endif


                                {{-- Sections --}}

                                @if(auth()->user()?->can('sections.view'))

                                    <a href="{{ route('admin.sections.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-grid-alt"></i>

                                        <span>Sections / Streams</span>

                                    </a>

                                @endif


                                {{-- Subjects --}}

                                @if(auth()->user()?->can('courses.view'))

                                    <a href="{{ route('admin.courses.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-book"></i>

                                        <span>Subjects / Courses</span>

                                    </a>

                                @endif


                                {{-- Timetable --}}

                                @if(
                                    auth()->user()?->can('timetable.view') ||
                                    auth()->user()?->can('timetable.manage')
                                )

                                    <a href="{{ route('admin.timetables.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-time-five"></i>
                                        <span>Timetable</span>

                                    </a>

                                @endif


                                {{-- Examination Heading --}}

                                @if(
                                    auth()->user()?->can('examinations.view') ||
                                    auth()->user()?->can('examinations.create') ||
                                    auth()->user()?->can('exam-schedules.view') ||
                                    auth()->user()?->can('exam-schedules.manage') ||
                                    auth()->user()?->can('marks.view') ||
                                    auth()->user()?->can('marks.enter') ||
                                    auth()->user()?->can('grading.view') ||
                                    auth()->user()?->can('results.view') ||
                                    auth()->user()?->can('report-cards.view') ||
                                    auth()->user()?->can('transcripts.view')
                                )

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-edit-alt"></i>

                                        <span>Examination</span>

                                    </div>

                                @endif


                                {{-- Examinations --}}

                                @if(
                                    auth()->user()?->can('examinations.view') ||
                                    auth()->user()?->can('examinations.create')
                                )

                                    <a href="{{ route('admin.examinations.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-edit-alt"></i>

                                        <span>Examinations</span>

                                    </a>

                                @endif


                                {{-- Exam Schedules --}}

                                @if(
                                    auth()->user()?->can('exam-schedules.view') ||
                                    auth()->user()?->can('exam-schedules.manage')
                                )

                                    <a href="{{ route('admin.exam-schedules.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-event"></i>

                                        <span>Exam Schedules</span>

                                    </a>

                                @endif


                                {{-- Marks --}}

                                @if(
                                    auth()->user()?->can('marks.view') ||
                                    auth()->user()?->can('marks.enter') ||
                                    auth()->user()?->can('marks.update') ||
                                    auth()->user()?->can('marks.approve')
                                )

                                    <a href="{{ route('admin.marks.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-pencil"></i>

                                        <span>Subject Marks</span>

                                    </a>

                                @endif


                                {{-- Grading --}}

                                @if(
                                    auth()->user()?->can('grading.view') ||
                                    auth()->user()?->can('grading.manage')
                                )

                                    <a href="{{ route('admin.grading.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-bar-chart-alt-2"></i>

                                        <span>Grading</span>

                                    </a>

                                @endif


                                {{-- Results --}}

                                @if(
                                    auth()->user()?->can('results.view') ||
                                    auth()->user()?->can('results.calculate') ||
                                    auth()->user()?->can('results.approve') ||
                                    auth()->user()?->can('results.publish')
                                )

                                    <a href="{{ route('admin.results.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-spreadsheet"></i>

                                        <span>Results</span>

                                    </a>

                                @endif


                                {{-- Report Cards --}}

                                @if(
                                    auth()->user()?->can('report-cards.view') ||
                                    auth()->user()?->can('report-cards.generate')
                                )

                                    <a href="{{ route('admin.report-cards.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-file"></i>

                                        <span>Report Cards</span>

                                    </a>

                                @endif


                                {{-- Transcripts --}}

                                @if(
                                    auth()->user()?->can('transcripts.view') ||
                                    auth()->user()?->can('transcripts.generate')
                                )

                                    <a href="{{ route('admin.transcripts.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-file-blank"></i>

                                        <span>Transcripts</span>

                                    </a>

                                @endif


                            </div>

                        </li>

                    @endif


                    {{-- =========================================================
                         STAFF
                         ========================================================= --}}

                    @if(
                        auth()->user()?->can('staff.view') ||
                        auth()->user()?->can('staff.create') ||
                        auth()->user()?->can('staff.update') ||
                        auth()->user()?->can('staff.delete') ||

                        auth()->user()?->can('instructors.view') ||
                        auth()->user()?->can('instructors.create') ||
                        auth()->user()?->can('instructors.update') ||
                        auth()->user()?->can('instructors.delete') ||

                        auth()->user()?->can('staff-attendance.view') ||
                        auth()->user()?->can('staff-attendance.mark') ||
                        auth()->user()?->can('staff-attendance.update') ||
                        auth()->user()?->can('staff-attendance.reports') ||

                        auth()->user()?->can('leaves.view') ||
                        auth()->user()?->can('leaves.create') ||
                        auth()->user()?->can('leaves.update') ||
                        auth()->user()?->can('leaves.delete') ||
                        auth()->user()?->can('leaves.approve') ||
                        auth()->user()?->can('leaves.reject') ||
                        auth()->user()?->can('leaves.reports')
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


                            <div class="dropdown-menu sms-wide-menu sms-two-column"
                                 aria-labelledby="topnav-staff">


                                {{-- Staff Management Heading --}}

                                <div class="sms-menu-heading">

                                    <i class="bx bx-group"></i>

                                    <span>Staff Management</span>

                                </div>


                                {{-- Staff List --}}

                                @if(auth()->user()?->can('staff.view'))

                                    <a href="{{ route('admin.staff.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-group"></i>

                                        <span>Staff List</span>

                                    </a>

                                @endif


                                {{-- Add Staff --}}

                                @if(auth()->user()?->can('staff.create'))

                                    <a href="{{ route('admin.staff.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-plus"></i>

                                        <span>Add Staff</span>

                                    </a>

                                @endif


                                {{-- Instructors --}}

                                @if(auth()->user()?->can('instructors.view'))

                                    <a href="{{ route('admin.instructors.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-chalkboard"></i>

                                        <span>Instructors</span>

                                    </a>

                                @endif


                                {{-- Add Instructor --}}

                                @if(auth()->user()?->can('instructors.create'))

                                    <a href="{{ route('admin.instructors.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-check"></i>

                                        <span>Add Instructor</span>

                                    </a>

                                @endif


                                {{-- Staff Attendance Heading --}}

                                @if(
                                    auth()->user()?->can('staff-attendance.view') ||
                                    auth()->user()?->can('staff-attendance.mark') ||
                                    auth()->user()?->can('staff-attendance.reports')
                                )

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-calendar-check"></i>

                                        <span>Staff Attendance</span>

                                    </div>

                                @endif


                                {{-- Staff Attendance --}}

                                @if(auth()->user()?->can('staff-attendance.view'))

                                    <a href="{{ route('admin.staff-attendance.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-check"></i>

                                        <span>Staff Attendance</span>

                                    </a>

                                @endif


                                {{-- Mark Staff Attendance --}}

                                @if(auth()->user()?->can('staff-attendance.mark'))

                                    <a href="{{ route('admin.staff-attendance.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-check"></i>

                                        <span>Mark Attendance</span>

                                    </a>

                                @endif


                                {{-- Staff Attendance Reports --}}

                                @if(auth()->user()?->can('staff-attendance.reports'))

                                    <a href="{{ route('admin.staff-attendance.reports') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-bar-chart-alt-2"></i>

                                        <span>Attendance Reports</span>

                                    </a>

                                @endif


                                {{-- Leave Management Heading --}}

                                @if(
                                    auth()->user()?->can('leaves.view') ||
                                    auth()->user()?->can('leaves.create') ||
                                    auth()->user()?->can('leaves.approve') ||
                                    auth()->user()?->can('leaves.reports')
                                )

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-calendar-minus"></i>

                                        <span>Leave Management</span>

                                    </div>

                                @endif


                                {{-- Leave Management --}}

                                @if(auth()->user()?->can('leaves.view'))

                                    <a href="{{ route('admin.leaves.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-minus"></i>

                                        <span>Leave Management</span>

                                    </a>

                                @endif


                                {{-- New Leave Request --}}

                                @if(auth()->user()?->can('leaves.create'))

                                    <a href="{{ route('admin.leaves.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-plus"></i>

                                        <span>New Leave Request</span>

                                    </a>

                                @endif


                                {{-- Leave Reports --}}

                                @if(auth()->user()?->can('leaves.reports'))

                                    <a href="{{ route('admin.leaves.reports') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-file-find"></i>

                                        <span>Leave Reports</span>

                                    </a>

                                @endif


                            </div>

                        </li>

                    @endif


                    {{-- =========================================================
                         FINANCE
                         ========================================================= --}}

                    @if(
                        auth()->user()?->can('fees.view') ||
                        auth()->user()?->can('fees.manage') ||

                        auth()->user()?->can('fee-structures.view') ||
                        auth()->user()?->can('fee-structures.manage') ||

                        auth()->user()?->can('invoices.view') ||
                        auth()->user()?->can('invoices.create') ||
                        auth()->user()?->can('invoices.update') ||
                        auth()->user()?->can('invoices.delete') ||

                        auth()->user()?->can('payments.view') ||
                        auth()->user()?->can('payments.create') ||

                        auth()->user()?->can('payment-refunds.view') ||
                        auth()->user()?->can('payment-refunds.approve') ||
                        auth()->user()?->can('payment-refunds.reject') ||
                        auth()->user()?->can('payment-refunds.process') ||
                        auth()->user()?->can('payment-refunds.cancel') ||

                        auth()->user()?->can('scholarships.view') ||
                        auth()->user()?->can('scholarships.manage')
                    )

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-finance"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-wallet me-2"></i>

                                <span>Finance</span>

                                <div class="arrow-down"></div>

                            </a>


                            <div class="dropdown-menu sms-wide-menu sms-two-column"
                                 aria-labelledby="topnav-finance">


                                {{-- =================================================
                                     FEE SETUP
                                     ================================================= --}}

                                <div class="sms-menu-heading">

                                    <i class="bx bx-wallet"></i>

                                    <span>Fee Setup</span>

                                </div>


                                {{-- Fee Categories --}}

                                @if(
                                    auth()->user()?->can('fees.view') ||
                                    auth()->user()?->can('fees.manage')
                                )

                                    <a href="{{ route('admin.fee-categories.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-category"></i>

                                        <span>Fee Categories</span>

                                    </a>

                                @endif


                                {{-- Fee Structures --}}

                                @if(
                                    auth()->user()?->can('fee-structures.view') ||
                                    auth()->user()?->can('fee-structures.manage')
                                )

                                    <a href="{{ route('admin.fee-structures.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-layer"></i>

                                        <span>Fee Structures</span>

                                    </a>

                                @endif


                                {{-- Scholarships --}}

                                @if(
                                    auth()->user()?->can('scholarships.view') ||
                                    auth()->user()?->can('scholarships.manage')
                                )

                                    <a href="{{ route('admin.scholarships.index') }}"
                                       class="dropdown-item scholarship-item">

                                        <i class="bx bx-award"></i>

                                        <span>Scholarships</span>

                                    </a>

                                @endif


                                {{-- Fee Installments --}}

                                @if(
                                    auth()->user()?->can('fees.view') ||
                                    auth()->user()?->can('fees.manage')
                                )

                                    <a href="{{ route('admin.fee-installments.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar"></i>

                                        <span>Fee Installments</span>

                                    </a>

                                @endif


                                {{-- Student Fee Assignment --}}

                                @if(
                                    auth()->user()?->can('fees.view') ||
                                    auth()->user()?->can('fees.manage')
                                )

                                    <a href="{{ route('admin.student-fees.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-check"></i>

                                        <span>Student Fee Assignment</span>

                                    </a>

                                @endif


                                {{-- =================================================
                                     BILLING & PAYMENTS
                                     ================================================= --}}

                                @if(
                                    auth()->user()?->can('invoices.view') ||
                                    auth()->user()?->can('invoices.create') ||
                                    auth()->user()?->can('payments.view') ||
                                    auth()->user()?->can('payment-refunds.view')
                                )

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-receipt"></i>

                                        <span>Billing & Payments</span>

                                    </div>

                                @endif


                                {{-- Invoices --}}

                                @if(auth()->user()?->can('invoices.view'))

                                    <a href="{{ route('admin.invoices.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-file"></i>

                                        <span>Invoices</span>

                                    </a>

                                @endif


                                {{-- Payments --}}

                                @if(auth()->user()?->can('payments.view'))

                                    <a href="{{ route('admin.payments.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-money"></i>

                                        <span>Payments</span>

                                    </a>

                                @endif


                                {{-- Payment Refunds --}}

                                @if(auth()->user()?->can('payment-refunds.view'))

                                    <a href="{{ route('admin.payment-refunds.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-revision"></i>

                                        <span>Payment Refunds</span>

                                    </a>

                                @endif


                            </div>

                        </li>

                    @endif


                    {{-- =========================================================
                         WAVE 1
                         COMMUNICATION + EVENTS + IDENTITY
                         ========================================================= --}}

                    @if(
                        auth()->user()?->can('announcements.view') ||
                        auth()->user()?->can('events.view') ||
                        auth()->user()?->can('certificates.view') ||
                        auth()->user()?->can('id-cards.view')
                    )

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-wave1"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-layer me-2"></i>

                                <span>Communication & Services</span>

                                <div class="arrow-down"></div>

                            </a>


                            <div class="dropdown-menu sms-wide-menu sms-two-column"
                                 aria-labelledby="topnav-wave1">


                                {{-- Communication --}}

                                @if(auth()->user()?->can('announcements.view'))

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-megaphone"></i>

                                        <span>Communication</span>

                                    </div>


                                    <a href="{{ route('admin.announcements.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-megaphone"></i>

                                        <span>Announcements</span>

                                    </a>

                                @endif


                                {{-- Events --}}

                                @if(auth()->user()?->can('events.view'))

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-calendar-event"></i>

                                        <span>Events</span>

                                    </div>


                                    <a href="{{ route('admin.events.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-event"></i>

                                        <span>School Events</span>

                                    </a>

                                @endif


                                {{-- Certificates --}}

                                @if(auth()->user()?->can('certificates.view'))

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-award"></i>

                                        <span>Certificates</span>

                                    </div>


                                    <a href="{{ route('admin.certificates.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-award"></i>

                                        <span>Certificates</span>

                                    </a>


                                    @can('certificate-templates.manage')

                                        <a href="{{ route('admin.certificates.templates') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-layout"></i>

                                            <span>Certificate Templates</span>

                                        </a>

                                    @endcan

                                @endif


                                {{-- ID Cards --}}

                                @if(auth()->user()?->can('id-cards.view'))

                                    <div class="sms-menu-heading">

                                        <i class="bx bx-id-card"></i>

                                        <span>ID Cards</span>

                                    </div>


                                    <a href="{{ route('admin.id-cards.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-id-card"></i>

                                        <span>ID Cards</span>

                                    </a>


                                    @can('id-cards.generate')

                                        <a href="{{ route('admin.id-cards.templates') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-layout"></i>

                                            <span>ID Card Templates</span>

                                        </a>

                                    @endcan

                                @endif


                            </div>

                        </li>

                    @endif




                    {{-- =========================================================
                         REPORTS & ANALYTICS
                         Completed central reporting module
                         ========================================================= --}}

                    @if(auth()->user()?->can('reports.view'))

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none"
                               href="#"
                               id="topnav-reports"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">

                                <i class="bx bx-bar-chart-alt-2 me-2"></i>

                                <span>Reports & Analytics</span>

                                <div class="arrow-down"></div>

                            </a>

                            <div class="dropdown-menu sms-wide-menu sms-two-column"
                                 aria-labelledby="topnav-reports">

                                <div class="sms-menu-heading">
                                    <i class="bx bx-bar-chart-alt-2"></i>
                                    <span>Reports</span>
                                </div>

                                <a href="{{ route('admin.reports.index') }}"
                                   class="dropdown-item">
                                    <i class="bx bx-dashboard"></i>
                                    <span>Reports Dashboard</span>
                                </a>

                                <a href="{{ route('admin.reports.students') }}"
                                   class="dropdown-item">
                                    <i class="bx bx-user"></i>
                                    <span>Student Reports</span>
                                </a>

                                <a href="{{ route('admin.reports.enrollment') }}"
                                   class="dropdown-item">
                                    <i class="bx bx-user-plus"></i>
                                    <span>Enrollment Reports</span>
                                </a>

                                <a href="{{ route('admin.reports.academic-performance') }}"
                                   class="dropdown-item">
                                    <i class="bx bx-line-chart"></i>
                                    <span>Academic Performance</span>
                                </a>

                                <a href="{{ route('admin.reports.attendance') }}"
                                   class="dropdown-item">
                                    <i class="bx bx-calendar-check"></i>
                                    <span>Attendance Reports</span>
                                </a>

                                <a href="{{ route('admin.reports.staff') }}"
                                   class="dropdown-item">
                                    <i class="bx bx-group"></i>
                                    <span>Staff Reports</span>
                                </a>

                            </div>

                        </li>

                    @endif

                    {{-- =========================================================
                         ADMINISTRATION
                         ========================================================= --}}

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

                                        <i class="bx bx-user"></i>

                                        <span>Users</span>

                                    </a>

                                @endif


                                {{-- Roles --}}

                                @if(auth()->user()?->can('roles.view'))

                                    <a href="{{ route('admin.roles.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-shield"></i>

                                        <span>Roles</span>

                                    </a>

                                @endif


                                {{-- Permissions --}}

                                @if(auth()->user()?->can('permissions.view'))

                                    <a href="{{ route('admin.permissions.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-lock-alt"></i>

                                        <span>Permissions</span>

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