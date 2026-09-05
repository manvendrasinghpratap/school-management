<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentDocumentService
{
    /**
     * Upload and create a student document.
     */
    public function create(
        Student $student,
        array $data,
        UploadedFile $document
    ): StudentDocument {
        $this->ensureSameSchool($student);

        $storedPath = null;

        try {
            return DB::transaction(function () use (
                $student,
                $data,
                $document,
                &$storedPath
            ) {
                $storedPath = $document->store(
                    'students/documents',
                    'public'
                );

                /*
                 * Keep the original uploaded filename, including
                 * its extension, when no custom document name
                 * is supplied.
                 */
                $documentName = $data['document_name'] ?? null;

                if (!$documentName) {
                    $documentName = $document->getClientOriginalName();
                }

                return StudentDocument::create([
                    'student_id' => $student->id,
                    'document_type' => $data['document_type'],
                    'document_name' => $documentName,
                    'file_path' => $storedPath,
                    'description' => $data['description'] ?? null,
                    'uploaded_by' => auth()->id(),
                ]);
            });
        } catch (\Throwable $exception) {
            if ($storedPath) {
                Storage::disk('public')->delete($storedPath);
            }

            throw $exception;
        }
    }

    /**
     * Delete a student document and its physical file.
     */
    public function delete(
        Student $student,
        StudentDocument $studentDocument
    ): void {
        $this->ensureSameSchool($student);

        $this->ensureBelongsToStudent(
            $student,
            $studentDocument
        );

        DB::transaction(function () use ($studentDocument) {
            $filePath = $studentDocument->file_path;

            $studentDocument->delete();

            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
        });
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
        StudentDocument $studentDocument
    ): void {
        abort_unless(
            (int) $studentDocument->student_id === (int) $student->id,
            404,
            'Student document not found.'
        );
    }
}