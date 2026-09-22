<?php

namespace App\Http\Requests;

use App\Models\HostelAllocation;
use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHostelFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (bool) auth()->user()?->school_id;
    }

    public function rules(): array
    {
        $schoolId = (int) auth()->user()->school_id;

        return [
            'hostel_allocation_id' => [
                'required',
                'integer',
                Rule::exists('hostel_allocations', 'id')->where(
                    fn ($q) => $q
                        ->where('school_id', $schoolId)
                        ->whereIn('status', ['allocated', 'checked_in'])
                        ->whereNull('deleted_at')
                ),
            ],
            'fee_month' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999999.99'],
            'status' => [
                'required',
                Rule::in(['pending', 'invoiced', 'paid', 'waived']),
            ],
            'invoice_id' => [
                'nullable',
                'integer',
                Rule::exists('invoices', 'id')->where(
                    fn ($q) => $q
                        ->where('school_id', $schoolId)
                        ->whereNull('deleted_at')
                ),
            ],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $schoolId = (int) auth()->user()->school_id;

            $allocation = HostelAllocation::query()
                ->where('id', $this->input('hostel_allocation_id'))
                ->where('school_id', $schoolId)
                ->whereNull('deleted_at')
                ->first();

            if (! $allocation) {
                $validator->errors()->add(
                    'hostel_allocation_id',
                    'The selected hostel allocation was not found.'
                );

                return;
            }

            $exists = \App\Models\HostelFee::query()
                ->where('school_id', $schoolId)
                ->where('hostel_allocation_id', $allocation->id)
                ->whereDate('fee_month', $this->input('fee_month'))
                ->whereNull('deleted_at')
                ->exists();

            $ignoreId = $this->route('fee')?->id;

            if ($ignoreId) {
                $exists = \App\Models\HostelFee::query()
                    ->where('school_id', $schoolId)
                    ->where('hostel_allocation_id', $allocation->id)
                    ->whereDate('fee_month', $this->input('fee_month'))
                    ->whereNull('deleted_at')
                    ->where('id', '!=', $ignoreId)
                    ->exists();
            }

            if ($exists) {
                $validator->errors()->add(
                    'fee_month',
                    'A hostel fee already exists for this allocation and month.'
                );
            }

            if ($this->filled('invoice_id')) {
                $invoice = Invoice::query()
                    ->where('id', (int) $this->input('invoice_id'))
                    ->where('school_id', $schoolId)
                    ->whereNull('deleted_at')
                    ->first();

                if (! $invoice) {
                    $validator->errors()->add(
                        'invoice_id',
                        'The selected invoice does not belong to this school.'
                    );
                }
            }
        });
    }
}
