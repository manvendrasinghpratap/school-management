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


                                {{-- ================================================= --}}
                                {{-- School Setup --}}
                                {{-- ================================================= --}}
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


                                {{-- ================================================= --}}
                                {{-- System Settings --}}
                                {{-- ================================================= --}}
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


                            <div class="dropdown-menu"
                                 aria-labelledby="topnav-students">


                                {{-- ================================================= --}}
                                {{-- Student List --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('students.view'))

                                    <a href="{{ route('admin.students.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-list-ul me-2"></i>

                                        Student List

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Student Registration --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('students.create'))

                                    <a href="{{ route('admin.students.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-plus me-2"></i>

                                        Student Registration

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Student Documents --}}
                                {{-- ================================================= --}}
                                @if(
                                    auth()->user()?->can('students.documents.view') ||
                                    auth()->user()?->can('students.documents.manage')
                                )

                                    @if(auth()->user()?->can('students.view'))

                                        <div class="dropdown-divider"></div>

                                        <span class="dropdown-item-text text-muted font-size-12">

                                            <i class="bx bx-file me-2"></i>

                                            Student Documents

                                        </span>

                                    @endif

                                @endif


                                {{-- ================================================= --}}
                                {{-- Guardians --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('guardians.view'))

                                    <div class="dropdown-divider"></div>

                                    <a href="{{ route('admin.guardians.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-group me-2"></i>

                                        Guardians

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Student Enrollments --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('enrollments.view'))

                                    <div class="dropdown-divider"></div>

                                    <a href="{{ route('admin.student-enrollments.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-book-add me-2"></i>

                                        Enrollments

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Add Student Enrollment --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('enrollments.create'))

                                    <a href="{{ route('admin.student-enrollments.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-check me-2"></i>

                                        Add Enrollment

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Student Promotions --}}
                                {{-- ================================================= --}}
                                @if(
                                    auth()->user()?->can('promotions.view') ||
                                    auth()->user()?->can('promotions.create')
                                )

                                    <div class="dropdown-divider"></div>

                                    @if(auth()->user()?->can('promotions.view'))

                                        <a href="{{ route('admin.student-promotions.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-transfer me-2"></i>

                                            Promotions

                                        </a>

                                    @elseif(auth()->user()?->can('promotions.create'))

                                        <a href="{{ route('admin.student-promotions.create') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-transfer me-2"></i>

                                            Promotions

                                        </a>

                                    @endif

                                @endif


                                {{-- ================================================= --}}
                                {{-- Graduation --}}
                                {{-- ================================================= --}}
                                @if(
                                    auth()->user()?->can('graduation.view') ||
                                    auth()->user()?->can('graduation.manage')
                                )

                                    <div class="dropdown-divider"></div>

                                    @if(auth()->user()?->can('graduation.view'))

                                        <a href="{{ route('admin.graduations.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-award me-2"></i>

                                            Graduation

                                        </a>

                                    @elseif(auth()->user()?->can('graduation.manage'))

                                        <a href="{{ route('admin.graduations.create') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-award me-2"></i>

                                            Graduation

                                        </a>

                                    @endif

                                @endif


                                {{-- ================================================= --}}
                                {{-- Alumni --}}
                                {{-- ================================================= --}}
                                @if(
                                    auth()->user()?->can('alumni.view') ||
                                    auth()->user()?->can('alumni.manage')
                                )

                                    <div class="dropdown-divider"></div>

                                    @if(auth()->user()?->can('alumni.view'))

                                        <a href="{{ route('admin.alumni.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-group me-2"></i>

                                            Alumni

                                        </a>

                                    @elseif(auth()->user()?->can('alumni.manage'))

                                        <a href="{{ route('admin.alumni.create') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-group me-2"></i>

                                            Alumni

                                        </a>

                                    @endif

                                @endif


                                {{-- ================================================= --}}
                                {{-- Student Attendance --}}
                                {{-- ================================================= --}}
                                @if(
                                    auth()->user()?->can('attendance.view') ||
                                    auth()->user()?->can('attendance.mark') ||
                                    auth()->user()?->can('attendance.reports')
                                )

                                    <div class="dropdown-divider"></div>

                                @endif


                                {{-- Student Attendance List --}}
                                @if(auth()->user()?->can('attendance.view'))

                                    <a href="{{ route('admin.attendance.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-check me-2"></i>

                                        Student Attendance

                                    </a>

                                @endif


                                {{-- Mark Student Attendance --}}
                                @if(auth()->user()?->can('attendance.mark'))

                                    <a href="{{ route('admin.attendance.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-check me-2"></i>

                                        Mark Student Attendance

                                    </a>

                                @endif


                                {{-- Student Attendance Reports --}}
                                @if(auth()->user()?->can('attendance.reports'))

                                    <a href="{{ route('admin.attendance.reports') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-bar-chart-alt-2 me-2"></i>

                                        Attendance Reports

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
                        auth()->user()?->can('courses.view') ||

                        {{-- Examination --}}
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


                            <div class="dropdown-menu"
                                 aria-labelledby="topnav-academics">


                                {{-- ================================================= --}}
                                {{-- Academic Years --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('academic-years.view'))

                                    <a href="{{ route('admin.academic-years.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar me-2"></i>

                                        Academic Years

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Terms --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('terms.view'))

                                    <a href="{{ route('admin.terms.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-event me-2"></i>

                                        Terms / Semesters

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Departments --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('departments.view'))

                                    <div class="dropdown-divider"></div>

                                    <a href="{{ route('admin.departments.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-sitemap me-2"></i>

                                        Departments

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Classes --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('classes.view'))

                                    <a href="{{ route('admin.classes.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-building me-2"></i>

                                        Classes

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Sections --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('sections.view'))

                                    <a href="{{ route('admin.sections.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-grid-alt me-2"></i>

                                        Sections / Streams

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Subjects --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('courses.view'))

                                    <a href="{{ route('admin.courses.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-book me-2"></i>

                                        Subjects / Courses

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Examination Management --}}
                                {{-- ================================================= --}}
                                @if(
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

                                    <div class="dropdown-divider"></div>


                                    {{-- ================================================= --}}
                                    {{-- Examinations --}}
                                    {{-- ================================================= --}}
                                    @if(
                                        auth()->user()?->can('examinations.view') ||
                                        auth()->user()?->can('examinations.create')
                                    )

                                        <a href="{{ route('admin.examinations.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-edit-alt me-2"></i>

                                            Examinations

                                        </a>

                                    @endif


                                    {{-- ================================================= --}}
                                    {{-- Exam Schedules --}}
                                    {{-- ================================================= --}}
                                    @if(
                                        auth()->user()?->can('exam-schedules.view') ||
                                        auth()->user()?->can('exam-schedules.manage')
                                    )

                                        <a href="{{ route('admin.exam-schedules.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-calendar-event me-2"></i>

                                            Exam Schedules

                                        </a>

                                    @endif


                                    {{-- ================================================= --}}
                                    {{-- Subject Marks --}}
                                    {{-- ================================================= --}}
                                    @if(
                                        auth()->user()?->can('marks.view') ||
                                        auth()->user()?->can('marks.enter') ||
                                        auth()->user()?->can('marks.update') ||
                                        auth()->user()?->can('marks.approve')
                                    )

                                        <a href="{{ route('admin.marks.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-pencil me-2"></i>

                                            Subject Marks

                                        </a>

                                    @endif


                                    {{-- ================================================= --}}
                                    {{-- Grading --}}
                                    {{-- ================================================= --}}
                                    @if(
                                        auth()->user()?->can('grading.view') ||
                                        auth()->user()?->can('grading.manage')
                                    )

                                        <a href="{{ route('admin.grading.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-bar-chart-alt-2 me-2"></i>

                                            Grading

                                        </a>

                                    @endif


                                    {{-- ================================================= --}}
                                    {{-- Results --}}
                                    {{-- ================================================= --}}
                                    @if(
                                        auth()->user()?->can('results.view') ||
                                        auth()->user()?->can('results.calculate') ||
                                        auth()->user()?->can('results.approve') ||
                                        auth()->user()?->can('results.publish')
                                    )

                                        <a href="{{ route('admin.results.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-spreadsheet me-2"></i>

                                            Results

                                        </a>

                                    @endif


                                    {{-- ================================================= --}}
                                    {{-- Report Cards --}}
                                    {{-- ================================================= --}}
                                    @if(
                                        auth()->user()?->can('report-cards.view') ||
                                        auth()->user()?->can('report-cards.generate')
                                    )

                                        <a href="{{ route('admin.report-cards.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-file me-2"></i>

                                            Report Cards

                                        </a>

                                    @endif


                                    {{-- ================================================= --}}
                                    {{-- Transcripts --}}
                                    {{-- ================================================= --}}
                                    @if(
                                        auth()->user()?->can('transcripts.view') ||
                                        auth()->user()?->can('transcripts.generate')
                                    )

                                        <a href="{{ route('admin.transcripts.index') }}"
                                           class="dropdown-item">

                                            <i class="bx bx-file-blank me-2"></i>

                                            Transcripts

                                        </a>

                                    @endif

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


                            <div class="dropdown-menu"
                                 aria-labelledby="topnav-staff">


                                {{-- ================================================= --}}
                                {{-- Staff List --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('staff.view'))

                                    <a href="{{ route('admin.staff.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-group me-2"></i>

                                        Staff List

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Add Staff --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('staff.create'))

                                    <a href="{{ route('admin.staff.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-plus me-2"></i>

                                        Add Staff

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Instructors --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('instructors.view'))

                                    <div class="dropdown-divider"></div>

                                    <a href="{{ route('admin.instructors.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-chalkboard me-2"></i>

                                        Instructors

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Add Instructor --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('instructors.create'))

                                    <a href="{{ route('admin.instructors.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-check me-2"></i>

                                        Add Instructor

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Staff Attendance --}}
                                {{-- ================================================= --}}
                                @if(
                                    auth()->user()?->can('staff-attendance.view') ||
                                    auth()->user()?->can('staff-attendance.mark')
                                )

                                    <div class="dropdown-divider"></div>

                                @endif


                                {{-- Staff Attendance List --}}
                                @if(auth()->user()?->can('staff-attendance.view'))

                                    <a href="{{ route('admin.staff-attendance.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-check me-2"></i>

                                        Staff Attendance

                                    </a>

                                @endif


                                {{-- Mark Staff Attendance --}}
                                @if(auth()->user()?->can('staff-attendance.mark'))

                                    <a href="{{ route('admin.staff-attendance.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user-check me-2"></i>

                                        Mark Staff Attendance

                                    </a>

                                @endif

                                {{-- ================================================= --}}
                                {{-- Staff Attendance Reports --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('staff-attendance.reports'))

                                    <a href="{{ route('admin.staff-attendance.reports') }}"
                                    class="dropdown-item">

                                        <i class="bx bx-bar-chart-alt-2 me-2"></i>

                                        Staff Attendance Reports
                                        
                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Leave Management --}}
                                {{-- ================================================= --}}
                                @if(
                                    auth()->user()?->can('leaves.view') ||
                                    auth()->user()?->can('leaves.create') ||
                                    auth()->user()?->can('leaves.update') ||
                                    auth()->user()?->can('leaves.delete') ||
                                    auth()->user()?->can('leaves.approve') ||
                                    auth()->user()?->can('leaves.reject') ||
                                    auth()->user()?->can('leaves.reports')
                                )

                                    <div class="dropdown-divider"></div>

                                @endif


                                {{-- Leave Management --}}
                                @if(auth()->user()?->can('leaves.view'))

                                    <a href="{{ route('admin.leaves.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-minus me-2"></i>

                                        Leave Management

                                    </a>

                                @endif


                                {{-- New Leave Request --}}
                                @if(auth()->user()?->can('leaves.create'))

                                    <a href="{{ route('admin.leaves.create') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-calendar-plus me-2"></i>

                                        New Leave Request

                                    </a>

                                @endif


                                {{-- Leave Reports --}}
                                @if(auth()->user()?->can('leaves.reports'))

                                    <a href="{{ route('admin.leaves.reports') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-file-find me-2"></i>

                                        Leave Reports

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


                                {{-- ================================================= --}}
                                {{-- Users --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('users.view'))

                                    <a href="{{ route('admin.users.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-user me-2"></i>

                                        Users

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Roles --}}
                                {{-- ================================================= --}}
                                @if(auth()->user()?->can('roles.view'))

                                    <a href="{{ route('admin.roles.index') }}"
                                       class="dropdown-item">

                                        <i class="bx bx-shield me-2"></i>

                                        Roles

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- Permissions --}}
                                {{-- ================================================= --}}
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