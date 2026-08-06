<?php

namespace App\Http\Requests\Academic\Discipline;

use Illuminate\Foundation\Http\FormRequest;

class StoreDisciplineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'faculty_id' => [
                'required',
                'exists:faculties,id',
            ],

            'discipline' => [
                'required',
                'string',
                'max:255',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
 
        ];
    }
}