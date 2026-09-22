<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHostelFeeRequest;
use App\Models\HostelAllocation;
use App\Models\HostelFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelFeeController extends Controller
{
    private function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        abort_unless($schoolId, 403, 'No school is assigned to the current user.');

        return (int) $schoolId;
    }

    private function own($model): void
    {
        abort_unless(
            (int) $model->school_id === $this->schoolId(),
            404
        );
    }

    public function fees(Request $request)
    {
        $fees = HostelFee::query()
            ->where('school_id', $this->schoolId())
            ->with([
                'allocation.student',
                'allocation.hostel',
                'allocation.room',
                'invoice',
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search'));

                $query->whereHas('allocation.student', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->input('status'))
            )
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.hostel.fees.index', compact('fees'));
    }

    public function createFee()
    {
        $allocations = HostelAllocation::query()
            ->where('school_id', $this->schoolId())
            ->whereIn('status', ['allocated', 'checked_in'])
            ->with(['student', 'hostel', 'room'])
            ->latest('id')
            ->get();

        return view('admin.hostel.fees.create', compact('allocations'));
    }

    public function storeFee(StoreHostelFeeRequest $request)
    {
        $data = $request->validated();
        $data['school_id'] = $this->schoolId();

        $fee = HostelFee::create($data);

        return redirect()
            ->route('admin.hostel.fees.show', $fee)
            ->with('success', 'Hostel fee created successfully.');
    }

    public function showFee(HostelFee $fee)
    {
        $this->own($fee);

        $fee->load([
            'allocation.student',
            'allocation.hostel',
            'allocation.room',
            'allocation.bed',
            'allocation.academicYear',
            'invoice',
        ]);

        return view('admin.hostel.fees.show', compact('fee'));
    }

    public function editFee(HostelFee $fee)
    {
        $this->own($fee);

        $allocations = HostelAllocation::query()
            ->where('school_id', $this->schoolId())
            ->whereIn('status', ['allocated', 'checked_in'])
            ->with(['student', 'hostel', 'room'])
            ->latest('id')
            ->get();

        return view('admin.hostel.fees.edit', compact('fee', 'allocations'));
    }

    public function updateFee(
        StoreHostelFeeRequest $request,
        HostelFee $fee
    ) {
        $this->own($fee);

        $data = $request->validated();
        $data['school_id'] = $this->schoolId();

        $fee->fill($data);
        $fee->save();

        return redirect()
            ->route('admin.hostel.fees.show', $fee)
            ->with('success', 'Hostel fee updated successfully.');
    }

    public function destroyFee(HostelFee $fee)
    {
        $this->own($fee);

        $fee->delete();

        return back()->with('success', 'Hostel fee deleted successfully.');
    }
}
