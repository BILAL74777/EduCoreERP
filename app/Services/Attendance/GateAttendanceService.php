<?php

namespace App\Services\Attendance;

use App\Models\GateAttendance;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GateAttendanceService
{
    /**
     * Search active/current student enrollment by:
     * - registration number
     * - admission number
     * - card number
     *
     * registration_no and admission_no are on students table.
     * card_no is on student_enrollments table.
     */
    public function findStudent(string $value)
    {
        return StudentEnrollment::query()
            ->with([
                'student',
                'academicClass',
                'academicSession',
            ])
            ->where('status', 'Active')
            ->where('is_current', true)
            ->where(function ($query) use ($value) {

                // Card number belongs to student_enrollments
                $query->where('card_no', $value)

                    // Registration/admission numbers belong to students
                    ->orWhereHas('student', function ($studentQuery) use ($value) {
                        $studentQuery
                            ->where('registration_no', $value)
                            ->orWhere('admission_no', $value);
                    });
            })
            ->first();
    }

    /**
     * Get today's latest gate event for student enrollment.
     */
    public function latestEvent(string $enrollmentId)
    {
        return GateAttendance::query()
            ->where(
                'student_enrollment_id',
                $enrollmentId
            )
            ->whereDate(
                'event_time',
                today()
            )
            ->latest('event_time')
            ->first();
    }

    /**
     * Mark gate IN/OUT.
     */
    public function mark(array $data): GateAttendance
    {
        return DB::transaction(function () use ($data) {

            /*
             * Find active/current enrollment.
             */
            $enrollment = StudentEnrollment::query()
                ->where(
                    'id',
                    $data['student_enrollment_id']
                )
                ->where(
                    'status',
                    'Active'
                )
                ->where(
                    'is_current',
                    true
                )
                ->first();

            if (!$enrollment) {
                throw ValidationException::withMessages([
                    'student_enrollment_id' => [
                        'The selected student does not have an active enrollment.'
                    ],
                ]);
            }

            /*
             * Get today's latest event.
             *
             * This is important because a student's previous day's
             * OUT should not prevent them from making an IN today.
             */
            $lastEvent = $this->latestEvent(
                $enrollment->id
            );

            /*
             * Prevent invalid sequence:
             *
             * IN -> IN = invalid
             * OUT -> OUT = invalid
             *
             * Valid:
             *
             * no event -> IN
             * IN -> OUT
             * OUT -> IN
             */
            if ($lastEvent) {

                if (
                    $lastEvent->event_type === 'IN' &&
                    $data['event_type'] === 'IN'
                ) {
                    throw ValidationException::withMessages([
                        'event_type' => [
                            'Student is already marked IN.'
                        ],
                    ]);
                }

                if (
                    $lastEvent->event_type === 'OUT' &&
                    $data['event_type'] === 'OUT'
                ) {
                    throw ValidationException::withMessages([
                        'event_type' => [
                            'Student is already marked OUT.'
                        ],
                    ]);
                }
            }

            /*
             * Prevent accidental duplicate scans.
             *
             * Example:
             * Scanner scans same card twice within 2 minutes.
             */
            $recent = GateAttendance::query()
                ->where(
                    'student_enrollment_id',
                    $enrollment->id
                )
                ->where(
                    'event_type',
                    $data['event_type']
                )
                ->where(
                    'event_time',
                    '>=',
                    now()->subMinutes(2)
                )
                ->exists();

            if ($recent) {
                throw ValidationException::withMessages([
                    'event_type' => [
                        'This attendance event was already recorded recently.'
                    ],
                ]);
            }

            /*
             * Create attendance record.
             */
            return GateAttendance::create([
                'student_enrollment_id' => $enrollment->id,

                'event_type' => $data['event_type'],

                'method' => $data['method'],

                'event_time' => $data['event_time'] ?? now(),

                'gate_id' => $data['gate_id'] ?? null,

                'device_id' => $data['device_id'] ?? null,

                'marked_by' => auth()->id(),

                'remarks' => $data['remarks'] ?? null,

            ])->load([
                'enrollment.student',
                'enrollment.academicClass',
                'enrollment.academicSession',
            ]);
        });
    }

    /**
     * Today's gate history.
     *
     * Search:
     * - student name
     * - registration number
     * - admission number
     * - enrollment card number
     */
    public function todayHistory(?string $search = null)
    {
        return GateAttendance::query()
            ->with([
                'enrollment.student',
                'enrollment.academicClass',
                'enrollment.academicSession',
            ])
            ->whereDate(
                'event_time',
                today()
            )
            ->when(
                $search,
                function ($query) use ($search) {

                    $query->where(function ($query) use ($search) {

                        /*
                         * Search student fields.
                         */
                        $query->whereHas(
                            'enrollment.student',
                            function ($studentQuery) use ($search) {

                                $studentQuery
                                    ->where(
                                        'name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'registration_no',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'admission_no',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        )

                        /*
                         * Search card number on enrollment.
                         */
                        ->orWhereHas(
                            'enrollment',
                            function ($enrollmentQuery) use ($search) {

                                $enrollmentQuery->where(
                                    'card_no',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        );
                    });
                }
            )
            ->latest('event_time')
            ->paginate(20);
    }

    /**
     * Complete gate history for one student.
     *
     * Filters:
     * - from
     * - to
     * - event_type
     * - per_page
     */
    public function studentHistory(
        string $studentId,
        array $filters = []
    ) {
        $query = GateAttendance::query()
            ->with([
                'enrollment.student',
                'enrollment.academicClass',
                'enrollment.academicSession',
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
                !empty($filters['from']),
                fn ($q) =>
                    $q->whereDate(
                        'event_time',
                        '>=',
                        $filters['from']
                    )
            )

            ->when(
                !empty($filters['to']),
                fn ($q) =>
                    $q->whereDate(
                        'event_time',
                        '<=',
                        $filters['to']
                    )
            )

            ->when(
                !empty($filters['event_type']),
                fn ($q) =>
                    $q->where(
                        'event_type',
                        $filters['event_type']
                    )
            );

        /*
         * Get all matching records for summary.
         */
        $all = (clone $query)->get();

        return [
            'summary' => [
                'total_events' => $all->count(),

                'in' => $all
                    ->where(
                        'event_type',
                        'IN'
                    )
                    ->count(),

                'out' => $all
                    ->where(
                        'event_type',
                        'OUT'
                    )
                    ->count(),
            ],

            'history' => $query
                ->latest('event_time')
                ->paginate(
                    $filters['per_page'] ?? 20
                ),
        ];
    }
}
