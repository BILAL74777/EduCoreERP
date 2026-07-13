<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Year extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'faculty_id',
        'discipline_id',
        'name',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}