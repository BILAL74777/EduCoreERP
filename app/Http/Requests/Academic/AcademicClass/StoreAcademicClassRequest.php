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

            'code' => [
                'required',
                'string',
                'max:20',
                'unique:academic_classes,code',
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

            'next_class_id' => [
                'nullable',
                'exists:academic_classes,id',
                'different:id',
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