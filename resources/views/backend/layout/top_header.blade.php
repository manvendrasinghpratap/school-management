@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;

    $user = auth()->user();
    $school = $user?->school;

    /* School */
    $schoolName = $school?->name ?: config('app.name', 'School Management System');
    $schoolCode = $school?->code;

    $schoolLogoFallback = asset('backend/assets/images/logo-light.png');
    $schoolLogoUrl = $schoolLogoFallback;

    if ($school?->logo) {
        $schoolLogoUrl = Storage::disk('public')->url($school->logo);
    }

    /* User */
    $displayName = $user?->name ?: $user?->username ?: 'User';
    $email = $user?->email;
    $roleName = $user?->getRoleNames()?->first() ?: 'User';

    $avatarFallback = asset('backend/assets/images/profile-img.png');
    $avatarUrl = $avatarFallback;

    if ($user?->avatar && $user->avatar !== 'default.png') {
        $avatarUrl = filter_var($user->avatar, FILTER_VALIDATE_URL)
            ? $user->avatar
            : Storage::disk('public')->url($user->avatar);
    }

    $dashboardUrl = Route::has('admin.dashboard')
        ? route('admin.dashboard')
        : url('/admin/dashboard');

    $profileUrl = Route::has('profile.edit')
        ? route('profile.edit')
        : url('/profile');
@endphp

