<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends BaseModel
{
    protected $table = 'attendance_sessions';

    protected $fillable = [
        'session_id',
        'class_id',
        'section',
        'attendance_date',
        'marked_by',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(
            AcademicSession::class,
            'session_id'
        );
    }

    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(
            AcademicClass::class,
            'class_id'
        );
    }

    public function records(): HasMany
    {
        return $this->hasMany(
            AttendanceRecord::class,
            'attendance_session_id'
        );
    }
}
