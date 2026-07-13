<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function disciplines()
    {
        return $this->hasMany(Discipline::class);
    }

    public function years()
    {
        return $this->hasMany(Year::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}