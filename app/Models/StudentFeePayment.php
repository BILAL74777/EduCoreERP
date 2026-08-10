<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentFeePayment extends BaseModel
{
    protected $table = 'student_fee_payments';

    protected $fillable = [
        'student_id',
        'enrollment_id',
        'fee_structure_id',
        'amount',
        'payment_date',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(
            StudentEnrollment::class,
            'enrollment_id'
        );
    }

    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(
            StudentFeeStructure::class,
            'fee_structure_id'
        );
    }
}