<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdCard;
use App\Models\IdCardTemplate;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IdCardController extends Controller
{
    /**
     * Get the authenticated user's school ID.
     */
    private function schoolId(): int
    {
        $schoolId = auth()->user()?->school_id;

        abort_unless($schoolId, 403, 'No school is assigned to the current user.');

        return (int) $schoolId;
    }

    /**
     * Ensure the supplied model belongs to the current school.
     */
    private function ensureSameSchool($model): void
    {
        abort_unless(
            (int) $model->school_id === $this->schoolId(),
            403,
            'You are not authorized to access this record.'
        );
    }

    /**
     * Display generated ID cards.
     */
    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = IdCard::query()
            ->where('school_id', $schoolId)
            ->with([
                'template',
                'student',
                'staff',
                'generator',
            ])
            ->latest('id');

        /*
         * Search by card number or verification code.
         */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('card_number', 'like', "%{$search}%")
                    ->orWhere('verification_code', 'like', "%{$search}%");
            });
        }

        /*
         * Filter by status.
         */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
         * Filter by holder type.
         */
        if ($request->filled('holder_type')) {
            if ($request->holder_type === 'student') {
                $query->whereNotNull('student_id');
            }

            if ($request->holder_type === 'staff') {
                $query->whereNotNull('staff_id');
            }
        }

        $idCards = $query
            ->paginate(15)
            ->withQueryString();

        return view('admin.id-cards.index', compact('idCards'));
    }

    /**
     * Show ID card generation form.
     */
    public function create()
    {
        $schoolId = $this->schoolId();

        $templates = IdCardTemplate::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->latest('id')
            ->get();

        $students = Student::query()
            ->where('school_id', $schoolId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $staff = Staff::query()
            ->where('school_id', $schoolId)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.id-cards.create',
            compact('templates', 'students', 'staff')
        );
    }

    /**
     * Generate a new ID card.
     */
    public function store(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'template_id' => [
                'required',
                'integer',
                'exists:id_card_templates,id',
            ],

            'holder_type' => [
                'required',
                'in:student,staff',
            ],

            'student_id' => [
                'nullable',
                'integer',
                'exists:students,id',
            ],

            'staff_id' => [
                'nullable',
                'integer',
                'exists:staff,id',
            ],

            'issued_at' => [
                'required',
                'date',
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after_or_equal:issued_at',
            ],

            'status' => [
                'nullable',
                'in:active,expired,cancelled',
            ],
        ]);

        /*
         * Verify template belongs to current school and is active.
         */
        $template = IdCardTemplate::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->findOrFail($validated['template_id']);

        /*
         * Determine holder.
         */
        $studentId = null;
        $staffId = null;

        if ($validated['holder_type'] === 'student') {

            if (empty($validated['student_id'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'student_id' => 'Please select a student.',
                    ]);
            }

            $student = Student::query()
                ->where('school_id', $schoolId)
                ->findOrFail($validated['student_id']);

            $studentId = $student->id;
        }

        if ($validated['holder_type'] === 'staff') {

            if (empty($validated['staff_id'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'staff_id' => 'Please select a staff member.',
                    ]);
            }

            $staff = Staff::query()
                ->where('school_id', $schoolId)
                ->findOrFail($validated['staff_id']);

            $staffId = $staff->id;
        }

        /*
         * Create the ID card inside a transaction.
         */
        $idCard = DB::transaction(function () use (
            $schoolId,
            $template,
            $studentId,
            $staffId,
            $validated
        ) {
            return IdCard::create([
                'school_id' => $schoolId,
                'template_id' => $template->id,
                'student_id' => $studentId,
                'staff_id' => $staffId,
                'card_number' => $this->generateCardNumber(),
                'issued_at' => $validated['issued_at'],
                'expires_at' => $validated['expires_at'] ?? null,
                'file_path' => null,
                'verification_code' => $this->generateVerificationCode(),
                'status' => $validated['status'] ?? 'active',
                'generated_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.id-cards.show', $idCard->id)
            ->with('success', 'ID card generated successfully.');
    }

    /**
     * Display an individual ID card.
     */
    public function show($idCard)
    {
        $schoolId = $this->schoolId();

        $idCard = IdCard::query()
            ->where('school_id', $schoolId)
            ->with([
                'template',
                'student',
                'staff',
                'generator',
            ])
            ->findOrFail($idCard);

        return view(
            'admin.id-cards.show',
            compact('idCard')
        );
    }

    /**
     * Print an ID card.
     */
    public function print($idCard)
    {
        $schoolId = $this->schoolId();

        $idCard = IdCard::query()
            ->where('school_id', $schoolId)
            ->with([
                'template',
                'student',
                'staff',
                'generator',
            ])
            ->findOrFail($idCard);

        return view(
            'admin.id-cards.print',
            compact('idCard')
        );
    }

    /**
     * Delete an ID card.
     */
    public function destroy($idCard)
    {
        $schoolId = $this->schoolId();

        $idCard = IdCard::query()
            ->where('school_id', $schoolId)
            ->findOrFail($idCard);

        $idCard->delete();

        return redirect()
            ->route('admin.id-cards.index')
            ->with('success', 'ID card deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | ID CARD TEMPLATES
    |--------------------------------------------------------------------------
    */

    /**
     * Display ID card templates.
     */
    public function templates()
    {
        $schoolId = $this->schoolId();

        $templates = IdCardTemplate::query()
            ->where('school_id', $schoolId)
            ->with('creator')
            ->latest('id')
            ->paginate(15);

        return view(
            'admin.id-cards.templates',
            compact('templates')
        );
    }

    /**
     * Create an ID card template.
     */
    public function storeTemplate(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'card_type' => [
                'required',
                'string',
                'max:50',
            ],

            'front_html' => [
                'nullable',
                'string',
            ],

            'back_html' => [
                'nullable',
                'string',
            ],
        ]);

        IdCardTemplate::create([
            'school_id' => $schoolId,
            'created_by' => auth()->id(),
            'name' => $validated['name'],
            'card_type' => $validated['card_type'],
            'front_html' => $validated['front_html'] ?? null,
            'back_html' => $validated['back_html'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.id-cards.templates')
            ->with(
                'success',
                'ID card template created successfully.'
            );
    }

    /**
     * Update an ID card template.
     */
    public function updateTemplate(Request $request, $template)
    {
        $schoolId = $this->schoolId();

        $template = IdCardTemplate::query()
            ->where('school_id', $schoolId)
            ->findOrFail($template);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'card_type' => [
                'required',
                'string',
                'max:50',
            ],

            'front_html' => [
                'nullable',
                'string',
            ],

            'back_html' => [
                'nullable',
                'string',
            ],
        ]);

        $template->update([
            'name' => $validated['name'],
            'card_type' => $validated['card_type'],
            'front_html' => $validated['front_html'] ?? null,
            'back_html' => $validated['back_html'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.id-cards.templates')
            ->with(
                'success',
                'ID card template updated successfully.'
            );
    }

    /**
     * Delete an ID card template.
     */
    public function deleteTemplate($template)
    {
        $schoolId = $this->schoolId();

        $template = IdCardTemplate::query()
            ->where('school_id', $schoolId)
            ->findOrFail($template);

        $template->delete();

        return redirect()
            ->route('admin.id-cards.templates')
            ->with(
                'success',
                'ID card template deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | NUMBER / VERIFICATION HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Generate a unique ID card number.
     */
    private function generateCardNumber(): string
    {
        do {
            $number = 'ID-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(8));
        } while (
            IdCard::withTrashed()
                ->where('card_number', $number)
                ->exists()
        );

        return $number;
    }

    /**
     * Generate a unique verification code.
     */
    private function generateVerificationCode(): string
    {
        do {
            $code = strtoupper(Str::random(16));
        } while (
            IdCard::withTrashed()
                ->where('verification_code', $code)
                ->exists()
        );

        return $code;
    }
}