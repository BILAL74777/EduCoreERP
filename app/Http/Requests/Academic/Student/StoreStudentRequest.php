<?php

namespace App\Http\Requests\Academic\Student;

use App\Models\AcademicClass;
use App\Models\Discipline;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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

            /*
            |--------------------------------------------------------------------------
            | Admission
            |--------------------------------------------------------------------------
            */

            'admission_no' => [
                'required',
                'string',
                'max:100',
                'unique:students,admission_no',
            ],

            'registration_no' => [
                'nullable',
                'string',
                'max:100',
                'unique:students,registration_no',
            ],

            'section' => [
                'nullable',
                'string',
                'max:100',
            ],

            'date_of_admission' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            /*
            |--------------------------------------------------------------------------
            | Personal
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'father_name' => [
                'required',
                'string',
                'max:255',
            ],

            'father_cnic' => [
                'required',
                'regex:/^\d{5}-\d{7}-\d$/',
            ],

            'father_occupation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'gender' => [
                'required',
                'in:Male,Female,Other',
            ],

            'date_of_birth' => [
                'required',
                'date',
                'before:today',
            ],

            'religion' => [
                'nullable',
                'string',
                'max:100',
            ],

            'tribe_cast' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            'contact_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'guardian_contact_number' => [
                'required',
                'string',
                'max:20',
            ],

            'address' => [
                'required',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Enrollment
            |--------------------------------------------------------------------------
            */

            'faculty_id' => [
                'required',
                'exists:faculties,id',
            ],

            'discipline_id' => [
                'required',
                'exists:disciplines,id',
            ],

            'session_id' => [
                'required',
                'exists:academic_sessions,id',
            ],

            'class_id' => [
                'required',
                'exists:academic_classes,id',
            ],

            'roll_no' => [
                'required',
                'string',
                'max:30',
            ],

            /*
            |--------------------------------------------------------------------------
            | Fee Structure
            |--------------------------------------------------------------------------
            */

            'annual_fee' => [
                'required',
                'numeric',
                'min:0',
            ],

            'paid_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Documents
            |--------------------------------------------------------------------------
            */

            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'form_b' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Flags
            |--------------------------------------------------------------------------
            */

            'is_orphan' => [
                'nullable',
                'boolean',
            ],

            'is_father_shaheed' => [
                'nullable',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Additional Validation
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $discipline = Discipline::find($this->discipline_id);

            if (
                $discipline &&
                $discipline->faculty_id != $this->faculty_id
            ) {
                $validator->errors()->add(
                    'discipline_id',
                    'Selected discipline does not belong to the selected faculty.'
                );
            }

            $class = AcademicClass::find($this->class_id);

            if (
                $class &&
                $class->discipline_id != $this->discipline_id
            ) {
                $validator->errors()->add(
                    'class_id',
                    'Selected class does not belong to the selected discipline.'
                );
            }

            if (
                $this->filled('paid_fee') &&
                $this->paid_fee > $this->annual_fee
            ) {
                $validator->errors()->add(
                    'paid_fee',
                    'Paid fee cannot exceed annual fee.'
                );
            }
        });
    }
}