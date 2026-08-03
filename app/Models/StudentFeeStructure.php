<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentFeeStructure extends BaseModel
{
    protected $table = 'student_fee_structures';

    protected $fillable = [

        'student_id', 
        'enrollment_id',  
        'session_id', 
        'class_id', 
        'annual_fee', 
        'discount', 
        'net_fee', 
        'remarks',

    ];

    protected function casts(): array
    {
        return [
            'annual_fee' => 'decimal:2',
            'discount' => 'decimal:2',
            'net_fee' => 'decimal:2',
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
}