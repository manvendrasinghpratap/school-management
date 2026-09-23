@php
    use Illuminate\Support\Facades\Route;

    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Permission-aware menu definition
    |--------------------------------------------------------------------------
    | Keep route names and permission names aligned with the existing SMS
    | application. A parent menu is shown only when at least one child is
    | visible to the current user.
    |--------------------------------------------------------------------------
    */
    $menuGroups = [
       
        [
            'key' => 'students',
            'label' => 'Students',
            'icon' => 'bx-user',
            'wide' => true,
            'sections' => [
                [
                    'title' => 'Student Management',
                    'icon' => 'bx-user',
                    'items' => [
                        ['label' => 'Student List', 'icon' => 'bx-list-ul', 'route' => 'admin.students.index', 'permissions' => ['students.view']],
                        ['label' => 'Student Registration', 'icon' => 'bx-user-plus', 'route' => 'admin.students.create', 'permissions' => ['students.create']],
                        ['label' => 'Guardians', 'icon' => 'bx-group', 'route' => 'admin.guardians.index', 'permissions' => ['guardians.view']],
                        ['label' => 'Enrollments', 'icon' => 'bx-book-add', 'route' => 'admin.student-enrollments.index', 'permissions' => ['enrollments.view']],
                        ['label' => 'Add Enrollment', 'icon' => 'bx-user-check', 'route' => 'admin.student-enrollments.create', 'permissions' => ['enrollments.create']],
                    ],
                ],
                [
                    'title' => 'Student Lifecycle',
                    'icon' => 'bx-transfer',
                    'items' => [
                        ['label' => 'Promotions', 'icon' => 'bx-transfer', 'route' => 'admin.student-promotions.index', 'permissions' => ['promotions.view', 'promotions.create']],
                        ['label' => 'Graduation', 'icon' => 'bx-award', 'route' => 'admin.graduations.index', 'permissions' => ['graduation.view', 'graduation.manage']],
                        ['label' => 'Alumni', 'icon' => 'bx-group', 'route' => 'admin.alumni.index', 'permissions' => ['alumni.view', 'alumni.manage']],
                    ],
                ],
                [
                    'title' => 'Attendance',
                    'icon' => 'bx-calendar-check',
                    'items' => [
                        ['label' => 'Student Attendance', 'icon' => 'bx-calendar-check', 'route' => 'admin.attendance.index', 'permissions' => ['attendance.view']],
                        ['label' => 'Mark Attendance', 'icon' => 'bx-user-check', 'route' => 'admin.attendance.create', 'permissions' => ['attendance.mark']],
                        ['label' => 'Attendance Reports', 'icon' => 'bx-bar-chart-alt-2', 'route' => 'admin.attendance.reports', 'permissions' => ['attendance.reports']],
                    ],
                ],
            ],
        ],
        [
            'key' => 'academics',
            'label' => 'Academics',
            'icon' => 'bx-book-open',
            'wide' => true,
            'sections' => [
                [
                    'title' => 'Academic Setup',
                    'icon' => 'bx-book',
                    'items' => [
                        ['label' => 'Academic Years', 'icon' => 'bx-calendar', 'route' => 'admin.academic-years.index', 'permissions' => ['academic-years.view']],
                        ['label' => 'Terms / Semesters', 'icon' => 'bx-calendar-event', 'route' => 'admin.terms.index', 'permissions' => ['terms.view']],
                        ['label' => 'Departments', 'icon' => 'bx-sitemap', 'route' => 'admin.departments.index', 'permissions' => ['departments.view']],
                        ['label' => 'Classes', 'icon' => 'bx-building', 'route' => 'admin.classes.index', 'permissions' => ['classes.view']],
                        ['label' => 'Sections / Streams', 'icon' => 'bx-grid-alt', 'route' => 'admin.sections.index', 'permissions' => ['sections.view']],
                        ['label' => 'Subjects / Courses', 'icon' => 'bx-book', 'route' => 'admin.courses.index', 'permissions' => ['courses.view']],
                        ['label' => 'Timetable', 'icon' => 'bx-time-five', 'route' => 'admin.timetables.index', 'permissions' => ['timetable.view', 'timetable.manage']],
                    ],
                ],
                [
                    'title' => 'Examinations',
                    'icon' => 'bx-edit-alt',
                    'items' => [
                        ['label' => 'Examinations', 'icon' => 'bx-edit-alt', 'route' => 'admin.examinations.index', 'permissions' => ['examinations.view', 'examinations.create', 'examinations.update', 'examinations.delete']],
                        ['label' => 'Exam Schedules', 'icon' => 'bx-calendar-event', 'route' => 'admin.exam-schedules.index', 'permissions' => ['exam-schedules.view', 'exam-schedules.manage']],
                        ['label' => 'Subject Marks', 'icon' => 'bx-pencil', 'route' => 'admin.marks.index', 'permissions' => ['marks.view', 'marks.enter', 'marks.update', 'marks.approve']],
                        ['label' => 'Grading', 'icon' => 'bx-bar-chart-alt-2', 'route' => 'admin.grading.index', 'permissions' => ['grading.view', 'grading.manage']],
                        ['label' => 'Results', 'icon' => 'bx-spreadsheet', 'route' => 'admin.results.index', 'permissions' => ['results.view', 'results.calculate', 'results.approve', 'results.publish']],
                        ['label' => 'Report Cards', 'icon' => 'bx-file', 'route' => 'admin.report-cards.index', 'permissions' => ['report-cards.view', 'report-cards.generate']],
                        ['label' => 'Transcripts', 'icon' => 'bx-file-blank', 'route' => 'admin.transcripts.index', 'permissions' => ['transcripts.view', 'transcripts.generate']],
                    ],
                ],
            ],
        ],
        [
            'key' => 'staff',
            'label' => 'Staff',
            'icon' => 'bx-group',
            'wide' => true,
            'sections' => [
                [
                    'title' => 'Staff Management',
                    'icon' => 'bx-group',
                    'items' => [
                        ['label' => 'Staff List', 'icon' => 'bx-group', 'route' => 'admin.staff.index', 'permissions' => ['staff.view']],
                        ['label' => 'Add Staff', 'icon' => 'bx-user-plus', 'route' => 'admin.staff.create', 'permissions' => ['staff.create']],
                        ['label' => 'Instructors', 'icon' => 'bx-chalkboard', 'route' => 'admin.instructors.index', 'permissions' => ['instructors.view', 'instructors.create', 'instructors.update', 'instructors.delete']],
                    ],
                ],
                [
                    'title' => 'Attendance & Leave',
                    'icon' => 'bx-calendar-check',
                    'items' => [
                        ['label' => 'Staff Attendance', 'icon' => 'bx-calendar-check', 'route' => 'admin.staff-attendance.index', 'permissions' => ['staff-attendance.view']],
                        ['label' => 'Mark Attendance', 'icon' => 'bx-user-check', 'route' => 'admin.staff-attendance.create', 'permissions' => ['staff-attendance.mark']],
                        ['label' => 'Attendance Reports', 'icon' => 'bx-bar-chart-alt-2', 'route' => 'admin.staff-attendance.reports', 'permissions' => ['staff-attendance.reports']],
                        ['label' => 'Leave Management', 'icon' => 'bx-calendar-minus', 'route' => 'admin.leaves.index', 'permissions' => ['leaves.view', 'leaves.create', 'leaves.update', 'leaves.delete', 'leaves.approve', 'leaves.reject', 'leaves.reports']],
                        ['label' => 'New Leave Request', 'icon' => 'bx-calendar-plus', 'route' => 'admin.leaves.create', 'permissions' => ['leaves.create']],
                        ['label' => 'Leave Reports', 'icon' => 'bx-file-find', 'route' => 'admin.leaves.reports', 'permissions' => ['leaves.reports']],
                    ],
                ],
            ],
        ],
        [
            'key' => 'finance',
            'label' => 'Finance',
            'icon' => 'bx-wallet',
            'wide' => true,
            'sections' => [
                [
                    'title' => 'Fee Setup',
                    'icon' => 'bx-wallet',
                    'items' => [
                        ['label' => 'Fee Categories', 'icon' => 'bx-category', 'route' => 'admin.fee-categories.index', 'permissions' => ['fees.view', 'fees.manage']],
                        ['label' => 'Fee Structures', 'icon' => 'bx-layer', 'route' => 'admin.fee-structures.index', 'permissions' => ['fee-structures.view', 'fee-structures.manage']],
                        ['label' => 'Fee Installments', 'icon' => 'bx-calendar', 'route' => 'admin.fee-installments.index', 'permissions' => ['fees.view', 'fees.manage']],
                        ['label' => 'Student Fee Assignment', 'icon' => 'bx-user-check', 'route' => 'admin.student-fees.index', 'permissions' => ['fees.view', 'fees.manage']],
                        ['label' => 'Scholarships', 'icon' => 'bx-award', 'route' => 'admin.scholarships.index', 'permissions' => ['scholarships.view', 'scholarships.manage']],
                    ],
                ],
                [
                    'title' => 'Billing & Payments',
                    'icon' => 'bx-receipt',
                    'items' => [
                        ['label' => 'Invoices', 'icon' => 'bx-file', 'route' => 'admin.invoices.index', 'permissions' => ['invoices.view', 'invoices.create', 'invoices.update', 'invoices.delete']],
                        ['label' => 'Payments', 'icon' => 'bx-money', 'route' => 'admin.payments.index', 'permissions' => ['payments.view', 'payments.create']],
                        ['label' => 'Payment Refunds', 'icon' => 'bx-revision', 'route' => 'admin.payment-refunds.index', 'permissions' => ['payment-refunds.view', 'payment-refunds.approve', 'payment-refunds.reject', 'payment-refunds.process', 'payment-refunds.cancel']],
                    ],
                ],
            ],
        ],
        [
            'key' => 'services',
            'label' => 'Services',
            'icon' => 'bx-grid-alt',
            'wide' => true,
            'sections' => [
                [
                    'title' => 'Library',
                    'icon' => 'bx-library',
                    'items' => [
                        ['label' => 'Books', 'icon' => 'bx-book', 'route' => 'admin.library.books.index', 'permissions' => ['library.view', 'library.create', 'library.update', 'library.delete']],
                        ['label' => 'Book Categories', 'icon' => 'bx-category', 'route' => 'admin.library.categories.index', 'permissions' => ['library.view', 'library.create', 'library.update', 'library.delete']],
                        ['label' => 'Authors', 'icon' => 'bx-user', 'route' => 'admin.library.authors.index', 'permissions' => ['library.view', 'library.create', 'library.update', 'library.delete']],
                        ['label' => 'Publishers', 'icon' => 'bx-buildings', 'route' => 'admin.library.publishers.index', 'permissions' => ['library.view', 'library.create', 'library.update', 'library.delete']],
                        ['label' => 'Members', 'icon' => 'bx-group', 'route' => 'admin.library.members.index', 'permissions' => ['library.members.view', 'library.members.manage']],
                        ['label' => 'Add Member', 'icon' => 'bx-user-plus', 'route' => 'admin.library.members.create', 'permissions' => ['library.members.manage']],
                        ['label' => 'Book Issues', 'icon' => 'bx-list-check', 'route' => 'admin.library.issues.index', 'permissions' => ['library.issues.view', 'library.issues.manage']],
                        ['label' => 'Issue Book', 'icon' => 'bx-book-add', 'route' => 'admin.library.issues.create', 'permissions' => ['library.issues.manage']],
                        ['label' => 'Reservations', 'icon' => 'bx-bookmark', 'route' => 'admin.library.reservations.index', 'permissions' => ['library.reservations.view', 'library.reservations.manage']],
                    ],
                ],
                [
                    'title' => 'Transport',
                    'icon' => 'bx-bus',
                    'items' => [
                        ['label' => 'Drivers', 'icon' => 'bx-user', 'route' => 'admin.transport.drivers.index', 'permissions' => ['transport.view', 'transport.manage']],
                        ['label' => 'Vehicles', 'icon' => 'bx-car', 'route' => 'admin.transport.vehicles.index', 'permissions' => ['transport.view', 'transport.manage']],
                        ['label' => 'Transport Routes', 'icon' => 'bx-map', 'route' => 'admin.transport.routes.index', 'permissions' => ['transport.view', 'transport.manage']],
                        ['label' => 'Student Assignments', 'icon' => 'bx-user-check', 'route' => 'admin.transport.assignments.index', 'permissions' => ['transport.view', 'transport.assign']],
                        ['label' => 'Transport Fees', 'icon' => 'bx-money', 'route' => 'admin.transport.fees.index', 'permissions' => ['transport.fees.view', 'transport.fees.manage']],
                    ],
                ],
                [
                    'title' => 'Hostel',
                    'icon' => 'bx-home-alt',
                    'items' => [
                        ['label' => 'Hostels', 'icon' => 'bx-building-house', 'route' => 'admin.hostel.index', 'permissions' => ['hostel.view', 'hostel.manage']],
                        ['label' => 'Rooms & Beds', 'icon' => 'bx-door-open', 'route' => 'admin.hostel.index', 'permissions' => ['hostel.rooms.manage']],
                        ['label' => 'Hostel Allocations', 'icon' => 'bx-home-heart', 'route' => 'admin.hostel.allocations.index', 'permissions' => ['hostel.allocations.view', 'hostel.allocations.manage']],
                        ['label' => 'Hostel Fees', 'icon' => 'bx-money', 'route' => 'admin.hostel.fees.index', 'permissions' => ['hostel.fees.view', 'hostel.fees.manage']],
                    ],
                ],
            ],
        ],
        [
            'key' => 'communication',
            'label' => 'Communication',
            'icon' => 'bx-megaphone',
            'wide' => true,
            'sections' => [
                [
                    'title' => 'Communication & Events',
                    'icon' => 'bx-megaphone',
                    'items' => [
                        ['label' => 'Announcements', 'icon' => 'bx-megaphone', 'route' => 'admin.announcements.index', 'permissions' => ['announcements.view']],
                        ['label' => 'School Events', 'icon' => 'bx-calendar-event', 'route' => 'admin.events.index', 'permissions' => ['events.view']],
                    ],
                ],
                [
                    'title' => 'Identity & Documents',
                    'icon' => 'bx-id-card',
                    'items' => [
                        ['label' => 'Certificates', 'icon' => 'bx-award', 'route' => 'admin.certificates.index', 'permissions' => ['certificates.view']],
                        ['label' => 'Certificate Templates', 'icon' => 'bx-layout', 'route' => 'admin.certificates.templates', 'permissions' => ['certificate-templates.manage']],
                        ['label' => 'ID Cards', 'icon' => 'bx-id-card', 'route' => 'admin.id-cards.index', 'permissions' => ['id-cards.view']],
                        ['label' => 'ID Card Templates', 'icon' => 'bx-layout', 'route' => 'admin.id-cards.templates', 'permissions' => ['id-cards.generate']],
                    ],
                ],
            ],
        ],
        [
            'key' => 'reports',
            'label' => 'Reports',
            'icon' => 'bx-bar-chart-alt-2',
            'sections' => [
                [
                    'title' => 'Reports & Analytics',
                    'icon' => 'bx-bar-chart-alt-2',
                    'items' => [
                        ['label' => 'Reports Dashboard', 'icon' => 'bx-dashboard', 'route' => 'admin.reports.index', 'permissions' => ['reports.view']],
                        ['label' => 'Student Reports', 'icon' => 'bx-user', 'route' => 'admin.reports.students', 'permissions' => ['reports.view']],
                        ['label' => 'Enrollment Reports', 'icon' => 'bx-user-plus', 'route' => 'admin.reports.enrollment', 'permissions' => ['reports.view']],
                        ['label' => 'Academic Performance', 'icon' => 'bx-line-chart', 'route' => 'admin.reports.academic-performance', 'permissions' => ['reports.view']],
                        ['label' => 'Attendance Reports', 'icon' => 'bx-calendar-check', 'route' => 'admin.reports.attendance', 'permissions' => ['reports.view']],
                        ['label' => 'Staff Reports', 'icon' => 'bx-group', 'route' => 'admin.reports.staff', 'permissions' => ['reports.view']],
                    ],
                ],
            ],
        ],
        [
            'key' => 'administration',
            'label' => 'Administration',
            'icon' => 'bx-cog',
            'wide' => false,
            'sections' => [
                [
                    'title' => 'Access Control',
                    'icon' => 'bx-shield',
                    'items' => [
                        ['label' => 'Users', 'icon' => 'bx-user', 'route' => 'admin.users.index', 'permissions' => ['users.view']],
                        ['label' => 'Roles', 'icon' => 'bx-shield', 'route' => 'admin.roles.index', 'permissions' => ['roles.view']],
                        ['label' => 'Permissions', 'icon' => 'bx-lock-alt', 'route' => 'admin.permissions.index', 'permissions' => ['permissions.view']],
                    ],
                ],
                [
                    'title' => 'School Operations',
                    'icon' => 'bx-package',
                    'items' => [
                        ['label' => 'Designations', 'icon' => 'bx-id-card', 'route' => 'admin.designations.index', 'permissions' => ['designations.view']],
                        ['label' => 'Admissions', 'icon' => 'bx-user-plus', 'route' => 'admin.admissions.index', 'permissions' => ['admissions.view']],
                        ['label' => 'Suppliers', 'icon' => 'bx-store', 'route' => 'admin.suppliers.index', 'permissions' => ['suppliers.view']],
                        ['label' => 'Inventory', 'icon' => 'bx-package', 'route' => 'admin.inventory.index', 'permissions' => ['inventory.view']],
                        ['label' => 'Assets', 'icon' => 'bx-cube', 'route' => 'admin.assets.index', 'permissions' => ['assets.view']],
                        ['label' => 'Visitors', 'icon' => 'bx-door-open', 'route' => 'admin.visitors.index', 'permissions' => ['visitors.view']],
                        ['label' => 'Complaints', 'icon' => 'bx-message-square-error', 'route' => 'admin.complaints.index', 'permissions' => ['complaints.view']],
                        ['label' => 'Discipline', 'icon' => 'bx-error', 'route' => 'admin.discipline.index', 'permissions' => ['discipline.view']],
                        ['label' => 'Medical Records', 'icon' => 'bx-first-aid', 'route' => 'admin.medical.index', 'permissions' => ['medical.view']],
                    ],
                ],
            ],
        ],
    ];

    $hasPermission = function (array $permissions) use ($user): bool {
        foreach ($permissions as $permission) {
            if ($user?->can($permission)) {
                return true;
            }
        }
        return false;
    };

    $isVisibleItem = function (array $item) use ($hasPermission, $user): bool {
        if (!Route::has($item['route'])) {
            return false;
        }

        if (
            $item['route'] === 'admin.school.settings.edit' &&
            ! $user?->school_id
        ) {
            return false;
        }

        return $hasPermission($item['permissions'] ?? []);
    };

    foreach ($menuGroups as &$group) {
        if (!empty($group['items'])) {
            $group['visible_items'] = array_values(array_filter(
                $group['items'],
                $isVisibleItem
            ));
        }

        if (!empty($group['sections'])) {
            $visibleSections = [];
            foreach ($group['sections'] as $section) {
                $visibleItems = array_values(array_filter(
                    $section['items'] ?? [],
                    $isVisibleItem
                ));
                if ($visibleItems) {
                    $section['visible_items'] = $visibleItems;
                    $visibleSections[] = $section;
                }
            }
            $group['visible_sections'] = $visibleSections;
        }
    }
    unset($group);

    $isItemActive = function (array $item): bool {
        return request()->routeIs($item['route']);
    };

    $isGroupActive = function (array $group) use ($isItemActive): bool {
        foreach ($group['visible_items'] ?? [] as $item) {
            if ($isItemActive($item)) {
                return true;
            }
        }

        foreach ($group['visible_sections'] ?? [] as $section) {
            foreach ($section['visible_items'] ?? [] as $item) {
                if ($isItemActive($item)) {
                    return true;
                }
            }
        }

        return false;
    };
