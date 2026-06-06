<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class FuelProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'unit',
        'purchase_price',
        'sale_price',
        'status',
    ];
}