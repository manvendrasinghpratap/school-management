<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Bed Number *</label>
        <input name="bed_number" class="form-control" required value="{{ old('bed_number', $bed->bed_number ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select">
            @foreach(['available','occupied','maintenance','inactive'] as $status)
                <option value="{{ $status }}" @selected(old('status', $bed->status ?? 'available') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
</div>
