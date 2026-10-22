<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Models\AcademicClass;
use App\Models\AcademicSession;
use App\Models\Student;
use App\Services\Attendance\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $service
    ) {
    }

    /**
     * Attendance dropdowns.
     */
    public function index()
    {
        return response()->json([
            'success' => true,

            'sessions' => AcademicSession::query()
                // ->where('is_active', true)
                ->orderByDesc('start_date')
                ->get(),

            'classes' => AcademicClass::query()
                ->where('is_active', true)
                ->with([
                    'discipline',
                ])
                ->orderBy('level')
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Get students for selected class/session.
     */
    public function students(Request $request)
    {
        $request->validate([
            'session_id' => [
                'required',
                'uuid',
                'exists:academic_sessions,id',
            ],

            'class_id' => [
                'required',
                'uuid',
                'exists:academic_classes,id',
            ],

            'section' => [
                'nullable',
                'string',
            ],
        ]);

        $students = $this->service->getStudents(
            $request->session_id,
            $request->class_id,
            $request->section
        );

        return response()->json([
            'success' => true,
            'students' => $students,
        ]);
    }

    /**
     * Get attendance sheet including saved statuses.
     */
    public function sheet(Request $request)
    {
        $request->validate([
            'session_id' => [
                'required',
                'uuid',
                'exists:academic_sessions,id',
            ],

            'class_id' => [
                'required',
                'uuid',
                'exists:academic_classes,id',
            ],

            'section' => [
                'nullable',
                'string',
            ],

            'date' => [
                'required',
                'date',
            ],
        ]);

        return response()->json([
            'success' => true,

            'students' => $this->service->getAttendanceSheet(
                $request->session_id,
                $request->class_id,
                $request->section,
                $request->date
            ),
        ]);
    }

    /**
     * Save classroom attendance.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $attendance = $this->service->save(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Attendance saved successfully.',
            'attendance' => $attendance,
        ]);
    }

    /**
     * Classroom attendance history.
     */
    public function history(Request $request)
    {
        return response()->json([
            'success' => true,

            'attendance' => $this->service->history(
                $request->only([
                    'session_id',
                    'class_id',
                    'section',
                    'date',
                    'per_page',
                ])
            ),
        ]);
    }

    /**
     * Individual student's classroom attendance.
     */
    public function studentHistory(
        Request $request,
        string $student
    ) {
        $request->validate([
            'session_id' => [
                'nullable',
                'uuid',
            ],

            'from' => [
                'nullable',
                'date',
            ],

            'to' => [
                'nullable',
                'date',
            ],

            'status' => [
                'nullable',
                'in:Present,Absent,Late,Leave',
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],
        ]);

        return response()->json([
            'success' => true,

            'attendance' => $this->service->studentHistory(
                $student,
                $request->only([
                    'session_id',
                    'from',
                    'to',
                    'status',
                    'per_page',
                ])
            ),
        ]);
    }

 
public function studentSearch(Request $request)
{
    $request->validate([
        'search' => [
            'required',
            'string',
            'max:100',
        ],
    ]);

    $search = $request->search;

    $students = Student::query()
        ->with([
            'currentEnrollment.academicClass',
            'currentEnrollment.academicSession',
        ])

        /*
         * Search student fields and current enrollment card number.
         */
        ->where(function ($query) use ($search) {

            /*
             * Student table fields.
             */
            $query->where(
                'name',
                'like',
                "%{$search}%"
            )
            ->orWhere(
                'admission_no',
                'like',
                "%{$search}%"
            )
            ->orWhere(
                'registration_no',
                'like',
                "%{$search}%"
            )

            /*
             * card_no belongs to student_enrollments,
             * not students.
             */
            ->orWhereHas(
                'currentEnrollment',
                function ($enrollmentQuery) use ($search) {

                    $enrollmentQuery->where(
                        'card_no',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        })

        ->orderBy('name')
        ->limit(20)
        ->get();

    return response()->json([
        'success' => true,
        'students' => $students,
    ]);
}
}
