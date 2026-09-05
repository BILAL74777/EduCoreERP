<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
                'max:50',
            ],

            'attendance_date' => [
                'required',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'records' => [
                'required',
                'array',
                'min:1',
            ],

            'records.*.student_enrollment_id' => [
                'required',
                'uuid',
                'exists:student_enrollments,id',
            ],

            'records.*.status' => [
                'required',
                'in:Present,Absent,Late,Leave',
            ],

            'records.*.remarks' => [
                'nullable',
                'string',
            ],
        ];
    }
}
