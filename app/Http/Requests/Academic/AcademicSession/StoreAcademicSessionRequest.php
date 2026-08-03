<?php

namespace App\Http\Requests\Academic\AcademicSession;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicSessionRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules
     */
    public function rules(): array
    {
        return [

            'session_name' => [
                'required',
                'string',
                'max:255',
                'unique:academic_sessions,session_name',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after:start_date',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            'session_name.required' => 'Session name is required.',
            'session_name.unique' => 'This academic session already exists.',

            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Please enter a valid start date.',

            'end_date.required' => 'End date is required.',
            'end_date.date' => 'Please enter a valid end date.',
            'end_date.after' => 'End date must be after the start date.',

        ];
    }
}