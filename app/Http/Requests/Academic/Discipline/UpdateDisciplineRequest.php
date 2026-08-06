<?php

namespace App\Http\Requests\Academic\Discipline;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDisciplineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $discipline = $this->route('discipline');

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