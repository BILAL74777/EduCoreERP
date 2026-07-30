<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discipline extends BaseModel
{
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(AcademicClass::class, 'discipline_id');
    }
}