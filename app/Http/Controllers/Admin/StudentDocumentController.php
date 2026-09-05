<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentDocumentRequest;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Services\StudentDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentDocumentController extends Controller
{
    public function __construct(
        protected StudentDocumentService $studentDocumentService
    ) {
    }

    /**
     * Display all documents belonging to a student.
     */
    public function index(Student $student): View
    {
        $this->ensureSameSchool($student);

        $documents = $student->documents()
            ->with('uploadedBy')
            ->latest()
            ->paginate(15);

        return view(
            'admin.students.documents.index',
            compact('student', 'documents')
        );
    }

    /**
     * Store a new student document.
     */
    public function store(
        StoreStudentDocumentRequest $request,
        Student $student
    ): RedirectResponse {
        $this->ensureSameSchool($student);

        $this->studentDocumentService->create(
            $student,
            $request->validated(),
            $request->file('document')
        );

        return redirect()
            ->route('admin.students.documents.index', $student)
            ->with(
                'success',
                'Student document uploaded successfully.'
            );
    }

    /**
     * Download a student document.
     */
   public function download(
    Student $student,
    StudentDocument $document
): StreamedResponse {
    $this->ensureSameSchool($student);
    $this->ensureBelongsToStudent($student, $document);

    abort_unless(
        Storage::disk('public')->exists($document->file_path),
        404,
        'The requested document file was not found.'
    );

    /*
     * Prefer the saved document name.
     * If it has no extension, recover the extension
     * from the actual stored file.
     */
    $downloadName = $document->document_name;

    if (!$downloadName) {
        $downloadName = basename($document->file_path);
    }

    $extension = pathinfo(
        $document->file_path,
        PATHINFO_EXTENSION
    );

    if (
        $extension &&
        !pathinfo($downloadName, PATHINFO_EXTENSION)
    ) {
        $downloadName .= '.' . $extension;
    }

    return Storage::disk('public')->download(
        $document->file_path,
        $downloadName
    );
}

    /**
     * Delete a student document.
     */
    public function destroy(
        Student $student,
        StudentDocument $document
    ): RedirectResponse {
        $this->ensureSameSchool($student);
        $this->ensureBelongsToStudent($student, $document);

        $this->studentDocumentService->delete(
            $student,
            $document
        );

        return redirect()
            ->route('admin.students.documents.index', $student)
            ->with(
                'success',
                'Student document deleted successfully.'
            );
    }

    /**
     * Ensure the student belongs to the current user's school.
     */
    protected function ensureSameSchool(Student $student): void
    {
        $user = auth()->user();

        abort_unless(
            $user,
            403,
            'You must be authenticated.'
        );

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        abort_unless(
            (int) $student->school_id === (int) $user->school_id,
            403,
            'You are not authorized to access this student.'
        );
    }

    /**
     * Ensure the document belongs to the supplied student.
     */
    protected function ensureBelongsToStudent(
        Student $student,
        StudentDocument $document
    ): void {
        abort_unless(
            (int) $document->student_id === (int) $student->id,
            404,
            'Student document not found.'
        );
    }
}