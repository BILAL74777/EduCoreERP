<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FacultyUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'faculty' => [

                'required',

                'string',

                'max:255',

                Rule::unique('faculties', 'faculty')
                    ->ignore($this->route('faculty')->id),

            ],
 

            'is_active' => [
                'required',
                'boolean',
            ],

        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [

            'faculty.required' => 'Faculty faculty is required.',

            'faculty.unique' => 'Faculty faculty already exists.',

        ];
    }
}