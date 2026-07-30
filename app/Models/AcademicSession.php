<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicSession extends BaseModel
{
    protected $table = 'academic_sessions';

    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class, 'session_id');
    }
}