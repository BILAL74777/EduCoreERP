<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class GateAttendance extends BaseModel
{
    protected $table = 'gate_attendances';

    protected $fillable = [
        'student_enrollment_id',
        'event_type',
        'method',
        'event_time',
        'gate_id',
        'device_id',
        'marked_by',
        'remarks',
    ];

    protected $casts = [
        'event_time' => 'datetime',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(
            StudentEnrollment::class,
            'student_enrollment_id'
        );
    }
}
