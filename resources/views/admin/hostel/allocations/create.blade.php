@extends('backend.layout.default')

@section('content')
<div class="container-fluid">
    <h4>Allocate Student to Hostel</h4>
    @include('admin.hostel._flash')

    <form method="POST" action="{{ route('admin.hostel.allocations.store') }}">
        @csrf

        <div class="mb-3">
            <x-academic-hierarchy
                :academic-years="$academicYears"
                :current-academic-year="$currentAcademicYear"
                :academic-year-id="old('academic_year_id', $currentAcademicYear?->id)"
                :class-id="old('class_id')"
                :section-id="old('section_id')"
                :student-id="old('student_id')"
            />
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Hostel *</label>
                <select name="hostel_id" id="hostel_id" class="form-select" required>
                    <option value="">Select hostel</option>
                    @foreach($hostels as $hostel)
                        <option value="{{ $hostel->id }}" @selected(old('hostel_id') == $hostel->id)>{{ $hostel->name }}</option>
                    @endforeach
                </select>
                @error('hostel_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Room *</label>
                <select name="room_id" id="hostel_room_id" class="form-select" required disabled>
                    <option value="">Select hostel first</option>
                </select>
                @error('room_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Bed</label>
                <select name="bed_id" id="hostel_bed_id" class="form-select" disabled>
                    <option value="">No specific bed</option>
                </select>
                @error('bed_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Start Date *</label>
                <input type="date" name="start_date" class="form-control" required value="{{ old('start_date', now()->format('Y-m-d')) }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Monthly Fee</label>
                <input type="number" name="monthly_fee" min="0" step="0.01" class="form-control" value="{{ old('monthly_fee') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select">
                    <option value="allocated" @selected(old('status','allocated') === 'allocated')>Allocated</option>
                    <option value="checked_in" @selected(old('status') === 'checked_in')>Checked In</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>
        </div>

        <button class="btn btn-primary mt-3">Allocate Student</button>
        <a href="{{ route('admin.hostel.allocations.index') }}" class="btn btn-light mt-3">Cancel</a>
    </form>
</div>
<script src="{{ asset('assets/js/academic-hierarchy.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hostel = document.getElementById('hostel_id');
    const room = document.getElementById('hostel_room_id');
    const bed = document.getElementById('hostel_bed_id');

    const roomUrl = @json(route('admin.hostel.ajax.rooms'));
    const bedUrl = @json(route('admin.hostel.ajax.beds'));

    async function loadRooms() {
        room.innerHTML = '<option value="">Loading rooms...</option>';
        room.disabled = true;
        bed.innerHTML = '<option value="">Select room first</option>';
        bed.disabled = true;

        if (!hostel.value) return;

        const url = new URL(roomUrl, window.location.origin);
        url.searchParams.set('hostel_id', hostel.value);

        const response = await fetch(url, {
            headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
        });
        const payload = await response.json();

        room.innerHTML = '<option value="">Select room</option>';

        (payload.data || []).forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.room_number} — Capacity ${item.capacity} — ${item.status}`;
            option.dataset.monthlyFee = item.monthly_fee || '';
            room.appendChild(option);
        });

        room.disabled = false;
    }

    async function loadBeds() {
        bed.innerHTML = '<option value="">Loading beds...</option>';
        bed.disabled = true;

        if (!room.value) {
            bed.innerHTML = '<option value="">No specific bed</option>';
            return;
        }

        const url = new URL(bedUrl, window.location.origin);
        url.searchParams.set('room_id', room.value);

        const response = await fetch(url, {
            headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
        });
        const payload = await response.json();

        bed.innerHTML = '<option value="">No specific bed</option>';

        (payload.data || []).forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.bed_number} — ${item.status}`;
            bed.appendChild(option);
        });

        bed.disabled = false;
    }

    hostel.addEventListener('change', loadRooms);
    room.addEventListener('change', loadBeds);

    if (hostel.value) {
        loadRooms();
    }
});
</script>
@endsection
