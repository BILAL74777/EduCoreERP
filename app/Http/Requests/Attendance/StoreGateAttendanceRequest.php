<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class StoreGateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_enrollment_id' => [
                'required',
                'uuid',
                'exists:student_enrollments,id',
            ],

            'event_type' => [
                'required',
                'in:IN,OUT',
            ],

            'method' => [
                'required',
                'in:MANUAL,BARCODE',
            ],

            'event_time' => [
                'nullable',
                'date',
            ],

            'gate_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            'device_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ];
    }
}