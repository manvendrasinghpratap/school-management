@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;

    $profileUser = auth()->user();
    $school = $profileUser?->school;

    $displayName = $profileUser?->name
        ?: $profileUser?->username
        ?: 'User';

    $email = $profileUser?->email ?: '—';
    $username = $profileUser?->username ?: '—';

    $roleNames = $profileUser?->getRoleNames() ?? collect();
    $primaryRole = $roleNames->first() ?: 'User';

    $schoolName = $school?->name
        ?: config('app.name', 'School Management System');

    $avatarFallback = asset(
        'backend/assets/images/profile-img.png'
    );

    $avatarUrl = $avatarFallback;

    if (
        $profileUser?->avatar &&
        $profileUser->avatar !== 'default.png'
    ) {
        $avatarUrl = filter_var(
            $profileUser->avatar,
            FILTER_VALIDATE_URL
        )
            ? $profileUser->avatar
            : Storage::disk('public')->url(
                ltrim($profileUser->avatar, '/')
            );
    }
@endphp

@extends('backend.layout.default')

@section('title', 'My Profile')

@section('content')

<div class="container-fluid profile-page">

    {{-- ==========================================================
         PAGE HEADER
    =========================================================== --}}
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <div>
                    <h4 class="mb-1">
                        My Profile
                    </h4>

                    <p class="text-muted mb-0">
                        Manage your personal information, password and account security.
                    </p>
                </div>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            My Profile
                        </li>

                    </ol>

                </div>

            </div>

        </div>
    </div>


    {{-- ==========================================================
         FLASH / VALIDATION MESSAGES
    =========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show profile-alert" role="alert">
            <i class="bx bx-check-circle me-2"></i>
            <span>{{ session('success') }}</span>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show profile-alert" role="alert">
            <i class="bx bx-check-circle me-2"></i>
            <span>{{ session('status') }}</span>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show profile-alert" role="alert">

            <div class="fw-semibold mb-2">
                <i class="bx bx-error-circle me-2"></i>
                Please correct the following:
            </div>

            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>
    @endif


    {{-- ==========================================================
         PROFILE HERO
    =========================================================== --}}
    <div class="row mb-4">

        <div class="col-12">

            <div class="profile-hero card border-0">

                <div class="profile-hero-pattern"></div>

                <div class="card-body position-relative">

                    <div class="row align-items-center">

                        <div class="col-lg-auto text-center mb-3 mb-lg-0">

                            <div class="profile-avatar-wrap">

                                <img
                                    src="{{ $avatarUrl }}"
                                    alt="{{ $displayName }}"
                                    class="profile-avatar"
                                    onerror="this.onerror=null;this.src='{{ $avatarFallback }}';"
                                >

                                <span class="profile-online-dot"
                                      title="Account active"></span>

                            </div>

                        </div>


                        <div class="col-lg">

                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">

                                <h3 class="profile-name mb-0">
                                    {{ $displayName }}
                                </h3>

                                <span class="badge profile-role-badge">
                                    {{ $primaryRole }}
                                </span>

                            </div>

                            <div class="profile-email mb-2">
                                <i class="bx bx-envelope me-1"></i>
                                {{ $email }}
                            </div>

                            <div class="profile-school">
                                <i class="bx bx-building-house me-1"></i>
                                {{ $schoolName }}

                                @if($school?->code)
                                    <span class="profile-school-code">
                                        • {{ $school->code }}
                                    </span>
                                @endif
                            </div>

                        </div>


                        <div class="col-lg-auto mt-3 mt-lg-0">

                            <div class="d-flex flex-wrap justify-content-lg-end gap-2">

                                <a
                                    href="{{ route('admin.dashboard') }}"
                                    class="btn btn-light profile-action-btn"
                                >
                                    <i class="bx bx-home-circle me-1"></i>
                                    Dashboard
                                </a>

                                @if(
                                    Route::has('admin.school.edit') &&
                                    $profileUser?->school_id
                                )
                                    <a
                                        href="{{ route(
                                            'admin.school.edit',
                                            $profileUser->school_id
                                        ) }}"
                                        class="btn btn-primary profile-action-btn"
                                    >
                                        <i class="bx bx-building-house me-1"></i>
                                        School Profile
                                    </a>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         MAIN CONTENT
    =========================================================== --}}
    <div class="row g-4">


        {{-- ======================================================
             LEFT / MAIN COLUMN
        ======================================================= --}}
        <div class="col-xl-8 col-lg-8">


            {{-- ==================================================
                 PROFILE INFORMATION
            =================================================== --}}
            <div class="card profile-card">

                <div class="card-header profile-card-header">

                    <div class="d-flex align-items-center">

                        <div class="profile-section-icon profile-section-icon-primary">
                            <i class="bx bx-user"></i>
                        </div>

                        <div>
                            <h5 class="card-title mb-1">
                                Profile Information
                            </h5>

                            <p class="text-muted mb-0">
                                Update your name and email address.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="card-body profile-form-body">

                    @include(
                        'profile.partials.update-profile-information-form'
                    )

                </div>

            </div>


            {{-- ==================================================
                 PASSWORD
            =================================================== --}}
            <div class="card profile-card mt-4">

                <div class="card-header profile-card-header">

                    <div class="d-flex align-items-center">

                        <div class="profile-section-icon profile-section-icon-warning">
                            <i class="bx bx-lock-alt"></i>
                        </div>

                        <div>
                            <h5 class="card-title mb-1">
                                Update Password
                            </h5>

                            <p class="text-muted mb-0">
                                Keep your account secure with a strong password.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="card-body profile-form-body">

                    @include(
                        'profile.partials.update-password-form'
                    )

                </div>

            </div>


            {{-- ==================================================
                 DELETE ACCOUNT
            =================================================== --}}
            <div class="card profile-card profile-danger-card mt-4">

                <div class="card-header profile-card-header profile-danger-header">

                    <div class="d-flex align-items-center">

                        <div class="profile-section-icon profile-section-icon-danger">
                            <i class="bx bx-trash"></i>
                        </div>

                        <div>
                            <h5 class="card-title mb-1 text-danger">
                                Delete Account
                            </h5>

                            <p class="text-muted mb-0">
                                Permanently remove your account and its associated data.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="card-body profile-form-body">

                    @include(
                        'profile.partials.delete-user-form'
                    )

                </div>

            </div>


        </div>


        {{-- ======================================================
             RIGHT SIDEBAR
        ======================================================= --}}
        <div class="col-xl-4 col-lg-4">


            {{-- ==================================================
                 ACCOUNT SUMMARY
            =================================================== --}}
            <div class="card profile-sidebar-card">

                <div class="card-body">

                    <div class="profile-sidebar-title">
                        <i class="bx bx-id-card me-2"></i>
                        Account Overview
                    </div>

                    <div class="profile-summary text-center">

                        <img
                            src="{{ $avatarUrl }}"
                            alt="{{ $displayName }}"
                            class="profile-summary-avatar"
                            onerror="this.onerror=null;this.src='{{ $avatarFallback }}';"
                        >

                        <h5 class="mt-3 mb-1">
                            {{ $displayName }}
                        </h5>

                        <p class="text-muted mb-2">
                            {{ $email }}
                        </p>

                        <span class="badge profile-summary-role">
                            {{ $primaryRole }}
                        </span>

                    </div>


                    <div class="profile-divider"></div>


                    <div class="profile-details">

                        <div class="profile-detail-row">

                            <div class="profile-detail-label">
                                <i class="bx bx-user me-2"></i>
                                Username
                            </div>

                            <div class="profile-detail-value">
                                {{ $username }}
                            </div>

                        </div>


                        <div class="profile-detail-row">

                            <div class="profile-detail-label">
                                <i class="bx bx-envelope me-2"></i>
                                Email
                            </div>

                            <div
                                class="profile-detail-value text-truncate"
                                title="{{ $email }}"
                            >
                                {{ $email }}
                            </div>

                        </div>


                        <div class="profile-detail-row">

                            <div class="profile-detail-label">
                                <i class="bx bx-shield me-2"></i>
                                Role
                            </div>

                            <div class="profile-detail-value">
                                {{ $primaryRole }}
                            </div>

                        </div>


                        <div class="profile-detail-row">

                            <div class="profile-detail-label">
                                <i class="bx bx-building-house me-2"></i>
                                School
                            </div>

                            <div
                                class="profile-detail-value text-truncate"
                                title="{{ $schoolName }}"
                            >
                                {{ $schoolName }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ==================================================
                 QUICK ACTIONS
            =================================================== --}}
            <div class="card profile-sidebar-card mt-4">

                <div class="card-body">

                    <div class="profile-sidebar-title mb-3">
                        <i class="bx bx-grid-alt me-2"></i>
                        Quick Actions
                    </div>


                    <div class="profile-quick-actions">

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="profile-quick-action"
                        >
                            <span class="profile-quick-icon">
                                <i class="bx bx-home-circle"></i>
                            </span>

                            <span>
                                Dashboard
                            </span>

                            <i class="bx bx-chevron-right ms-auto"></i>
                        </a>


                        @if(
                            Route::has('admin.school.edit') &&
                            $profileUser?->school_id
                        )

                            <a
                                href="{{ route(
                                    'admin.school.edit',
                                    $profileUser->school_id
                                ) }}"
                                class="profile-quick-action"
                            >
                                <span class="profile-quick-icon">
                                    <i class="bx bx-building-house"></i>
                                </span>

                                <span>
                                    School Profile
                                </span>

                                <i class="bx bx-chevron-right ms-auto"></i>
                            </a>

                        @endif


                        @if(
                            $profileUser?->school_id &&
                            $profileUser?->can('settings.view') &&
                            Route::has('admin.school.settings.edit')
                        )

                            <a
                                href="{{ route(
                                    'admin.school.settings.edit',
                                    $profileUser->school_id
                                ) }}"
                                class="profile-quick-action"
                            >
                                <span class="profile-quick-icon">
                                    <i class="bx bx-cog"></i>
                                </span>

                                <span>
                                    School Settings
                                </span>

                                <i class="bx bx-chevron-right ms-auto"></i>
                            </a>

                        @endif


                        <form
                            id="profile-logout-form"
                            action="{{ route('logout') }}"
                            method="POST"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="profile-quick-action profile-logout-action w-100 border-0"
                            >
                                <span class="profile-quick-icon">
                                    <i class="bx bx-power-off"></i>
                                </span>

                                <span>
                                    Logout
                                </span>

                                <i class="bx bx-chevron-right ms-auto"></i>
                            </button>

                        </form>

                    </div>

                </div>

            </div>


            {{-- ==================================================
                 SECURITY NOTICE
            =================================================== --}}
            <div class="profile-security-box mt-4">

                <div class="d-flex align-items-start">

                    <div class="profile-security-icon">
                        <i class="bx bx-shield-quarter"></i>
                    </div>

                    <div>

                        <h6 class="mb-1">
                            Keep your account secure
                        </h6>

                        <p class="text-muted mb-0">
                            Never share your password or login credentials.
                            Use a unique password and update it regularly.
                        </p>

                    </div>

                </div>

            </div>


        </div>

    </div>

