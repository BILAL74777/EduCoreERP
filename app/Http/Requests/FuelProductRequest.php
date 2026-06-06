<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FuelProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'code' => [
                'required',
                'string',
                'max:50'
            ],

            'unit' => [
                'required',
                'string',
                'max:20'
            ],

            'purchase_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'sale_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'status' => ['required', 'boolean'],
        ];
    }
}
