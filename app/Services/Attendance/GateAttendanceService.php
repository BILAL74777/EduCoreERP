<?php

namespace App\Services\Attendance;

use App\Models\GateAttendance;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GateAttendanceService
{
    /**
     * Search active student by:
     * registration number,
     * admission number,
     * card number.
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
            ->whereHas(
                'student',
                function ($query) use ($value) {
                    $query->where(
                        'registration_no',
                        $value
                    )
                    ->orWhere(
                        'admission_no',
                        $value
                    )
                    ->orWhere(
                        'card_no',
                        $value
                    );
                }
            )
            ->first();
    }

    /**
     * Get today's latest gate event for student.
     */
    public function latestEvent(
        string $enrollmentId
    ) {
        return GateAttendance::query()
            ->where(
                'student_enrollment_id',
                $enrollmentId
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

            $lastEvent = $this->latestEvent(
                $enrollment->id
            );

            /*
             * Prevent invalid sequence:
             *
             * No OUT before IN.
             * No IN while already inside.
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

            return GateAttendance::create([
                'student_enrollment_id' =>
                    $enrollment->id,

                'event_type' =>
                    $data['event_type'],

                'method' =>
                    $data['method'],

                'event_time' =>
                    $data['event_time'] ?? now(),

                'gate_id' =>
                    $data['gate_id'] ?? null,

                'device_id' =>
                    $data['device_id'] ?? null,

                'marked_by' =>
                    auth()->id(),

                'remarks' =>
                    $data['remarks'] ?? null,
            ])->load([
                'enrollment.student',
                'enrollment.academicClass',
                'enrollment.academicSession',
            ]);
        });
    }

    /**
     * Today's gate history.
     */
    public function todayHistory(
        ?string $search = null
    ) {
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
                    $query->whereHas(
                        'enrollment.student',
                        function ($q) use ($search) {

                            $q->where(
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
                            )
                            ->orWhere(
                                'card_no',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
                }
            )

            ->latest('event_time')
            ->paginate(20);
    }

    /**
     * Complete gate history for one student.
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