<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BaseModel extends Model
{
    use SoftDeletes;

    /**
     * The primary key type.
     */
    protected $keyType = 'string';

    /**
     * IDs are generated manually.
     */
    public $incrementing = false;

    /**
     * Guard nothing.
     */
    protected $guarded = [];
}