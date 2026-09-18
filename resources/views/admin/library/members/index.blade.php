@extends('backend.layout.default')

@section('title', 'Library Members')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Library Members</h4>
            <p class="text-muted mb-0">
                Manage students and staff registered as library members.
            </p>
        </div>

        <a href="{{ route('admin.library.members.create') }}"
           class="btn btn-primary">
            <i class="bx bx-user-plus me-1"></i>
            Add Member
        </a>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-2">
                Please correct the following:
            </div>

            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET"
                  action="{{ route('admin.library.members.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-5">
                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Member number, student or staff name">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            Holder Type
                        </label>

                        <select name="holder_type"
                                class="form-select">
                            <option value="">All Holders</option>

                            <option value="student"
                                @selected(request('holder_type') === 'student')>
                                Student
                            </option>

                            <option value="staff"
                                @selected(request('holder_type') === 'staff')>
                                Staff
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">
                            <option value="">All Statuses</option>

                            @foreach([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'suspended' => 'Suspended',
                                'expired' => 'Expired',
                            ] as $value => $label)
                                <option value="{{ $value }}"
                                    @selected(request('status') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bx bx-search"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.library.members.index') }}"
                           class="btn btn-light">
                            Reset
                        </a>
                    </div>

                </div>

            </form>
        </div>
    </div>

    {{-- Members --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                Library Members
            </h5>

            <span class="text-muted small">
                {{ $members->total() }} member(s)
            </span>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Member No.</th>
                        <th>Holder</th>
                        <th>Type</th>
                        <th>Joined</th>
                        <th>Expiry</th>
                        <th>Max Books</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($members as $member)

                        @php
                            $holderType = $member->student_id
                                ? 'Student'
                                : ($member->staff_id ? 'Staff' : '—');

                            $holderName = '—';

                            if ($member->student) {
                                $holderName = trim(
                                    $member->student->first_name . ' ' .
                                    ($member->student->middle_name ?? '') . ' ' .
                                    $member->student->last_name
                                );
                            } elseif ($member->staff) {
                                $holderName = trim(
                                    $member->staff->first_name . ' ' .
                                    ($member->staff->middle_name ?? '') . ' ' .
                                    $member->staff->last_name
                                );
                            }

                            $statusClass = match($member->status) {
                                'active' => 'success',
                                'inactive' => 'secondary',
                                'suspended' => 'warning',
                                'expired' => 'danger',
                                default => 'secondary',
                            };
                        @endphp

                        <tr>

                            <td>
                                {{ $members->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <span class="fw-semibold">
                                    {{ $member->member_number }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $holderName }}
                                </div>
                            </td>

                            <td>
                                @if($holderType === 'Student')
                                    <span class="badge bg-info-subtle text-info">
                                        Student
                                    </span>
                                @elseif($holderType === 'Staff')
                                    <span class="badge bg-primary-subtle text-primary">
                                        Staff
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                {{ optional($member->joined_at)->format('d M Y') ?? '—' }}
                            </td>

                            <td>
                                {{ optional($member->expiry_date)->format('d M Y') ?? '—' }}
                            </td>

                            <td>
                                {{ $member->max_books }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $statusClass }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>

                            <td class="text-end">

                                @can('library.members.manage')

                                    <a href="{{ route('admin.library.members.edit', $member) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-edit-alt"></i>
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.library.members.destroy', $member) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this library member?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="bx bx-trash"></i>
                                            Delete
                                        </button>

                                    </form>

                                @endcan

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9"
                                class="text-center py-5">

                                <div class="text-muted">
                                    <i class="bx bx-group fs-1 d-block mb-2"></i>
                                    No library members found.
                                </div>

                                <a href="{{ route('admin.library.members.create') }}"
                                   class="btn btn-primary mt-3">
                                    Add First Member
                                </a>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($members->hasPages())
            <div class="card-footer">
                {{ $members->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
