<?php

namespace App\Http\Requests\Academic\AcademicClass;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicClassRequest extends FormRequest
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

            'discipline_id' => [
                'required',
                'exists:disciplines,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'level' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],

            'next_class' => [
                'nullable',
                'string',
                'max:32',
            ],

            'previous_class' => [
                'nullable',
                'string',
                'max:32',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ];
    }
}