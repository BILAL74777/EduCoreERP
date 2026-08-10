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

            /*
            |--------------------------------------------------------------------------
            | Fee Structure
            |--------------------------------------------------------------------------
            */

            $feeStructure = StudentFeeStructure::query()
                ->lockForUpdate()
                ->findOrFail($data['fee_structure_id']);

            /*
            |--------------------------------------------------------------------------
            | Validate Student / Enrollment
            |--------------------------------------------------------------------------
            */

            if ($feeStructure->student_id !== $data['student_id']) {
                throw ValidationException::withMessages([
                    'student_id' => 'The selected fee structure does not belong to this student.',
                ]);
            }

            if ($feeStructure->enrollment_id !== $data['enrollment_id']) {
                throw ValidationException::withMessages([
                    'enrollment_id' => 'The selected enrollment does not belong to this fee structure.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Payment Amount
            |--------------------------------------------------------------------------
            */

            $amount = (float) $data['amount'];

            $outstanding = max(
                0,
                (float) $feeStructure->annual_fee -
                (float) $feeStructure->paid_fee
            );

            if ($amount <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Payment amount must be greater than zero.',
                ]);
            }

            if ($amount > $outstanding) {
                throw ValidationException::withMessages([
                    'amount' => "Payment amount cannot exceed the outstanding fee of {$outstanding}.",
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */

            $payment = StudentFeePayment::create([
                'student_id'       => $feeStructure->student_id,
                'enrollment_id'    => $feeStructure->enrollment_id,
                'fee_structure_id' => $feeStructure->id,
                'amount'           => $amount,
                'payment_date'     => $data['payment_date'] ?? now()->toDateString(),
                'note'             => $data['note'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Fee Structure
            |--------------------------------------------------------------------------
            */

            $newPaidFee = (float) $feeStructure->paid_fee + $amount;

            $newNetFee = max(
                0,
                (float) $feeStructure->annual_fee - $newPaidFee
            );

            $feeStructure->update([
                'paid_fee' => $newPaidFee,
                'net_fee'  => $newNetFee,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Return
            |--------------------------------------------------------------------------
            */

            return $payment->load([
                'student',
                'enrollment.academicClass.discipline.faculty',
                'feeStructure',
            ]);
        });
    }

    /**
     * Get fee information for a student.
     */
    public function getStudentFee(string $studentId): ?StudentFeeStructure
    {
        return StudentFeeStructure::query()
            ->with([
                'student',
                'enrollment.academicClass.discipline.faculty',
            ])
            ->where('student_id', $studentId)
            ->latest()
            ->first();
    }

    /**
     * Get payment history for a fee structure.
     */
    public function getPaymentHistory(string $feeStructureId)
    {
        return StudentFeePayment::query()
            ->with([
                'student',
                'enrollment.academicClass.discipline.faculty',
            ])
            ->where('fee_structure_id', $feeStructureId)
            ->latest('payment_date')
            ->latest('created_at')
            ->get();
    }
    public function delete(StudentFeePayment $payment): void
{
    DB::transaction(function () use ($payment) {

        /*
        |--------------------------------------------------------------------------
        | Lock Fee Structure
        |--------------------------------------------------------------------------
        */

        $feeStructure = StudentFeeStructure::query()
            ->lockForUpdate()
            ->findOrFail($payment->fee_structure_id);

        /*
        |--------------------------------------------------------------------------
        | Reverse Payment
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Update Fee Structure
        |--------------------------------------------------------------------------
        */

        $feeStructure->update([
            'paid_fee' => $newPaidFee,
            'net_fee'  => $newNetFee,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Delete Payment
        |--------------------------------------------------------------------------
        */

        $payment->delete();
    });
}
}