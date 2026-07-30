<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends BaseModel
{
    public function disciplines(): HasMany
    {
        return $this->hasMany(Discipline::class);
    }
}