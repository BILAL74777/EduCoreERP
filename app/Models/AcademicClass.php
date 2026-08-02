<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicClass extends BaseModel
{
    protected $table = 'academic_classes';

    protected $fillable = [
        'discipline_id',
        'code',
        'name',
        'level',
        'next_class_id',
        'is_active',
        'description',
    ];

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    /**
     * Next Academic Class
     */
    public function nextClass(): BelongsTo
    {
        return $this->belongsTo(
            AcademicClass::class,
            'next_class_id'
        );
    }

    /**
     * Previous Academic Classes (Optional)
     */
    public function previousClasses(): HasMany
    {
        return $this->hasMany(
            AcademicClass::class,
            'next_class_id'
        );
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(
            StudentEnrollment::class,
            'class_id'
        );
    }
}