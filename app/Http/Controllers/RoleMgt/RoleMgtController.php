<?php

namespace App\Http\Controllers\RoleMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleMgtController extends Controller
{
    public function index()
    {
        // 権限情報を取得
        $roles = Role::getAll()->get();
        return view('role_mgt.index')->with([
            'roles' => $roles,
        ]);
    }
}
