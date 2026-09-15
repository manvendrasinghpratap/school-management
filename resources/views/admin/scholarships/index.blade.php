@extends('backend.layout.default')
@section('content')
<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <div><h4 class="mb-sm-0">Scholarships</h4></div>
        <div class="page-title-right"><ol class="breadcrumb m-0"><li class="breadcrumb-item">Finance</li><li class="breadcrumb-item active">Scholarships</li></ol></div>
    </div>

    @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div><h5 class="card-title mb-1">Scholarship Management</h5><p class="text-muted mb-0">Manage fixed and percentage-based scholarships used for student fee concessions.</p></div>
            @can('fees.manage')<a href="{{ route('admin.scholarships.create') }}" class="btn btn-primary"><i class="ri-add-line me-1"></i> Add Scholarship</a>@endcan
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-5"><label class="form-label">Search</label><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Scholarship name or description"></div>
                <div class="col-md-2"><label class="form-label">Type</label><select name="type" class="form-select"><option value="">All</option><option value="percentage" @selected(request('type')==='percentage')>Percentage</option><option value="fixed" @selected(request('type')==='fixed')>Fixed</option></select></div>
                <div class="col-md-2"><label class="form-label">Status</label><select name="status" class="form-select"><option value="">All</option><option value="active" @selected(request('status')==='active')>Active</option><option value="inactive" @selected(request('status')==='inactive')>Inactive</option></select></div>
                <div class="col-md-3 d-flex align-items-end gap-2"><button class="btn btn-secondary">Filter</button><a href="{{ route('admin.scholarships.index') }}" class="btn btn-light">Reset</a></div>
            </form>
            <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>#</th><th>Name</th><th>Type</th><th>Value</th><th>Assigned Fees</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
            @forelse($scholarships as $scholarship)
            <tr><td>{{ $scholarships->firstItem()+$loop->index }}</td><td><a href="{{ route('admin.scholarships.show',$scholarship) }}" class="fw-semibold">{{ $scholarship->name }}</a><div class="text-muted small">{{ Str::limit($scholarship->description,60) }}</div></td><td><span class="badge bg-info-subtle text-info">{{ ucfirst($scholarship->type) }}</span></td><td class="fw-semibold">{{ $scholarship->formatted_value }}</td><td>{{ $scholarship->student_fees_count }}</td><td>{!! $scholarship->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' !!}</td><td class="text-end"><div class="d-inline-flex gap-1"><a href="{{ route('admin.scholarships.show',$scholarship) }}" class="btn btn-sm btn-soft-info">View</a>@can('fees.manage')<a href="{{ route('admin.scholarships.edit',$scholarship) }}" class="btn btn-sm btn-soft-primary">Edit</a><form method="POST" action="{{ route('admin.scholarships.toggle-status',$scholarship) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-soft-warning">{{ $scholarship->is_active?'Deactivate':'Activate' }}</button></form>@if($scholarship->student_fees_count===0)<form method="POST" action="{{ route('admin.scholarships.destroy',$scholarship) }}" onsubmit="return confirm('Delete this scholarship?')">@csrf @method('DELETE')<button class="btn btn-sm btn-soft-danger">Delete</button></form>@endif @endcan</div></td></tr>
            @empty<tr><td colspan="7" class="text-center text-muted py-5">No scholarships found.</td></tr>@endforelse
            </tbody></table></div>
            <div class="mt-3">{{ $scholarships->links() }}</div>
        </div>
    </div>
</div>
@endsection
