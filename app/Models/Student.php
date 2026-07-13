<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'form_b',
        'father_cnic',
        'name',
        'father_name',
        'father_occupation',
        'gender',
        'date_of_birth',
        'religion',
        'tribe_cast',
        'admission_no',
        'date_of_admission',
        'faculty_id',
        'discipline_id',
        'year_id',
        'class_admitted_in',
        'current_class',
        'status',
        'date_of_withdrawal',
        'is_orphan',
        'is_father_shaheed',
        'contact_number',
        'address',
        'photo',
        'remarks',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_admission' => 'date',
        'date_of_withdrawal' => 'date',
        'is_orphan' => 'boolean',
        'is_father_shaheed' => 'boolean',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function admittedClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_admitted_in');
    }

    public function currentClass()
    {
        return $this->belongsTo(StudentClass::class, 'current_class');
    }

    public function formB()
    {
        return $this->belongsTo(Upload::class, 'form_b');
    }

    public function photoUpload()
    {
        return $this->belongsTo(Upload::class, 'photo');
    }
    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}