@endphp

<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav align-items-lg-center sms-navbar-list">

                    {{-- Dashboard --}}
                    @if($user?->can('dashboard.view') && Route::has('admin.dashboard'))
                        <li class="nav-item">
                            <a class="nav-link sms-topnav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                               href="{{ route('admin.dashboard') }}">
                                <i class="bx bx-home-circle me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif

                    @foreach($menuGroups as $group)
                        @php
                            $visibleItems = $group['visible_items'] ?? [];
                            $visibleSections = $group['visible_sections'] ?? [];
                            $hasMenuContent = !empty($visibleItems) || !empty($visibleSections);
                        @endphp

                        @if($hasMenuContent)
                            <li class="nav-item dropdown">
                                @php
                                    $groupActive = $isGroupActive($group);
                                    $dropAlignRight = in_array(
                                        $group['key'],
                                        ['reports', 'administration'],
                                        true
                                    );
                                @endphp
                                <a class="nav-link dropdown-toggle arrow-none sms-topnav-link {{ $groupActive ? 'active' : '' }}"
                                   href="#"
                                   id="topnav-{{ $group['key'] }}"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   data-bs-auto-close="outside"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    <i class="bx {{ $group['icon'] }} me-2"></i>
                                    <span>{{ $group['label'] }}</span>
                                    <div class="arrow-down"></div>
                                </a>

                                <div class="dropdown-menu {{ !empty($group['wide']) ? 'sms-mega-menu' : 'sms-admin-menu' }} {{ $dropAlignRight ? 'dropdown-menu-end' : '' }}"
                                     aria-labelledby="topnav-{{ $group['key'] }}">

                                    @if($visibleItems)
                                        @foreach($visibleItems as $item)
                                            @php
                                                $itemUrl = $item['route'] === 'admin.school.settings.edit'
                                                    ? route($item['route'], $user?->school_id)
                                                    : route($item['route']);

                                                $itemActive = $isItemActive($item);
                                            @endphp

                                            <a href="{{ $itemUrl }}"
                                               class="dropdown-item sms-menu-item {{ $itemActive ? 'active' : '' }}">
                                                <i class="bx {{ $item['icon'] }}"></i>
                                                <span>{{ $item['label'] }}</span>
                                            </a>
                                        @endforeach
                                    @endif

                                    @if($visibleSections)
                                        <div class="sms-mega-grid">
                                            @foreach($visibleSections as $section)
                                                <div class="sms-menu-column">
                                                    <div class="sms-menu-section">
                                                        <div class="sms-menu-heading">
                                                            <i class="bx {{ $section['icon'] }}"></i>
                                                            <span>{{ $section['title'] }}</span>
                                                        </div>

                                                        @foreach($section['visible_items'] as $item)
                                                            @php
                                                                $itemUrl = $item['route'] === 'admin.school.settings.edit'
                                                                    ? route($item['route'], $user?->school_id)
                                                                    : route($item['route']);

                                                                $itemActive = $isItemActive($item);
                                                            @endphp

                                                            <a href="{{ $itemUrl }}"
                                                               class="dropdown-item sms-menu-item {{ $itemActive ? 'active' : '' }}">
                                                                <i class="bx {{ $item['icon'] }}"></i>
                                                                <span>{{ $item['label'] }}</span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @endif
                    @endforeach

                </ul>
            </div>
        </nav>
    </div>
