<?php

namespace App\Http\Controllers\Admin\Wave4;

use App\Models\MedicalRecords;
use App\Models\Student;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MedicalController extends SchoolScopedController
{
    public function index(Request $r)
    {
        $items = MedicalRecords::where('school_id', $this->schoolId())
            ->with(['student', 'staff'])
            ->latest('record_date')
            ->paginate(20)
            ->withQueryString();

        return view('admin.wave4.medical.index', compact('items'));
    }

    public function create()
    {
        return view('admin.wave4.medical.form', [
            'item' => new MedicalRecords(),

            'students' => Student::where('school_id', $this->schoolId())
                ->orderBy('first_name')
                ->get(),

            'staff' => Staff::where('school_id', $this->schoolId())
                ->orderBy('first_name')
                ->get(),
        ]);
    }

    public function store(Request $r)
    {
        $d = $this->validateData($r);

        $d['school_id'] = $this->schoolId();
        $d['recorded_by'] = auth()->id();

        MedicalRecords::create($d);

        return redirect()
            ->route('admin.medical.index')
            ->with('success', 'Medical record created successfully.');
    }

    public function edit(MedicalRecords $medical)
    {
        $this->ensureSchool($medical);

        return view('admin.wave4.medical.form', [
            'item' => $medical,

            'students' => Student::where('school_id', $this->schoolId())
                ->orderBy('first_name')
                ->get(),

            'staff' => Staff::where('school_id', $this->schoolId())
                ->orderBy('first_name')
                ->get(),
        ]);
    }

    public function update(Request $r, MedicalRecords $medical)
    {
        $this->ensureSchool($medical);

        $d = $this->validateData($r);

        $medical->update($d);

        return redirect()
            ->route('admin.medical.index')
            ->with('success', 'Medical record updated successfully.');
    }

    public function destroy(MedicalRecords $medical)
    {
        $this->ensureSchool($medical);

        $medical->delete();

        return back()->with(
            'success',
            'Medical record deleted successfully.'
        );
    }

    private function validateData(Request $r): array
    {
        $data = $r->validate([
            'student_id' => [
                'nullable',
                'integer',
                Rule::exists('students', 'id')
                    ->where(
                        fn ($q) => $q->where(
                            'school_id',
                            $this->schoolId()
                        )
                    ),
            ],

            'staff_id' => [
                'nullable',
                'integer',
                Rule::exists('staff', 'id')
                    ->where(
                        fn ($q) => $q->where(
                            'school_id',
                            $this->schoolId()
                        )
                    ),
            ],

            'record_date' => [
                'required',
                'date',
            ],

            'condition_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'treatment' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'emergency_contact' => [
                'nullable',
                'string',
                'max:255',
            ],

            'emergency_phone' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        $this->ensureOneSubject($data);

        return $data;
    }

    private function ensureOneSubject(array $data): void
    {
        $hasStudent = !empty($data['student_id']);
        $hasStaff = !empty($data['staff_id']);

        // Neither selected
        if (!$hasStudent && !$hasStaff) {
            throw ValidationException::withMessages([
                'student_id' => 'Please select a student or a staff member.',
                'staff_id' => 'Please select a student or a staff member.',
            ]);
        }

        // Both selected
        if ($hasStudent && $hasStaff) {
            throw ValidationException::withMessages([
                'student_id' => 'Select either a student or a staff member, not both.',
                'staff_id' => 'Select either a student or a staff member, not both.',
            ]);
        }
    }
}