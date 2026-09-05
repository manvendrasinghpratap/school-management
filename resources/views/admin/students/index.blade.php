@extends('backend.layout.default')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Students</h1>
            <p class="text-muted mb-0">
                Manage registered students
            </p>
        </div>

        <a href="{{ route('admin.students.create') }}"
           class="btn btn-primary">
            + Add Student
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search & Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.students.index') }}">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Student number, name, admission number, phone..."
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'active',
                                'inactive',
                                'graduated',
                                'transferred',
                                'withdrawn'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(request('status') === $status)
                                >
                                    {{ ucfirst($status) }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">
                            Search
                        </button>

                        <a href="{{ route('admin.students.index') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Students Table --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <strong>
                Student Records
            </strong>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Photo</th>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Admission No.</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Admission Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($students as $student)

                            <tr>

                                {{-- Photo --}}
                                <td>

                                    @if($student->photo)

                                        <img
                                            src="{{ asset('storage/' . $student->photo) }}"
                                            alt="{{ $student->full_name }}"
                                            width="45"
                                            height="45"
                                            class="rounded-circle object-fit-cover"
                                        >

                                    @else

                                        <div
                                            class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                            style="width:45px;height:45px;"
                                        >
                                            {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                        </div>

                                    @endif

                                </td>

                                {{-- Student Number --}}
                                <td>
                                    <strong>
                                        {{ $student->student_number }}
                                    </strong>
                                </td>

                                {{-- Name --}}
                                <td>
                                    <a
                                        href="{{ route('admin.students.show', $student) }}"
                                        class="text-decoration-none fw-semibold"
                                    >
                                        {{ $student->full_name }}
                                    </a>
                                </td>

                                {{-- Admission Number --}}
                                <td>
                                    {{ $student->admission_number ?: '—' }}
                                </td>

                                {{-- Gender --}}
                                <td>
                                    {{ $student->gender
                                        ? ucfirst($student->gender)
                                        : '—' }}
                                </td>

                                {{-- Phone --}}
                                <td>
                                    {{ $student->phone ?: '—' }}
                                </td>

                                {{-- Admission Date --}}
                                <td>
                                    {{ $student->admission_date?->format('d M Y') }}
                                </td>

                                {{-- Status --}}
                                <td>

                                    @php
                                        $statusClass = match($student->status) {
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            'graduated' => 'primary',
                                            'transferred' => 'warning',
                                            'withdrawn' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($student->status) }}
                                    </span>

                                </td>

                                {{-- Actions --}}
                                <td class="text-end">

                                    <div class="btn-group">

                                        <a
                                            href="{{ route('admin.students.show', $student) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                        <a
                                            href="{{ route('admin.students.edit', $student) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.students.destroy', $student) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this student?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <h5>
                                            No students found
                                        </h5>

                                        <p class="mb-3">
                                            There are currently no student records matching your search.
                                        </p>

                                        <a
                                            href="{{ route('admin.students.create') }}"
                                            class="btn btn-primary"
                                        >
                                            Add First Student
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Pagination --}}
        @if($students->hasPages())

            <div class="card-footer bg-white">

                {{ $students->links() }}

            </div>

        @endif

    </div>

</div>
@endsection