</div>

<style>
/* ================================================================
   SMS ADMIN NAVBAR — PROFESSIONAL / RESPONSIVE
   ================================================================ */

.topnav {
    position: relative;
    z-index: 1030;
    width: 100%;
    max-width: 100%;
    overflow-x: clip;
    background: #ffffff;
    border-top: 1px solid rgba(30, 42, 60, .06);
    border-bottom: 1px solid rgba(30, 42, 60, .08);
    box-shadow: 0 3px 14px rgba(18, 38, 63, .06);
}

.topnav .container-fluid {
    padding-left: 14px;
    padding-right: 14px;
}

.topnav-menu {
    min-height: 58px;
    width: 100%;
}

.sms-navbar-list {
    width: 100%;
    display: flex;
    align-items: stretch;
    justify-content: space-between;
    flex-wrap: nowrap;
    gap: 0;
    overflow: visible;
    min-width: 0;
}

.topnav .navbar-nav > .nav-item {
    flex: 1 1 0;
    min-width: 0;
}

.sms-topnav-link {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-width: 0;
    min-height: 58px;
    margin: 0;
    padding: 0 7px !important;
    border-radius: 8px 8px 0 0;
    color: #4b5563 !important;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: .005em;
    line-height: 1.2;
    transition: color .18s ease, background-color .18s ease, box-shadow .18s ease;
}

