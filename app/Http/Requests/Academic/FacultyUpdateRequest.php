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

            'code' => [

                'required',

                'string',

                'max:20',

                Rule::unique('faculties', 'code')
                    ->ignore($this->route('faculty')->id),

            ],

            'name' => [

                'required',

                'string',

                'max:255',

                Rule::unique('faculties', 'name')
                    ->ignore($this->route('faculty')->id),

            ],

            'description' => [
                'nullable',
                'string',
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

            'code.required' => 'Faculty code is required.',

            'code.unique' => 'Faculty code already exists.',

            'name.required' => 'Faculty name is required.',

            'name.unique' => 'Faculty name already exists.',

        ];
    }
}