<header id="page-topbar">
    <div class="navbar-header sms-header-bar">

        {{-- =========================================================
             BRAND / SCHOOL
             ========================================================= --}}
        <div class="d-flex align-items-center sms-header-left">

            <div class="navbar-brand-box sms-brand-box">
                <a href="{{ $dashboardUrl }}" class="logo logo-light sms-brand-link">
                    <img
                        src="{{ $schoolLogoUrl }}"
                        alt="{{ $schoolName }} Logo"
                        class="sms-school-logo"
                        onerror="this.onerror=null;this.src='{{ $schoolLogoFallback }}';"
                    >
                </a>
            </div>

            <button
                type="button"
                class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light"
                data-bs-toggle="collapse"
                data-bs-target="#topnav-menu-content"
                aria-controls="topnav-menu-content"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <div class="d-none d-md-block sms-school-info">
                <div class="sms-school-name">
                    {{ $schoolName }}
                </div>
                <div class="sms-school-subtitle">
                    School Management System
                    @if($schoolCode)
                        <span>• {{ $schoolCode }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- =========================================================
             RIGHT SIDE
             ========================================================= --}}
        <div class="d-flex align-items-center sms-header-right">

            <button
                type="button"
                class="btn header-item noti-icon waves-effect d-none d-lg-inline-flex"
                data-bs-toggle="fullscreen"
                aria-label="Toggle fullscreen"
                title="Fullscreen"
            >
                <i class="bx bx-fullscreen"></i>
            </button>

            {{-- =====================================================
                 USER PROFILE
                 ===================================================== --}}
            <div class="dropdown sms-user-dropdown">

                <button
                    type="button"
                    class="btn header-item waves-effect sms-user-toggle"
                    id="page-header-user-dropdown"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    <span class="sms-user-identity">
                        <span class="sms-avatar-wrap">
                            <img
                                src="{{ $avatarUrl }}"
                                alt="{{ $displayName }}"
                                class="rounded-circle sms-user-avatar"
                                onerror="this.onerror=null;this.src='{{ $avatarFallback }}';"
                            >
                        </span>

                        <span class="sms-user-text d-none d-xl-flex">
                            <span class="sms-user-name">{{ $displayName }}</span>
                            <span class="sms-user-role">{{ $roleName }}</span>
                        </span>

                        <i class="mdi mdi-chevron-down sms-user-chevron"></i>
                    </span>
                </button>

                {{-- =================================================
                     PROFILE DROPDOWN
                     ================================================= --}}
                <div
                    class="dropdown-menu dropdown-menu-end sms-user-menu"
                    aria-labelledby="page-header-user-dropdown"
                >

                    <div class="sms-user-menu-head">
                        <img
                            src="{{ $avatarUrl }}"
                            alt="{{ $displayName }}"
                            class="rounded-circle sms-user-menu-avatar"
                            onerror="this.onerror=null;this.src='{{ $avatarFallback }}';"
                        >

                        <div class="sms-user-menu-details">
                            <div class="sms-user-menu-name">
                                {{ $displayName }}
                            </div>

                            @if($email)
                                <div class="sms-user-menu-email" title="{{ $email }}">
                                    {{ $email }}
                                </div>
                            @endif

                            <span class="badge bg-primary-subtle text-primary sms-role-badge">
                                {{ $roleName }}
                            </span>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item sms-user-menu-item" href="{{ $profileUrl }}">
                        <i class="bx bx-user-circle"></i>
                        <span>My Profile</span>
                    </a>

                    @if($school && Route::has('admin.school.edit'))
                        <a
                            class="dropdown-item sms-user-menu-item"
                            href="{{ route('admin.school.edit', $school) }}"
                        >
                            <i class="bx bx-building-house"></i>
                            <span>School Profile</span>
                        </a>
                    @endif

                    @if(
                        $school &&
                        $user?->can('settings.view') &&
                        Route::has('admin.school.settings.edit')
                    )
                        <a
                            class="dropdown-item sms-user-menu-item"
                            href="{{ route('admin.school.settings.edit', $school->id) }}"
                        >
                            <i class="bx bx-cog"></i>
                            <span>School Settings</span>
                        </a>
                    @endif

                    <a class="dropdown-item sms-user-menu-item" href="{{ $dashboardUrl }}">
                        <i class="bx bx-home-circle"></i>
                        <span>Dashboard</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="dropdown-item sms-user-menu-item sms-logout-item"
                        >
                            <i class="bx bx-log-out"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    /* =============================================================
       TOP HEADER
       ============================================================= */

    #page-topbar,
    #page-topbar .navbar-header {
        width: 100%;
        min-width: 0;
    }

    #page-topbar .navbar-header {
        min-height: 68px;
        height: 68px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        overflow: visible;
    }

    .sms-header-left,
    .sms-header-right {
        height: 100%;
        min-width: 0;
    }

    /* =============================================================
       BRAND
       ============================================================= */

    .sms-brand-box {
        min-width: 185px;
        width: 185px;
        height: 68px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sms-brand-link {
        width: 100%;
        height: 100%;
        display: flex !important;
        align-items: center;
        justify-content: center;
        padding: 0 16px;
    }

    .sms-school-logo {
        max-width: 145px;
        max-height: 42px;
        width: auto;
        height: auto;
        object-fit: contain;
        display: block;
    }

    /* =============================================================
       SCHOOL INFO
       ============================================================= */

    .sms-school-info {
        min-width: 0;
        line-height: 1.15;
        padding-left: 14px;
        padding-right: 18px;
    }

    .sms-school-name {
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 420px;
    }

    .sms-school-subtitle {
        color: rgba(255, 255, 255, .60);
        font-size: 11px;
        margin-top: 2px;
        white-space: nowrap;
    }

    /* =============================================================
       RIGHT SIDE
       ============================================================= */

    .sms-header-right {
        flex: 0 0 auto;
        padding-right: 10px;
    }

    .sms-header-right > .header-item {
        height: 68px;
        min-height: 68px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* =============================================================
       USER TRIGGER
       ============================================================= */

    .sms-user-dropdown {
        position: relative;
        height: 68px;
        display: flex;
        align-items: stretch;
    }

    .sms-user-toggle {
        min-width: 215px;
        height: 68px;
        padding: 0 14px !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        border-radius: 0;
        color: #fff;
    }

    .sms-user-identity {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        line-height: 1;
    }

    .sms-avatar-wrap {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        border-radius: 50%;
        overflow: hidden;
        background: rgba(255,255,255,.12);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sms-user-avatar {
        width: 38px;
        height: 38px;
        object-fit: cover;
        display: block;
    }

    .sms-user-text {
        min-width: 0;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        gap: 4px;
    }

    .sms-user-name {
        max-width: 145px;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sms-user-role {
        color: rgba(255,255,255,.55);
        font-size: 10px;
        font-weight: 500;
        white-space: nowrap;
    }

    .sms-user-chevron {
        color: rgba(255,255,255,.72);
        font-size: 15px;
        margin-left: 1px;
        flex: 0 0 auto;
    }

    /* =============================================================
       PROFILE DROPDOWN
       ============================================================= */

    .sms-user-menu {
        width: 320px;
        min-width: 320px;
        padding: 0;
        margin-top: 0 !important;
        border: 0;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 12px 35px rgba(15, 23, 42, .16);
        overflow: hidden;
    }

    .sms-user-menu-head {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 18px 16px;
        background: #fff;
    }

    .sms-user-menu-avatar {
        width: 50px;
        height: 50px;
        object-fit: cover;
        flex: 0 0 50px;
    }

    .sms-user-menu-details {
        min-width: 0;
        flex: 1;
    }

    .sms-user-menu-name {
        font-size: 14px;
        font-weight: 700;
        color: #212529;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sms-user-menu-email {
        margin-top: 3px;
        font-size: 11px;
        color: #74788d;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sms-role-badge {
        margin-top: 7px;
        font-size: 10px;
        font-weight: 600;
    }

    .sms-user-menu-item {
        min-height: 43px;
        display: flex !important;
        align-items: center;
        gap: 10px;
        padding: 9px 18px !important;
        font-size: 13px;
    }

    .sms-user-menu-item i {
        width: 18px;
        text-align: center;
        font-size: 17px;
        flex: 0 0 18px;
    }

    .sms-user-menu-item span {
        min-width: 0;
    }

    .sms-user-menu-item:hover {
        background: #f6f8ff;
    }

    .sms-logout-item {
        color: #f06565 !important;
        border: 0;
        background: transparent;
        width: 100%;
        text-align: left;
    }

    .sms-logout-item:hover {
        color: #d94848 !important;
        background: #fff5f5;
    }

    /* =============================================================
       MOBILE
       ============================================================= */

    @media (max-width: 1199.98px) {
        .sms-user-toggle {
            min-width: 180px;
        }

        .sms-school-name {
            max-width: 320px;
        }
    }

    @media (max-width: 767.98px) {
        #page-topbar .navbar-header {
            min-height: 60px;
            height: 60px;
        }

        .sms-brand-box,
        .sms-user-dropdown,
        .sms-user-toggle {
            height: 60px;
        }

        .sms-brand-box {
            min-width: 125px;
            width: 125px;
        }

        .sms-school-logo {
            max-width: 100px;
            max-height: 34px;
        }

        .sms-header-right {
            padding-right: 0;
        }

        .sms-user-toggle {
            min-width: 58px;
            width: 58px;
            padding: 0 8px !important;
        }

        .sms-user-identity {
            justify-content: center;
        }

        .sms-user-chevron {
            display: none;
        }

        .sms-user-menu {
            width: min(320px, calc(100vw - 16px));
            min-width: min(320px, calc(100vw - 16px));
        }
    }
</style>
