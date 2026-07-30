<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicClass extends BaseModel
{
    protected $table = 'academic_classes';

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class, 'class_id');
    }
}