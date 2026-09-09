<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Academic\Student\StoreStudentRequest;
use App\Http\Requests\Academic\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Services\Academic\StudentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Throwable;

class StudentController extends BaseController
{
    public function __construct(
        protected StudentService $service
    ) {}

    /**
     * Listing
     */
    public function index(Request $request)
    {
        try {

            $students = $this->service->index(
                $request->all()
            );

            return $this->paginated(
                $students,
                'Students fetched successfully.'
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }

    /**
     * Store
     */
    public function store(StoreStudentRequest $request)
    {
        // dd($request);
        try {

            $student = $this->service->store(
                $request->validated(),
                $request
            );

            return $this->success(
                $student,
                'Faculty created successfully.',
                201
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }

    /**
     * Show
     */
    public function show(Student $student): Response
    {
        return Inertia::render(
            'Academic/Students/Show',
            [
                'student' => $this->service->find($student->id),
            ]
        );
    }

    public function edit(Student $student): Response
    {
        return Inertia::render(
            'Academic/Students/Edit',
            [
                'student' => $this->service->find($student->id),
            ]
        );
    }

    /**
     * Update
     */
    public function update(
        UpdateStudentRequest $request,
        Student $student
    ) {

        // dd($student,$request);
        try {

            $student = $this->service->update(
                $student,
                $request->validated(),
                $request
            );

            return 'success';

            return $this->success(
                $student,
                'Student updated successfully.'
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }

    /**
     * Delete
     */
    public function destroy(Student $student)
    {
        try {

            $this->service->destroy(
                $student
            );

            return $this->success(
                null,
                'Student deleted successfully.'
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }

    /**
     * Dropdown
     */
    // public function getDropdown()
    // {
    //     try {

    //         return $this->success([
    //             'students' => $this->service->dropdown(),
    //         ]);

    //     } catch (Throwable $e) {

    //         return $this->error(
    //             $e->getMessage()
    //         );

    //     }
    // }

    public function getDropdowns()
    {
        return response()->json(
            $this->service->getDropdowns()
        );
    }

    public function getDisciplines(string $faculty)
    {
        return response()->json([
            'disciplines' => $this->service->getDisciplines($faculty),
        ]);
    }

    public function getClasses(string $discipline)
    {
        return response()->json([
            'classes' => $this->service->getClasses($discipline),
        ]);
    }

    public function generateIdCards(Request $request)
    {
        $request->validate(['session_id' => ['required', 'uuid', 'exists:academic_sessions,id'], 'class_id' => ['required', 'uuid', 'exists:academic_classes,id'], 'section' => ['nullable', 'string']]);
        $enrollments = $this->getIdCardEnrollments($request->session_id, $request->class_id, $request->section);
        if ($enrollments->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No active students found for the selected filters.'], 404);
        } $this->prepareIdCards($enrollments);
        $html = view('idcards.sheet', ['enrollments' => $enrollments])->render();
        $pdf = Pdf::loadHTML($html)->setPaper('A4', 'portrait');
        $fileName = 'student_id_cards_'.now()->format('Ymd_His').'.pdf';

        return $pdf->download($fileName);
    }

    /** * Preview ID cards without generating PDF. */
    public function generateIdCardsPreview(Request $request)
    {
        $request->validate(['session_id' => ['required', 'uuid', 'exists:academic_sessions,id'], 'class_id' => ['required', 'uuid', 'exists:academic_classes,id'], 'section' => ['nullable', 'string']]);
        $enrollments = $this->getIdCardEnrollments($request->session_id, $request->class_id, $request->section);
        if ($enrollments->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No active students found for the selected filters.'], 404);
        } $this->prepareIdCards($enrollments);
        $html = view('idcards.sheet', ['enrollments' => $enrollments])->render();

        return response($html, 200)->header('Content-Type', 'text/html');
    }

    /** * Get students for ID cards. */
    private function getIdCardEnrollments(string $sessionId, string $classId, ?string $section = null)
    {
        return StudentEnrollment::query()->with(['student', 'academicSession', 'academicClass'])->where('session_id', $sessionId)->where('class_id', $classId)->where('status', 'Active')->where('is_current', true)->when($section !== null && $section !== '', fn ($query) => $query->whereHas('student', fn ($studentQuery) => $studentQuery->where('section', $section)))->orderBy('roll_no')->get();
    }

    /** * Ensure roll number, card number and barcode exist. */
    private function prepareIdCards($enrollments): void
    {
        $studentService = app(StudentService::class);
        foreach ($enrollments as $enrollment) {
            $student = $enrollment->student;
            $changed = false;
            /* |-------------------------------------------------------------------------- | Roll Number |-------------------------------------------------------------------------- */ if (empty($enrollment->roll_no)) {
                $enrollment->roll_no = $studentService->generateRollNo($enrollment->session_id, $enrollment->class_id, $student->section ?? null);
                $changed = true;
            } /* |-------------------------------------------------------------------------- | Card Number |-------------------------------------------------------------------------- */ if (empty($enrollment->card_no)) {
                $enrollment->card_no = $studentService->generateCardNo($enrollment->session_id, $enrollment->class_id, $enrollment->roll_no, $student->section ?? null);
                $changed = true;
            } /* |-------------------------------------------------------------------------- | Save permanent values |-------------------------------------------------------------------------- */ if ($changed) {
                $enrollment->save();
            } /* |-------------------------------------------------------------------------- | Generate Code 128 Barcode |-------------------------------------------------------------------------- */ try {
                $enrollment->barcode_svg = DNS1D::getBarcodeSVG((string) $enrollment->card_no, 'C128', 1.6, 42);
            } catch (Throwable $e) {
                $enrollment->barcode_svg = '';
                report($e);
            }
        }
    }
}
