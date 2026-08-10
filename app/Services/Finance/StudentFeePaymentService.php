<?php

namespace App\Services\Finance;

use App\Models\Student;
use App\Models\StudentFeePayment;
use App\Models\StudentFeeStructure;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentFeePaymentService
{
    /**
     * Collect a student fee payment.
     */
    public function collect(array $data): StudentFeePayment
    {
        return DB::transaction(function () use ($data) {

            $feeStructure = StudentFeeStructure::query()
                ->lockForUpdate()
                ->findOrFail($data['fee_structure_id']);

            /*
            |--------------------------------------------------------------------------
            | Validate Student
            |--------------------------------------------------------------------------
            */

            if (
                (string) $feeStructure->student_id !==
                (string) $data['student_id']
            ) {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'The selected fee structure does not belong to this student.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Validate Enrollment
            |--------------------------------------------------------------------------
            */

            if (
                (string) $feeStructure->enrollment_id !==
                (string) $data['enrollment_id']
            ) {
                throw ValidationException::withMessages([
                    'enrollment_id' =>
                        'The selected enrollment does not belong to this fee structure.',
                ]);
            }

            $amount = (float) $data['amount'];

            $annualFee = (float) $feeStructure->annual_fee;

            /*
            |--------------------------------------------------------------------------
            | Calculate actual paid amount from payment history
            |--------------------------------------------------------------------------
            */

            $historicalPaid = (float) $feeStructure
                ->payments()
                ->sum('amount');

            /*
            |--------------------------------------------------------------------------
            | Use the greater of cached/current paid value and payment history
            |--------------------------------------------------------------------------
            */

            $paidFee = max(
                (float) $feeStructure->paid_fee,
                $historicalPaid
            );

            $outstanding = max(
                0,
                $annualFee - $paidFee
            );

            /*
            |--------------------------------------------------------------------------
            | Validate amount
            |--------------------------------------------------------------------------
            */

            if ($amount <= 0) {
                throw ValidationException::withMessages([
                    'amount' =>
                        'Payment amount must be greater than zero.',
                ]);
            }

            if ($amount > $outstanding) {
                throw ValidationException::withMessages([
                    'amount' =>
                        'Payment amount cannot exceed the outstanding fee of ' .
                        number_format($outstanding, 2),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */

            $payment = StudentFeePayment::create([
                'student_id' => $feeStructure->student_id,
                'enrollment_id' => $feeStructure->enrollment_id,
                'fee_structure_id' => $feeStructure->id,
                'amount' => $amount,
                'payment_date' =>
                    $data['payment_date']
                    ?? now()->toDateString(),
                'note' => $data['note'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Fee Structure
            |--------------------------------------------------------------------------
            */

            $newPaidFee = $paidFee + $amount;

            $newNetFee = max(
                0,
                $annualFee - $newPaidFee
            );

            $feeStructure->update([
                'paid_fee' => $newPaidFee,
                'net_fee' => $newNetFee,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Return complete payment
            |--------------------------------------------------------------------------
            */

            return $payment->load([
                'student.photo',
                'student.currentEnrollment.academicClass.discipline.faculty',
                'enrollment.academicClass.discipline.faculty',
                'feeStructure.academicClass.discipline.faculty',
                'feeStructure.session',
            ]);
        });
    }

    /**
     * Get complete student fee profile.
     */
    public function getStudentFee(string $studentId): array
    {
        $student = Student::query()
            ->with([
                'photo',
                'currentEnrollment.academicClass.discipline.faculty',
            ])
            ->findOrFail($studentId);

        /*
        |--------------------------------------------------------------------------
        | Fee Structures
        |--------------------------------------------------------------------------
        */

        $feeStructures = StudentFeeStructure::query()
            ->with([
                'session',
                'academicClass.discipline.faculty',
                'enrollment.academicClass.discipline.faculty',
            ])
            ->where('student_id', $studentId)
            ->orderBy('session_id')
            ->orderBy('class_id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Calculate each structure
        |--------------------------------------------------------------------------
        */

        $feeStructures->each(function ($structure) {

            $paid = (float) $structure
                ->payments()
                ->sum('amount');

            $annual = (float) $structure->annual_fee;

            $due = max(
                0,
                $annual - $paid
            );

            $structure->setAttribute(
                'calculated_paid',
                number_format($paid, 2, '.', '')
            );

            $structure->setAttribute(
                'calculated_due',
                number_format($due, 2, '.', '')
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Payment History
        |--------------------------------------------------------------------------
        */

        $payments = StudentFeePayment::query()
            ->with([
                'student.photo',
                'enrollment.academicClass.discipline.faculty',
                'feeStructure.academicClass.discipline.faculty',
                'feeStructure.session',
            ])
            ->where('student_id', $studentId)
            ->latest('payment_date')
            ->latest('created_at')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Overall Totals
        |--------------------------------------------------------------------------
        */

        $totalFee = $feeStructures->sum(
            fn ($structure) =>
                (float) $structure->annual_fee
        );

        $totalPaid = $payments->sum(
            fn ($payment) =>
                (float) $payment->amount
        );

        $totalDue = max(
            0,
            $totalFee - $totalPaid
        );

        return [
            'student' => $student,

            'fee_structures' => $feeStructures,

            'payments' => $payments,

            'totals' => [
                'total_fee' => number_format(
                    $totalFee,
                    2,
                    '.',
                    ''
                ),

                'total_paid' => number_format(
                    $totalPaid,
                    2,
                    '.',
                    ''
                ),

                'total_due' => number_format(
                    $totalDue,
                    2,
                    '.',
                    ''
                ),
            ],
        ];
    }

    /**
     * Delete payment and reverse fee.
     */
    public function delete(
        StudentFeePayment $payment
    ): void {

        DB::transaction(function () use ($payment) {

            $feeStructure = StudentFeeStructure::query()
                ->lockForUpdate()
                ->findOrFail(
                    $payment->fee_structure_id
                );

            $newPaidFee = max(
                0,
                (float) $feeStructure->paid_fee -
                (float) $payment->amount
            );

            $newNetFee = max(
                0,
                (float) $feeStructure->annual_fee -
                $newPaidFee
            );

            $feeStructure->update([
                'paid_fee' => $newPaidFee,
                'net_fee' => $newNetFee,
            ]);

            $payment->delete();
        });
    }
}