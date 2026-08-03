<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends BaseModel
{

    protected $fillable = [
        'photo',
        'form_b',
        'admission_no',
        'registration_no',
        'date_of_admission',
        'name',
        'father_name',
        'father_cnic',
        'father_occupation',
        'gender',
        'date_of_birth',
        'religion',
        'tribe_cast',
        'contact_number',
        'address',
        'is_orphan',
        'is_father_shaheed',
        'remarks',
    ];

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Upload::class, 'photo');
    }

    public function formB(): BelongsTo
    {
        return $this->belongsTo(Upload::class, 'form_b');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class);
    }

    public function currentEnrollment()
    {
        return $this->hasOne(StudentEnrollment::class)
            ->where('is_current', true);
    }
    public function feeStructure()
    {
        return $this->hasOne(StudentFeeStructure::class);
    }
}