</div>


{{-- ==============================================================
     PAGE-SCOPED STYLES
=============================================================== --}}
<style>

    .profile-page {
        padding-bottom: 40px;
    }


    /* ----------------------------------------------------------
       Alerts
    ---------------------------------------------------------- */

    .profile-alert {
        border: 0;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(33, 37, 41, .05);
    }


    /* ----------------------------------------------------------
       Profile Hero
    ---------------------------------------------------------- */

    .profile-hero {
        overflow: hidden;
        border-radius: 14px;
        background:
            linear-gradient(
                135deg,
                #ffffff 0%,
                #f7f9ff 55%,
                #eef1ff 100%
            );
        box-shadow: 0 8px 28px rgba(50, 50, 93, .07);
    }

    .profile-hero-pattern {
        position: absolute;
        inset: 0 0 auto auto;
        width: 38%;
        height: 100%;
        opacity: .35;
        background:
            radial-gradient(
                circle at 20% 30%,
                rgba(85, 110, 230, .14) 0,
                rgba(85, 110, 230, 0) 48%
            ),
            radial-gradient(
                circle at 70% 70%,
                rgba(85, 110, 230, .10) 0,
                rgba(85, 110, 230, 0) 42%
            );
        pointer-events: none;
    }

    .profile-avatar-wrap {
        position: relative;
        display: inline-block;
    }

    .profile-avatar {
        width: 92px;
        height: 92px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 8px 24px rgba(31, 45, 61, .14);
        background: #f1f3f9;
    }

    .profile-online-dot {
        position: absolute;
        right: 5px;
        bottom: 7px;
        width: 15px;
        height: 15px;
        border: 3px solid #ffffff;
        border-radius: 50%;
        background: #34c38f;
    }

    .profile-name {
        color: #343a40;
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -.02em;
    }

    .profile-role-badge,
    .profile-summary-role {
        padding: 6px 11px;
        border-radius: 999px;
        color: #556ee6;
        background: rgba(85, 110, 230, .12);
        font-weight: 600;
    }

    .profile-email,
    .profile-school {
        color: #6c757d;
        font-size: 14px;
    }

    .profile-school-code {
        color: #9aa2ac;
    }

    .profile-action-btn {
        border-radius: 8px;
        padding: 9px 14px;
        font-weight: 600;
    }


    /* ----------------------------------------------------------
       Cards
    ---------------------------------------------------------- */

    .profile-card,
    .profile-sidebar-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 6px 24px rgba(31, 45, 61, .06);
    }

    .profile-card-header {
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #f0f1f4;
    }

    .profile-form-body {
        padding: 24px;
    }

    .profile-section-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        margin-right: 13px;
        border-radius: 12px;
        font-size: 21px;
    }

    .profile-section-icon-primary {
        color: #556ee6;
        background: rgba(85, 110, 230, .12);
    }

    .profile-section-icon-warning {
        color: #f1b44c;
        background: rgba(241, 180, 76, .15);
    }

    .profile-section-icon-danger {
        color: #f46a6a;
        background: rgba(244, 106, 106, .12);
    }


    /* ----------------------------------------------------------
       Sidebar
    ---------------------------------------------------------- */

    .profile-sidebar-title {
        display: flex;
        align-items: center;
        color: #343a40;
        font-size: 15px;
        font-weight: 700;
    }

    .profile-summary {
        padding-top: 8px;
    }

    .profile-summary-avatar {
        width: 104px;
        height: 104px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #f5f6fa;
        box-shadow: 0 7px 24px rgba(31, 45, 61, .10);
        background: #f1f3f9;
    }

    .profile-divider {
        height: 1px;
        margin: 24px 0;
        background: #eef0f3;
    }

    .profile-details {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .profile-detail-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
    }

    .profile-detail-label {
        flex: 0 0 auto;
        color: #74788d;
        font-size: 13px;
        font-weight: 500;
    }

    .profile-detail-value {
        min-width: 0;
        color: #343a40;
        font-size: 13px;
        font-weight: 600;
        text-align: right;
    }


    /* ----------------------------------------------------------
       Quick Actions
    ---------------------------------------------------------- */

    .profile-quick-actions {
        display: flex;
        flex-direction: column;
        gap: 9px;
    }

    .profile-quick-action {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 46px;
        padding: 9px 12px;
        border: 1px solid #eef0f4;
        border-radius: 9px;
        color: #343a40;
        background: #f8f9fb;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition:
            transform .15s ease,
            box-shadow .15s ease,
            background .15s ease;
    }

    .profile-quick-action:hover {
        color: #556ee6;
        background: #ffffff;
        box-shadow: 0 5px 18px rgba(31, 45, 61, .07);
        transform: translateY(-1px);
    }

    .profile-quick-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        color: #556ee6;
        background: rgba(85, 110, 230, .10);
        flex: 0 0 30px;
    }

    .profile-logout-action {
        color: #f46a6a;
        background: rgba(244, 106, 106, .06);
    }

    .profile-logout-action .profile-quick-icon {
        color: #f46a6a;
        background: rgba(244, 106, 106, .10);
    }

    .profile-logout-action:hover {
        color: #f46a6a;
        background: #fff5f5;
    }


    /* ----------------------------------------------------------
       Delete Account
    ---------------------------------------------------------- */

    .profile-danger-card {
        overflow: hidden;
        border: 1px solid rgba(244, 106, 106, .18);
    }

    .profile-danger-header {
        background: linear-gradient(
            90deg,
            rgba(244, 106, 106, .07),
            rgba(244, 106, 106, .02)
        );
    }


    /* ----------------------------------------------------------
       Existing Breeze partials
       Scoped so their functionality remains unchanged while
       controls visually match the Skote admin theme.
    ---------------------------------------------------------- */

    .profile-page .profile-form-body label {
        display: block;
        margin-bottom: 7px;
        color: #495057;
        font-size: 13px;
        font-weight: 600;
    }

    .profile-page .profile-form-body input[type="text"],
    .profile-page .profile-form-body input[type="email"],
    .profile-page .profile-form-body input[type="password"],
    .profile-page .profile-form-body input[type="file"],
    .profile-page .profile-form-body select,
    .profile-page .profile-form-body textarea {
        width: 100%;
        min-height: 42px;
        padding: 9px 12px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        color: #343a40;
        background: #ffffff;
        box-shadow: none;
        outline: none;
        font-size: 13px;
        transition:
            border-color .15s ease,
            box-shadow .15s ease;
    }

    .profile-page .profile-form-body textarea {
        min-height: 100px;
    }

    .profile-page .profile-form-body input:focus,
    .profile-page .profile-form-body select:focus,
    .profile-page .profile-form-body textarea:focus {
        border-color: #93a3ef;
        box-shadow: 0 0 0 3px rgba(85, 110, 230, .10);
    }

    .profile-page .profile-form-body button[type="submit"] {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 8px 17px;
        border: 0;
        border-radius: 7px;
        color: #ffffff;
        background: #556ee6;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .profile-page .profile-form-body button[type="submit"]:hover {
        background: #465bd4;
    }

    .profile-page .profile-form-body [type="button"] {
        border-radius: 7px;
    }

    .profile-page .profile-form-body .text-sm,
    .profile-page .profile-form-body .text-xs {
        color: #7a8088;
    }

    .profile-page .profile-form-body .underline {
        color: #556ee6;
    }

    .profile-page .profile-form-body .text-red-600,
    .profile-page .profile-form-body .text-danger {
        color: #f46a6a !important;
    }


    /* ----------------------------------------------------------
       Responsive
    ---------------------------------------------------------- */

    @media (max-width: 1199.98px) {

        .profile-name {
            font-size: 21px;
        }

    }

    @media (max-width: 767.98px) {

        .profile-form-body,
        .profile-card-header {
            padding: 18px;
        }

        .profile-name {
            font-size: 19px;
        }

        .profile-detail-row {
            flex-direction: column;
            gap: 4px;
        }

        .profile-detail-value {
            width: 100%;
            text-align: left;
        }

    }

</style>

@endsection
