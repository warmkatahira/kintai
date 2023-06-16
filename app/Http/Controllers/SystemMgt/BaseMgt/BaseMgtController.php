<?php

namespace App\Http\Controllers\SystemMgt\BaseMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Base;

class BaseMgtController extends Controller
{
    public function index()
    {
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        return view('base_mgt.index')->with([
            'bases' => $bases,
        ]);
    }
}
