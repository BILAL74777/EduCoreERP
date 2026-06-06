<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Partner extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'cnic',
        'address',
        'share_percentage',
        'opening_investment',
        'status',
    ];
}