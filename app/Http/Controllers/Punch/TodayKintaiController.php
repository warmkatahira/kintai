<?php

namespace App\Http\Controllers\Punch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class TodayKintaiController extends Controller
{
    public function index()
    {
        // 自拠点の従業員情報を取得
        $employees = Employee::getSpecifyBase(Auth::user()->base_id)
                        ->where('is_available', 1)
                        ->orderBy('employee_category_id', 'asc')
                        ->orderBy('employee_no', 'asc')
                        ->get();
        return view('punch.today_kintai.index')->with([
            'employees' => $employees,
        ]);
    }
}
