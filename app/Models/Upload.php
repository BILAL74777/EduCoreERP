<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'file_original_name',
        'file_name',
        'file_size',
        'extension',
        'mime_type',
        'disk',
        'folder',
    ];

    protected $appends = [
        'url',
    ];

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->folder . '/' . $this->file_name);
    }
}