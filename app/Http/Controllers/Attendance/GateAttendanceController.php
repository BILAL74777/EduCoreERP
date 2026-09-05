<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreGateAttendanceRequest;
use App\Services\Attendance\GateAttendanceService;
use Illuminate\Http\Request;

class GateAttendanceController extends Controller
{
    public function __construct(
        protected GateAttendanceService $service
    ) {
    }

    /**
     * Search student by admission,
     * registration or card number.
     */
    public function search(Request $request)
    {
        $request->validate([
            'search' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $student = $this->service->findStudent(
            $request->search
        );

        return response()->json([
            'success' => true,
            'student' => $student,
        ]);
    }

    /**
     * Mark gate IN/OUT.
     */
    public function store(
        StoreGateAttendanceRequest $request
    ) {
        $attendance = $this->service->mark(
            $request->validated()
        );

        return response()->json([
            'success' => true,

            'message' =>
                $request->event_type === 'IN'
                    ? 'Student marked IN successfully.'
                    : 'Student marked OUT successfully.',

            'attendance' => $attendance,
        ]);
    }

    /**
     * Today's gate history.
     */
    public function history(Request $request)
    {
        return response()->json([
            'success' => true,

            'attendance' =>
                $this->service->todayHistory(
                    $request->search
                ),
        ]);
    }

    /**
     * Individual student's gate history.
     */
    public function studentHistory(
        Request $request,
        string $student
    ) {
        $request->validate([
            'from' => [
                'nullable',
                'date',
            ],

            'to' => [
                'nullable',
                'date',
            ],

            'event_type' => [
                'nullable',
                'in:IN,OUT',
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

            'attendance' =>
                $this->service->studentHistory(
                    $student,
                    $request->only([
                        'from',
                        'to',
                        'event_type',
                        'per_page',
                    ])
                ),
        ]);
    }
}