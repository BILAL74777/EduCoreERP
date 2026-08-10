<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentFeePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => [
                'required',
                'uuid',
                'exists:students,id',
            ],

            'enrollment_id' => [
                'required',
                'uuid',
                'exists:student_enrollments,id',
            ],

            'fee_structure_id' => [
                'required',
                'uuid',
                'exists:student_fee_structures,id',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Please select a student.',
            'enrollment_id.required' => 'Student enrollment is required.',
            'fee_structure_id.required' => 'Student fee structure is required.',
            'amount.required' => 'Please enter the payment amount.',
            'amount.min' => 'Payment amount must be greater than zero.',
            'payment_date.required' => 'Payment date is required.',
        ];
    }
}