<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeInstallment;
use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FeeInstallmentController extends Controller
{
    /**
     * Display installments for a fee structure.
     */
    public function index(Request $request)
{
    $user = Auth::user();

    if (!$user || !$user->school_id) {
        abort(403, 'No school is assigned to the current user.');
    }

    $schoolId = $user->school_id;

    $query = FeeInstallment::with([
        'feeStructure.feeCategory',
        'feeStructure.academicYear',
        'feeStructure.classModel',
        'feeStructure.term',
    ])
        ->where('school_id', $schoolId);

    /*
    |--------------------------------------------------------------------------
    | Filter: Fee Structure
    |--------------------------------------------------------------------------
    */
    if ($request->filled('fee_structure_id')) {
        $query->where('fee_structure_id', $request->fee_structure_id);
    }

    /*
    |--------------------------------------------------------------------------
    | Filter: Status
    |--------------------------------------------------------------------------
    */
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */
    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where('name', 'like', '%' . $search . '%');
    }

    /*
    |--------------------------------------------------------------------------
    | Installments
    |--------------------------------------------------------------------------
    */
    $feeInstallments = $query
        ->orderBy('fee_structure_id')
        ->orderBy('installment_number')
        ->paginate(15)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Fee Structures for filter
    |--------------------------------------------------------------------------
    */
    $feeStructures = FeeStructure::with([
        'feeCategory',
        'academicYear',
        'classModel',
        'term',
    ])
        ->where('school_id', $schoolId)
        ->orderByDesc('id')
        ->get();

    return view('admin.fee-installments.index', compact(
        'feeInstallments',
        'feeStructures'
    ));
}


    /**
     * Show create form.
     */
    public function create(Request $request)
    {
        abort_unless(
            Auth::user()->can('fee-installments.manage'),
            403
        );

        $user = Auth::user();

        $feeStructures = FeeStructure::query()
            ->where('school_id', $user->school_id)
            ->where('is_active', true)
            ->with([
                'feeCategory',
                'academicYear',
                'classModel',
                'term',
            ])
            ->orderByDesc('id')
            ->get();

        $selectedFeeStructureId =
            $request->get('fee_structure_id');

        return view(
            'admin.fee-installments.create',
            compact(
                'feeStructures',
                'selectedFeeStructureId'
            )
        );
    }


    /**
     * Store a new installment.
     */
    public function store(Request $request)
    {
        abort_unless(
            Auth::user()->can('fee-installments.manage'),
            403
        );

        $user = Auth::user();

        $validated = $request->validate([
            'fee_structure_id' => [
                'required',
                'integer',
                'exists:fee_structures,id',
            ],

            'installment_number' => [
                'required',
                'integer',
                'min:1',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'due_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'paid',
                    'partial',
                    'overdue',
                    'cancelled',
                ]),
            ],
        ]);

        /*
         * Get fee structure with school isolation.
         */
        $feeStructure = FeeStructure::query()
            ->where('id', $validated['fee_structure_id'])
            ->where('school_id', $user->school_id)
            ->first();

        if (!$feeStructure) {

            return back()
                ->withInput()
                ->withErrors([
                    'fee_structure_id' =>
                        'The selected fee structure does not belong to your school.',
                ]);
        }

        /*
         * Prevent duplicate installment number.
         */
        $duplicateNumber = FeeInstallment::query()
            ->where('school_id', $user->school_id)
            ->where(
                'fee_structure_id',
                $feeStructure->id
            )
            ->where(
                'installment_number',
                $validated['installment_number']
            )
            ->exists();

        if ($duplicateNumber) {

            return back()
                ->withInput()
                ->withErrors([
                    'installment_number' =>
                        'This installment number already exists for the selected fee structure.',
                ]);
        }

        /*
         * Calculate existing installment total.
         */
        $existingTotal = FeeInstallment::query()
            ->where('school_id', $user->school_id)
            ->where(
                'fee_structure_id',
                $feeStructure->id
            )
            ->whereNull('deleted_at')
            ->sum('amount');

        $newTotal =
            (float) $existingTotal +
            (float) $validated['amount'];

        /*
         * Do not allow installment total to exceed
         * the parent fee structure amount.
         */
        if (
            $newTotal >
            (float) $feeStructure->amount
        ) {

            $remaining =
                (float) $feeStructure->amount -
                (float) $existingTotal;

            return back()
                ->withInput()
                ->withErrors([
                    'amount' =>
                        'The installment amount exceeds the remaining amount of ' .
                        number_format($remaining, 2) .
                        ' available for this fee structure.',
                ]);
        }

        /*
         * Create installment.
         */
        FeeInstallment::create([
            'school_id' =>
                $user->school_id,

            'fee_structure_id' =>
                $feeStructure->id,

            'installment_number' =>
                $validated['installment_number'],

            'name' =>
                $validated['name'],

            'amount' =>
                $validated['amount'],

            'due_date' =>
                $validated['due_date'],

            'status' =>
                $validated['status'],
        ]);

        return redirect()
            ->route(
                'admin.fee-installments.index',
                [
                    'fee_structure_id' =>
                        $feeStructure->id,
                ]
            )
            ->with(
                'success',
                'Fee installment created successfully.'
            );
    }


    /**
     * Display installment.
     */
    public function show(FeeInstallment $feeInstallment)
    {
        abort_unless(
            Auth::user()->can('fee-installments.view') ||
            Auth::user()->can('fee-installments.manage'),
            403
        );

        $user = Auth::user();

        if (
            $feeInstallment->school_id !=
            $user->school_id
        ) {
            abort(404);
        }

        $feeInstallment->load([
            'feeStructure.feeCategory',
            'feeStructure.academicYear',
            'feeStructure.classModel',
            'feeStructure.term',
        ]);

        return view(
            'admin.fee-installments.show',
            compact('feeInstallment')
        );
    }


    /**
     * Show edit form.
     */
    public function edit(FeeInstallment $feeInstallment)
    {
        abort_unless(
            Auth::user()->can('fee-installments.manage'),
            403
        );

        $user = Auth::user();

        if (
            $feeInstallment->school_id !=
            $user->school_id
        ) {
            abort(404);
        }

        $feeStructures = FeeStructure::query()
            ->where('school_id', $user->school_id)
            ->where('is_active', true)
            ->with([
                'feeCategory',
                'academicYear',
                'classModel',
                'term',
            ])
            ->orderByDesc('id')
            ->get();

        return view(
            'admin.fee-installments.edit',
            compact(
                'feeInstallment',
                'feeStructures'
            )
        );
    }


    /**
     * Update installment.
     */
    public function update(
        Request $request,
        FeeInstallment $feeInstallment
    ) {

        abort_unless(
            Auth::user()->can('fee-installments.manage'),
            403
        );

        $user = Auth::user();

        if (
            $feeInstallment->school_id !=
            $user->school_id
        ) {
            abort(404);
        }

        $validated = $request->validate([
            'fee_structure_id' => [
                'required',
                'integer',
                'exists:fee_structures,id',
            ],

            'installment_number' => [
                'required',
                'integer',
                'min:1',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'due_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'paid',
                    'partial',
                    'overdue',
                    'cancelled',
                ]),
            ],
        ]);

        /*
         * Verify target fee structure belongs
         * to current school.
         */
        $feeStructure = FeeStructure::query()
            ->where(
                'id',
                $validated['fee_structure_id']
            )
            ->where(
                'school_id',
                $user->school_id
            )
            ->first();

        if (!$feeStructure) {

            return back()
                ->withInput()
                ->withErrors([
                    'fee_structure_id' =>
                        'The selected fee structure does not belong to your school.',
                ]);
        }

        /*
         * Duplicate installment number.
         */
        $duplicateNumber = FeeInstallment::query()
            ->where(
                'school_id',
                $user->school_id
            )
            ->where(
                'fee_structure_id',
                $feeStructure->id
            )
            ->where(
                'installment_number',
                $validated['installment_number']
            )
            ->where(
                'id',
                '!=',
                $feeInstallment->id
            )
            ->exists();

        if ($duplicateNumber) {

            return back()
                ->withInput()
                ->withErrors([
                    'installment_number' =>
                        'This installment number already exists for the selected fee structure.',
                ]);
        }

        /*
         * Existing total excluding current installment.
         */
        $existingTotal = FeeInstallment::query()
            ->where(
                'school_id',
                $user->school_id
            )
            ->where(
                'fee_structure_id',
                $feeStructure->id
            )
            ->where(
                'id',
                '!=',
                $feeInstallment->id
            )
            ->whereNull('deleted_at')
            ->sum('amount');

        $newTotal =
            (float) $existingTotal +
            (float) $validated['amount'];

        if (
            $newTotal >
            (float) $feeStructure->amount
        ) {

            $remaining =
                (float) $feeStructure->amount -
                (float) $existingTotal;

            return back()
                ->withInput()
                ->withErrors([
                    'amount' =>
                        'The installment amount exceeds the remaining amount of ' .
                        number_format($remaining, 2) .
                        ' available for this fee structure.',
                ]);
        }

        $feeInstallment->update([
            'fee_structure_id' =>
                $feeStructure->id,

            'installment_number' =>
                $validated['installment_number'],

            'name' =>
                $validated['name'],

            'amount' =>
                $validated['amount'],

            'due_date' =>
                $validated['due_date'],

            'status' =>
                $validated['status'],
        ]);

        return redirect()
            ->route(
                'admin.fee-installments.show',
                $feeInstallment
            )
            ->with(
                'success',
                'Fee installment updated successfully.'
            );
    }


    /**
     * Soft delete installment.
     */
    public function destroy(
        FeeInstallment $feeInstallment
    ) {

        abort_unless(
            Auth::user()->can('fee-installments.manage'),
            403
        );

        $user = Auth::user();

        if (
            $feeInstallment->school_id !=
            $user->school_id
        ) {
            abort(404);
        }

        /*
         * Financial safety:
         * Do not delete an installment that has
         * already been paid or partially paid.
         */
        if (
            in_array(
                $feeInstallment->status,
                [
                    'paid',
                    'partial',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Paid or partially paid installments cannot be deleted.'
                );
        }

        $feeInstallment->delete();

        return redirect()
            ->route(
                'admin.fee-installments.index'
            )
            ->with(
                'success',
                'Fee installment deleted successfully.'
            );
    }


    /**
     * Toggle installment status.
     */
    public function toggleStatus(
        FeeInstallment $feeInstallment
    ) {

        abort_unless(
            Auth::user()->can('fee-installments.manage'),
            403
        );

        $user = Auth::user();

        if (
            $feeInstallment->school_id !=
            $user->school_id
        ) {
            abort(404);
        }

        /*
         * Only pending/overdue installments can
         * be toggled through this action.
         *
         * Paid/partial/cancelled statuses are
         * business states and should not be
         * changed casually.
         */
        if (
            in_array(
                $feeInstallment->status,
                [
                    'paid',
                    'partial',
                    'cancelled',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This installment status cannot be toggled manually.'
                );
        }

        $feeInstallment->update([
            'status' =>
                $feeInstallment->status === 'pending'
                    ? 'cancelled'
                    : 'pending',
        ]);

        return back()
            ->with(
                'success',
                'Fee installment status updated successfully.'
            );
    }
}