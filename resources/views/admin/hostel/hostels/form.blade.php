<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Name *</label>
        <input name="name" class="form-control" required value="{{ old('name', $hostel->name ?? '') }}">
        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Code</label>
        <input name="code" class="form-control" value="{{ old('code', $hostel->code ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Type *</label>
        <select name="hostel_type" class="form-select" required>
            @foreach(['boys'=>'Boys','girls'=>'Girls','mixed'=>'Mixed','staff'=>'Staff'] as $v=>$l)
                <option value="{{ $v }}" @selected(old('hostel_type', $hostel->hostel_type ?? 'mixed') === $v)>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Warden</label>
        <select name="warden_staff_id" class="form-select">
            <option value="">Select warden</option>
            @foreach($staff as $member)
                <option value="{{ $member->id }}" @selected(old('warden_staff_id', $hostel->warden_staff_id ?? '') == $member->id)>
                    {{ trim($member->first_name.' '.($member->middle_name ?? '').' '.$member->last_name) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Capacity *</label>
        <input type="number" name="capacity" min="0" class="form-control" required value="{{ old('capacity', $hostel->capacity ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Monthly Fee *</label>
        <input type="number" name="monthly_fee" min="0" step="0.01" class="form-control" required value="{{ old('monthly_fee', $hostel->monthly_fee ?? 0) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="2">{{ old('address', $hostel->address ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $hostel->description ?? '') }}</textarea>
    </div>
    <div class="col-md-3">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            <option value="active" @selected(old('status', $hostel->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $hostel->status ?? '') === 'inactive')>Inactive</option>
        </select>
    </div>
</div>
