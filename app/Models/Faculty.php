<?php

namespace App\Models;

class Faculty extends BaseModel
{
    protected $table = 'faculties';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function disciplines()
    {
        return $this->hasMany(Discipline::class, 'faculty_id');
    }
}