.sms-topnav-link i {
    flex: 0 0 auto;
    margin-right: 5px !important;
    font-size: 16px;
    line-height: 1;
    transition: transform .18s ease;
}

.sms-topnav-link > span {
    min-width: 0;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sms-topnav-link .arrow-down {
    flex: 0 0 auto;
    margin-left: 5px;
    transition: transform .18s ease;
}

.sms-topnav-link:hover,
.sms-topnav-link:focus-visible {
    color: #515def !important;
    background: rgba(81, 93, 239, .06);
    text-decoration: none;
}

.sms-topnav-link:hover i {
    transform: translateY(-1px);
}

.sms-topnav-link.active {
    color: #515def !important;
    background: rgba(81, 93, 239, .08);
    font-weight: 600;
}

.sms-topnav-link.active::after {
    content: "";
    position: absolute;
    left: 12px;
    right: 12px;
    bottom: 0;
    height: 3px;
    border-radius: 3px 3px 0 0;
    background: #515def;
}

.sms-topnav-link[aria-expanded="true"] {
    color: #515def !important;
    background: rgba(81, 93, 239, .07);
}

.sms-topnav-link[aria-expanded="true"] .arrow-down {
    transform: rotate(180deg);
}

/* ---------------------------------------------------------------
   DROPDOWNS
   --------------------------------------------------------------- */

.topnav .dropdown-menu.sms-mega-menu,
.topnav .dropdown-menu.sms-admin-menu {
    position: absolute;
    margin-top: 4px !important;
    border: 1px solid rgba(30, 42, 60, .08) !important;
    border-radius: 12px !important;
    background: #ffffff;
    box-shadow: 0 20px 45px rgba(18, 38, 63, .16) !important;
    overflow: hidden;
}

.topnav .dropdown-menu.sms-mega-menu {
    width: 760px !important;
    min-width: 0 !important;
    max-width: calc(100vw - 28px) !important;
    padding: 10px !important;
}

.topnav .dropdown-menu.sms-admin-menu {
    width: 290px !important;
    min-width: 0 !important;
    max-width: calc(100vw - 28px) !important;
    padding: 8px !important;
}

.dropdown-menu .sms-mega-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    align-items: start;
}

