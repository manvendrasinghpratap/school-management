<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'School Portal' }} | {{ auth()->user()->school?->name ?? 'School' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('portal.index') }}">School Portal</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#portalNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="portalNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if(auth()->user()->hasRole('Student'))
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.student.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.student.attendance') }}">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.student.timetable') }}">Timetable</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.student.courses') }}">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.student.results') }}">Results</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.student.fees') }}">Fees</a></li>
                @elseif(auth()->user()->hasRole('Parent'))
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.parent.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.parent.children') }}">Children</a></li>
                @elseif(auth()->user()->hasRole('Teacher'))
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.teacher.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.teacher.courses') }}">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.teacher.students') }}">Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.teacher.attendance') }}">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.teacher.marks') }}">Marks</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('portal.teacher.timetable') }}">Timetable</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="{{ route('portal.notifications.index') }}">Notifications</a></li>
            </ul>
            <div class="d-flex gap-2 align-items-center text-white">
                <span>{{ auth()->user()->name }}</span>
                <a class="btn btn-outline-light btn-sm" href="{{ route('portal.profile.edit') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-danger btn-sm">Logout</button></form>
            </div>
        </div>
    </div>
</nav>
<main class="container py-4">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
    @yield('content')
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
