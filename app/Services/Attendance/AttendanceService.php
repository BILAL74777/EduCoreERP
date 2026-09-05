<?php

namespace App\Services\Attendance;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    /**
     * Get active students for a class/session.
     */
    public function getStudents(
        string $sessionId,
        string $classId,
        ?string $section = null
    ) {
        return StudentEnrollment::query()
            ->with([
                'student',
                'academicClass',
                'academicSession',
            ])
            ->where('session_id', $sessionId)
            ->where('class_id', $classId)
            ->where('status', 'Active')
            ->where('is_current', true)
            ->when(
                $section !== null && $section !== '',
                function ($query) use ($section) {
                    $query->whereHas(
                        'student',
                        function ($q) use ($section) {
                            $q->where('section', $section);
                        }
                    );
                }
            )
            ->orderByRaw(
                'CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END'
            )
            ->orderBy('roll_no')
            ->orderBy(
                'id'
            )
            ->get();
    }

    /**
     * Load students with their attendance status
     * for a particular date/class/session.
     */
    public function getAttendanceSheet(
        string $sessionId,
        string $classId,
        ?string $section,
        string $date
    ) {
        $students = $this->getStudents(
            $sessionId,
            $classId,
            $section
        );

        $attendanceSession = AttendanceSession::query()
            ->with('records')
            ->where('session_id', $sessionId)
            ->where('class_id', $classId)
            ->where('attendance_date', $date)
            ->when(
                $section !== null && $section !== '',
                fn ($q) => $q->where('section', $section)
            )
            ->when(
                $section === null || $section === '',
                fn ($q) => $q->whereNull('section')
            )
            ->first();

        $records = collect();

        if ($attendanceSession) {
            $records = $attendanceSession
                ->records
                ->keyBy('student_enrollment_id');
        }

        return $students->map(function ($enrollment) use ($records) {
            $record = $records->get($enrollment->id);

            return [
                'id' => $enrollment->id,
                'student_id' => $enrollment->student_id,
                'roll_no' => $enrollment->roll_no,

                'student' => $enrollment->student,

                'status' => $record?->status ?? 'Present',

                'remarks' => $record?->remarks,

                'marked_at' => $record?->marked_at,
            ];
        });
    }

    /**
     * Save classroom attendance.
     */
    public function save(array $data): AttendanceSession
    {
        return DB::transaction(function () use ($data) {

            $attendanceSession = AttendanceSession::updateOrCreate(
                [
                    'session_id' => $data['session_id'],
                    'class_id' => $data['class_id'],
                    'section' => $data['section'] ?? null,
                    'attendance_date' => $data['attendance_date'],
                ],
                [
                    'marked_by' => auth()->id(),
                    'remarks' => $data['remarks'] ?? null,
                ]
            );

            foreach ($data['records'] as $record) {

                $enrollment = StudentEnrollment::query()
                    ->where('id', $record['student_enrollment_id'])
                    ->where(
                        'session_id',
                        $data['session_id']
                    )
                    ->where(
                        'class_id',
                        $data['class_id']
                    )
                    ->where('status', 'Active')
                    ->where('is_current', true)
                    ->when(
                        ($data['section'] ?? null) !== null &&
                        ($data['section'] ?? '') !== '',
                        function ($query) use ($data) {
                            $query->whereHas(
                                'student',
                                function ($q) use ($data) {
                                    $q->where(
                                        'section',
                                        $data['section']
                                    );
                                }
                            );
                        }
                    )
                    ->first();

                if (!$enrollment) {
                    throw ValidationException::withMessages([
                        'records' => [
                            'One or more students do not belong to the selected class/session.'
                        ],
                    ]);
                }

                AttendanceRecord::updateOrCreate(
                    [
                        'attendance_session_id' =>
                            $attendanceSession->id,

                        'student_enrollment_id' =>
                            $enrollment->id,
                    ],
                    [
                        'status' =>
                            $record['status'],

                        'remarks' =>
                            $record['remarks'] ?? null,

                        'marked_at' =>
                            now(),
                    ]
                );
            }

            return $attendanceSession->load([
                'session',
                'academicClass',
                'records.enrollment.student',
                'records.enrollment.academicSession',
            ]);
        });
    }

    /**
     * Classroom attendance history.
     */
    public function history(array $filters = [])
    {
        return AttendanceSession::query()
            ->with([
                'session',
                'academicClass',
                'records.enrollment.student',
            ])

            ->when(
                !empty($filters['session_id']),
                fn ($q) => $q->where(
                    'session_id',
                    $filters['session_id']
                )
            )

            ->when(
                !empty($filters['class_id']),
                fn ($q) => $q->where(
                    'class_id',
                    $filters['class_id']
                )
            )

            ->when(
                !empty($filters['date']),
                fn ($q) => $q->where(
                    'attendance_date',
                    $filters['date']
                )
            )

            ->when(
                isset($filters['section']) &&
                $filters['section'] !== '',
                fn ($q) => $q->where(
                    'section',
                    $filters['section']
                )
            )

            ->latest('attendance_date')
            ->latest('created_at')
            ->paginate(
                $filters['per_page'] ?? 20
            );
    }

    /**
     * Individual student classroom attendance history.
     */
    public function studentHistory(
        string $studentId,
        array $filters = []
    ) {
        $query = AttendanceRecord::query()
            ->with([
                'attendanceSession.session',
                'attendanceSession.academicClass',
                'enrollment.student',
            ])
            ->whereHas(
                'enrollment',
                function ($q) use ($studentId) {
                    $q->where(
                        'student_id',
                        $studentId
                    );
                }
            )

            ->when(
                !empty($filters['session_id']),
                function ($q) use ($filters) {
                    $q->whereHas(
                        'attendanceSession',
                        fn ($session) =>
                        $session->where(
                            'session_id',
                            $filters['session_id']
                        )
                    );
                }
            )

            ->when(
                !empty($filters['from']),
                function ($q) use ($filters) {
                    $q->whereHas(
                        'attendanceSession',
                        fn ($session) =>
                        $session->whereDate(
                            'attendance_date',
                            '>=',
                            $filters['from']
                        )
                    );
                }
            )

            ->when(
                !empty($filters['to']),
                function ($q) use ($filters) {
                    $q->whereHas(
                        'attendanceSession',
                        fn ($session) =>
                        $session->whereDate(
                            'attendance_date',
                            '<=',
                            $filters['to']
                        )
                    );
                }
            )

            ->when(
                !empty($filters['status']),
                fn ($q) => $q->where(
                    'status',
                    $filters['status']
                )
            );

        $allRecords = (clone $query)->get();

        $total = $allRecords->count();

        $present = $allRecords
            ->where('status', 'Present')
            ->count();

        $absent = $allRecords
            ->where('status', 'Absent')
            ->count();

        $late = $allRecords
            ->where('status', 'Late')
            ->count();

        $leave = $allRecords
            ->where('status', 'Leave')
            ->count();

        $percentage = $total > 0
            ? round(
                (
                    ($present + $late)
                    / $total
                ) * 100,
                2
            )
            : 0;

        return [
            'student' => StudentEnrollment::query()
                ->with([
                    'student',
                    'academicClass',
                    'academicSession',
                ])
                ->where('student_id', $studentId)
                ->where('is_current', true)
                ->first(),

            'summary' => [
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'leave' => $leave,
                'percentage' => $percentage,
            ],

            'history' => $query
                ->orderByDesc(
                    AttendanceRecord::query()
                        ->select(
                            'attendance_date'
                        )
                        ->from(
                            'attendance_sessions'
                        )
                        ->whereColumn(
                            'attendance_sessions.id',
                            'attendance_records.attendance_session_id'
                        )
                        ->limit(1)
                )
                ->paginate(
                    $filters['per_page'] ?? 20
                ),
        ];
    }
}