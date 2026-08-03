<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEnrollment extends BaseModel
{

    protected $fillable = [
        'student_id',
        'session_id',
        'class_id',
        'faculty_id',
        'discipline_id',
        'roll_no',
        'status',
        'is_current',
        'remarks',
    ];
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    /*
     |-------------------------------------------------------
     | Shortcut Relationships
     |-------------------------------------------------------
     */

    public function discipline()
    {
        return $this->academicClass->discipline();
    }

    public function faculty()
    {
        return $this->academicClass->discipline->faculty();
    }
    public function feeStructure()
    {
        return $this->hasOne(
            StudentFeeStructure::class,
            'enrollment_id'
        );
    }
}   