.sms-menu-column {
    min-width: 0;
    padding: 3px 9px 8px;
}

.sms-menu-column + .sms-menu-column {
    border-left: 1px solid rgba(30, 42, 60, .07);
}

/* ---------------------------------------------------------------
   MENU SECTIONS
   --------------------------------------------------------------- */

.sms-menu-section {
    min-width: 0;
    padding: 2px 0 8px;
}

.sms-menu-heading {
    display: flex;
    align-items: center;
    gap: 8px;
    min-height: 34px;
    margin: 0 4px 7px;
    padding: 7px 8px;
    color: #747b8c;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .08em;
    line-height: 1.2;
    text-transform: uppercase;
}

.sms-menu-heading i {
    flex: 0 0 19px;
    width: 19px;
    color: #515def;
    font-size: 15px;
    text-align: center;
}

/* ---------------------------------------------------------------
   MENU ITEMS
   --------------------------------------------------------------- */

.sms-menu-item {
    display: flex !important;
    align-items: center;
    gap: 9px;
    width: 100%;
    min-height: 36px;
    margin: 2px 0;
    padding: 8px 10px !important;
    border-radius: 8px;
    color: #343a40 !important;
    font-size: 13px;
    line-height: 1.35;
    transition: color .16s ease, background-color .16s ease, transform .16s ease;
}

