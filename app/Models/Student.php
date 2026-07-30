<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends BaseModel
{
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
}