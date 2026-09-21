<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AcademicHierarchyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicHierarchyController extends Controller
{
    public function __construct(
        protected AcademicHierarchyService $academicHierarchy
    ) {
    }

    public function classes(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer', 'min:1'],
        ]);

        return response()->json([
            'classes' => $this->academicHierarchy
                ->classes((int) $validated['academic_year_id'])
                ->values(),
        ]);
    }

    public function sections(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'min:1'],
        ]);

        return response()->json([
            'sections' => $this->academicHierarchy
                ->sections((int) $validated['class_id'])
                ->values(),
        ]);
    }

    public function students(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer', 'min:1'],
            'class_id' => ['required', 'integer', 'min:1'],
            'section_id' => ['required', 'integer', 'min:1'],
        ]);

        return response()->json([
            'students' => $this->academicHierarchy
                ->students(
                    (int) $validated['academic_year_id'],
                    (int) $validated['class_id'],
                    (int) $validated['section_id']
                )
                ->values(),
        ]);
    }
}
