<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class FacultyStoreRequest extends FormRequest
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
                'unique:faculties,code',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
                'unique:faculties,name',
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