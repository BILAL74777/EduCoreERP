<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Traits\ApiResponse;

abstract class BaseController extends Controller
{
    use ApiResponse;
}