.sms-menu-item i {
    flex: 0 0 19px;
    width: 19px;
    color: #7b8497;
    font-size: 15px;
    text-align: center;
    transition: color .16s ease, transform .16s ease;
}

.sms-menu-item span {
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sms-menu-item:hover,
.sms-menu-item:focus-visible {
    color: #515def !important;
    background: rgba(81, 93, 239, .07);
    text-decoration: none;
    transform: translateX(2px);
}

.sms-menu-item:hover i {
    color: #515def;
}

.sms-menu-item.active {
    color: #515def !important;
    background: rgba(81, 93, 239, .10);
    font-weight: 600;
}

.sms-menu-item.active i {
    color: #515def;
}

/* ---------------------------------------------------------------
   EMPTY / SINGLE MENUS
   --------------------------------------------------------------- */

.sms-admin-menu .sms-menu-item {
    min-height: 38px;
}

/* ---------------------------------------------------------------
   LARGE DESKTOP
   --------------------------------------------------------------- */

@media (min-width: 1400px) {
    .sms-topnav-link {
        padding-left: 8px !important;
        padding-right: 8px !important;
        font-size: 13px;
    }
}

/* ---------------------------------------------------------------
   DESKTOP FIT
   --------------------------------------------------------------- */

@media (min-width: 1200px) and (max-width: 1399.98px) {
    .sms-topnav-link {
        padding-left: 6px !important;
        padding-right: 6px !important;
        font-size: 12.5px;
    }

    .sms-topnav-link i {
        margin-right: 4px !important;
        font-size: 15px;
    }

    .sms-mega-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

/* ---------------------------------------------------------------
   TABLET
   --------------------------------------------------------------- */

@media (max-width: 1199.98px) and (min-width: 992px) {
    .sms-topnav-link {
        padding-left: 5px !important;
        padding-right: 5px !important;
        font-size: 11.5px;
    }

    .sms-topnav-link i {
        margin-right: 5px !important;
        font-size: 16px;
    }

    .topnav .dropdown-menu.sms-mega-menu {
        width: 680px !important;
        min-width: 0 !important;
    }

    .sms-mega-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* ---------------------------------------------------------------
   MOBILE
   --------------------------------------------------------------- */

@media (max-width: 991.98px) {
    .topnav {
        box-shadow: 0 2px 9px rgba(18, 38, 63, .08);
        overflow-x: visible;
    }

    .topnav .container-fluid {
        padding-left: 0;
        padding-right: 0;
    }

    .topnav-menu {
        min-height: auto;
    }

    .sms-navbar-list {
        width: 100%;
        flex-wrap: wrap;
        overflow: visible;
        padding: 6px 0 10px;
    }

    .topnav .navbar-nav > .nav-item {
        width: 100%;
    }

    .sms-topnav-link {
        width: 100%;
        min-height: 44px;
        justify-content: flex-start;
        margin: 1px 8px;
        padding: 0 12px !important;
        border-radius: 8px;
    }

    .sms-topnav-link.active::after {
        left: 0;
        right: auto;
        top: 7px;
        bottom: 7px;
        width: 3px;
        height: auto;
        border-radius: 0 3px 3px 0;
    }

    .topnav .dropdown-menu.sms-mega-menu,
    .topnav .dropdown-menu.sms-admin-menu {
        position: static !important;
        width: calc(100% - 24px) !important;
        min-width: 0 !important;
        max-width: none !important;
        margin: 2px 12px 8px !important;
        transform: none !important;
        box-shadow: none !important;
        border-radius: 8px !important;
    }

    .sms-mega-grid {
        display: block;
    }

    .sms-menu-column {
        padding-left: 3px;
        padding-right: 3px;
    }

    .sms-menu-column + .sms-menu-column {
        margin-top: 5px;
        padding-top: 5px;
        border-top: 1px solid rgba(30, 42, 60, .06);
        border-left: 0;
    }
}

</style>


<script>
(function () {
    function initSmsDropdowns() {
        if (typeof bootstrap === 'undefined' || !bootstrap.Dropdown) {
            return;
        }

        document.querySelectorAll('.topnav [data-bs-toggle="dropdown"]').forEach(function (toggle) {
            if (!bootstrap.Dropdown.getInstance(toggle)) {
                new bootstrap.Dropdown(toggle, {
                    autoClose: 'outside',
                    popperConfig: function (defaultBsPopperConfig) {
                        return Object.assign({}, defaultBsPopperConfig || {}, {
                            strategy: 'fixed'
                        });
                    }
                });
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSmsDropdowns);
    } else {
        initSmsDropdowns();
    }
})();
</script>
