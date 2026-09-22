<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Hostel Allocation *</label>
        <select name="hostel_allocation_id" class="form-select" required>
            <option value="">Select allocation</option>
            @foreach($allocations as $allocation)
                <option value="{{ $allocation->id }}" @selected(old('hostel_allocation_id', $fee->hostel_allocation_id ?? '') == $allocation->id)>
                    {{ trim(($allocation->student->first_name ?? '').' '.($allocation->student->last_name ?? '')) }}
                    — {{ $allocation->hostel->name ?? 'Hostel' }}
                    / {{ $allocation->room->room_number ?? 'Room' }}
                </option>
            @endforeach
        </select>
        @error('hostel_allocation_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Fee Month *</label>
        <input type="date" name="fee_month" class="form-control" required value="{{ old('fee_month', isset($fee) && $fee->fee_month ? $fee->fee_month->format('Y-m-d') : now()->format('Y-m-d')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Amount *</label>
        <input type="number" name="amount" class="form-control" min="0.01" step="0.01" required value="{{ old('amount', $fee->amount ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select">
            @foreach(['pending','invoiced','paid','waived'] as $status)
                <option value="{{ $status }}" @selected(old('status', $fee->status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Invoice ID</label>
        <input type="number" name="invoice_id" class="form-control" min="1" value="{{ old('invoice_id', $fee->invoice_id ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $fee->notes ?? '') }}</textarea>
    </div>
</div>
