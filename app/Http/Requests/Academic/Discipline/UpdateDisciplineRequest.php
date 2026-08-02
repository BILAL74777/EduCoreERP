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

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('disciplines', 'code')
                    ->ignore($discipline->id),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'duration_years' => [
                'required',
                'integer',
                'min:1',
                'max:10',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}