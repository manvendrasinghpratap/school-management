<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCertificateRequest;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CertificateController extends Controller
{
    private function schoolId(): int
    {
        return (int) auth()->user()->school_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Certificates
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $certificates = Certificate::query()
            ->where('school_id', $this->schoolId())
            ->with(['student', 'template', 'issuer'])
            ->latest('id')
            ->paginate(15);

        return view('admin.certificates.index', compact('certificates'));
    }

    public function create(): View
    {
        $schoolId = $this->schoolId();

        $templates = CertificateTemplate::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $students = Student::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('admin.certificates.create', compact(
            'templates',
            'students'
        ));
    }

    public function store(
        StoreCertificateRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        $schoolId = $this->schoolId();

        /*
        |--------------------------------------------------------------------------
        | Validate student belongs to current school
        |--------------------------------------------------------------------------
        */

        $student = Student::query()
            ->where('school_id', $schoolId)
            ->whereKey($data['student_id'])
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Validate template belongs to current school
        |--------------------------------------------------------------------------
        */

        $template = CertificateTemplate::query()
            ->where('school_id', $schoolId)
            ->whereKey($data['template_id'])
            ->where('is_active', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Recipient name
        |--------------------------------------------------------------------------
        */

        $recipientName = trim(
            collect([
                $student->first_name ?? null,
                $student->middle_name ?? null,
                $student->last_name ?? null,
            ])
                ->filter()
                ->implode(' ')
        );

        /*
        |--------------------------------------------------------------------------
        | Certificate creation
        |--------------------------------------------------------------------------
        */

        $certificate = DB::transaction(function () use (
            $data,
            $schoolId,
            $student,
            $template,
            $recipientName
        ) {
            $issueDate = $data['issue_date'];

            return Certificate::create([
                'school_id' => $schoolId,

                'template_id' => $template->id,

                'student_id' => $student->id,

                /*
                 * Certificate type comes from the selected template.
                 */
                'certificate_type' => $template->certificate_type,

                /*
                 * Automatically generated unique certificate number.
                 */
                'certificate_number' => $this->generateCertificateNumber(),

                'recipient_name' => $recipientName,

                'course_or_class' => $data['course_or_class'] ?? null,

                /*
                 * The live database has both fields.
                 * We intentionally keep them synchronized.
                 */
                'issued_at' => $issueDate,
                'issue_date' => $issueDate,

                'file_path' => null,

                'notes' => $data['notes'] ?? null,

                'issued_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.certificates.show', $certificate)
            ->with(
                'success',
                'Certificate issued successfully.'
            );
    }

    public function show(Certificate $certificate): View
    {
        $this->ensureSameSchool($certificate);

        $certificate->load([
            'student',
            'template',
            'issuer',
        ]);

        return view(
            'admin.certificates.show',
            compact('certificate')
        );
    }

    public function print(Certificate $certificate): View
    {
        $this->ensureSameSchool($certificate);

        $certificate->load([
            'student',
            'template',
        ]);

        return view(
            'admin.certificates.print',
            compact('certificate')
        );
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $this->ensureSameSchool($certificate);

        $certificate->delete();

        return redirect()
            ->route('admin.certificates.index')
            ->with(
                'success',
                'Certificate deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Certificate Templates
    |--------------------------------------------------------------------------
    */

    public function templates(): View
    {
        $templates = CertificateTemplate::query()
            ->where('school_id', $this->schoolId())
            ->with('creator')
            ->latest('id')
            ->paginate(15);

        return view(
            'admin.certificates.templates',
            compact('templates')
        );
    }

    public function storeTemplate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'certificate_type' => [
                'required',
                'string',
                'max:100',
            ],

            'body' => [
                'nullable',
                'string',
            ],

            'footer_text' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        CertificateTemplate::create([
            'school_id' => $this->schoolId(),
            'created_by' => auth()->id(),
            'name' => $data['name'],
            'certificate_type' => $data['certificate_type'],
            'body' => $data['body'] ?? null,
            'footer_text' => $data['footer_text'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.certificates.templates')
            ->with(
                'success',
                'Certificate template created successfully.'
            );
    }

    public function updateTemplate(
        Request $request,
        CertificateTemplate $template
    ): RedirectResponse {
        $this->ensureSameSchool($template);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'certificate_type' => [
                'required',
                'string',
                'max:100',
            ],

            'body' => [
                'nullable',
                'string',
            ],

            'footer_text' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $template->update([
            'name' => $data['name'],
            'certificate_type' => $data['certificate_type'],
            'body' => $data['body'] ?? null,
            'footer_text' => $data['footer_text'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.certificates.templates')
            ->with(
                'success',
                'Certificate template updated successfully.'
            );
    }

    public function deleteTemplate(
        CertificateTemplate $template
    ): RedirectResponse {
        $this->ensureSameSchool($template);

        $template->delete();

        return redirect()
            ->route('admin.certificates.templates')
            ->with(
                'success',
                'Certificate template deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function generateCertificateNumber(): string
    {
        do {
            $number =
                'CERT-' .
                now()->format('Ymd') .
                '-' .
                Str::upper(Str::random(8));
        } while (
            Certificate::withTrashed()
                ->where('certificate_number', $number)
                ->exists()
        );

        return $number;
    }

    private function ensureSameSchool($model): void
    {
        abort_unless(
            (int) $model->school_id === $this->schoolId(),
            403,
            'You are not authorized to access this record.'
        );
    }
}