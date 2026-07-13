<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentClass extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function admittedStudents()
    {
        return $this->hasMany(Student::class, 'class_admitted_in');
    }

    public function currentStudents()
    {
        return $this->hasMany(Student::class, 'current_class');
    }
}