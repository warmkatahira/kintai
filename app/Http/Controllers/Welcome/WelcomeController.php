<?php

namespace App\Http\Controllers\Welcome;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        // ログインされている場合
        if(Auth::check()){
            return redirect()->route('top.index');
        }
        // ログインされていない場合
        if(!Auth::check()){
            return view('welcome');
        }
    }
}
