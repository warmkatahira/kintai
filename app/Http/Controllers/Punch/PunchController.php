<?php

namespace App\Http\Controllers\Punch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PunchController extends Controller
{
    public function index()
    {
        return view('punch.index');
    }
}
