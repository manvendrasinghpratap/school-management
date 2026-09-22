<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label">Room Number *</label>
        <input name="room_number" class="form-control" required value="{{ old('room_number', $room->room_number ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Floor</label>
        <input name="floor" class="form-control" value="{{ old('floor', $room->floor ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Room Type</label>
        <input name="room_type" class="form-control" value="{{ old('room_type', $room->room_type ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Capacity *</label>
        <input type="number" name="capacity" min="1" class="form-control" required value="{{ old('capacity', $room->capacity ?? 1) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Monthly Fee *</label>
        <input type="number" name="monthly_fee" min="0" step="0.01" class="form-control" required value="{{ old('monthly_fee', $room->monthly_fee ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select">
            @foreach(['available','full','maintenance','inactive'] as $status)
                <option value="{{ $status }}" @selected(old('status', $room->status ?? 'available') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
</div>
