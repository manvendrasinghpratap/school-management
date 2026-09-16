@extends('backend.layout.default')

@section('title', 'Reports & Analytics')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Reports & Analytics</h4>
            <p class="text-muted mb-0">Central reporting for students, academics, attendance, examinations, staff and finance.</p>
        </div>
        <div class="text-muted small mt-2 mt-md-0">
            @if($currentAcademicYear)
                Academic Year: <strong>{{ $currentAcademicYear->name }}</strong>
                @if($currentTerm) · Term: <strong>{{ $currentTerm->name }}</strong> @endif
            @endif
        </div>
    </div>

    <div class="row">
        @foreach([
            ['Students','bx-user','primary',$statistics['students'],$statistics['active_students'].' active','admin.reports.students'],
            ['Staff','bx-group','success',$statistics['staff'],'total staff','admin.reports.staff'],
            ['Classes','bx-book-content','info',$statistics['classes'],'classes','admin.reports.enrollment'],
            ['Sections','bx-grid-alt','warning',$statistics['sections'],'sections','admin.reports.enrollment'],
            ['Attendance Records','bx-calendar-check','secondary',$statistics['attendance_records'],'records','admin.reports.attendance'],
            ['Examinations','bx-edit','danger',$statistics['examinations'],'examinations','admin.reports.academic-performance'],
        ] as $card)
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">{{ $card[0] }}</p>
                                <h4 class="mb-1">{{ number_format($card[3]) }}</h4>
                                <small class="text-muted">{{ $card[4] }}</small>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title rounded bg-{{ $card[2] }}-subtle text-{{ $card[2] }} font-size-20">
                                    <i class="bx {{ $card[1] }}"></i>
                                </span>
                            </div>
                        </div>
                        <a href="{{ route($card[5]) }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mt-2">
        <div class="col-xl-6">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent"><h5 class="mb-0">Student Demographics</h5></div>
                <div class="card-body">
                    @forelse($genderSummary as $row)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ ucfirst($row->gender ?? 'Not specified') }}</span>
                            <strong>{{ number_format($row->total) }}</strong>
                        </div>
                    @empty
                        <div class="text-muted">No student data available.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent"><h5 class="mb-0">Attendance Summary</h5></div>
                <div class="card-body">
                    @forelse($attendanceSummary as $row)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ ucfirst($row->status) }}</span>
                            <strong>{{ number_format($row->total) }}</strong>
                        </div>
                    @empty
                        <div class="text-muted">No attendance data available.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-header bg-transparent"><h5 class="mb-0">Report Catalogue</h5></div>
        <div class="card-body">
            <div class="row g-3">
                @foreach([
                    ['Student Reports','Student demographic, status and registration information.','bx-user','primary','admin.reports.students'],
                    ['Enrollment Reports','Academic year, term, class and section enrollment history.','bx-book-reader','info','admin.reports.enrollment'],
                    ['Academic Performance','Results, averages, grades, positions and grade distribution.','bx-bar-chart-alt-2','success','admin.reports.academic-performance'],
                    ['Attendance Reports','Student attendance by date, class, section and status.','bx-calendar-check','warning','admin.reports.attendance'],
                    ['Staff Reports','Staff attendance by date, employee and attendance status.','bx-group','secondary','admin.reports.staff'],
                ] as $report)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route($report[4]) }}" class="text-reset">
                            <div class="border rounded p-3 h-100 report-card">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="avatar-sm me-3"><span class="avatar-title rounded bg-{{ $report[3] }}-subtle text-{{ $report[3] }}"><i class="bx {{ $report[2] }} font-size-20"></i></span></span>
                                    <h6 class="mb-0">{{ $report[0] }}</h6>
                                </div>
                                <p class="text-muted small mb-0">{{ $report[1] }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach

                @if(auth()->user()?->can('payments.view') || auth()->user()?->can('invoices.view') || auth()->user()?->can('payment-refunds.view'))
                    <div class="col-lg-4 col-md-6">
                        <div class="border rounded p-3 h-100 report-card">
                            <div class="d-flex align-items-center mb-2"><span class="avatar-sm me-3"><span class="avatar-title rounded bg-success-subtle text-success"><i class="bx bx-wallet font-size-20"></i></span></span><h6 class="mb-0">Finance Reports</h6></div>
                            <div class="d-flex flex-wrap gap-2">
                                @can('payments.view')<a class="btn btn-sm btn-outline-primary" href="{{ route('admin.finance-reports.collection') }}">Collection</a>@endcan
                                @can('invoices.view')<a class="btn btn-sm btn-outline-primary" href="{{ route('admin.finance-reports.outstanding') }}">Outstanding</a>@endcan
                                @can('payments.view')<a class="btn btn-sm btn-outline-primary" href="{{ route('admin.finance-reports.payments') }}">Payments</a>@endcan
                                @can('payment-refunds.view')<a class="btn btn-sm btn-outline-primary" href="{{ route('admin.finance-reports.refunds') }}">Refunds</a>@endcan
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.report-card { transition: .15s ease-in-out; }
.report-card:hover { transform: translateY(-2px); box-shadow: 0 .35rem 1rem rgba(0,0,0,.08); }
</style>
@endsection
