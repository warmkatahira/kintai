<?php

namespace App\Http\Controllers\EmployeeMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeCreateRequest;
use App\Services\EmployeeMgt\EmployeeCreateService;
use App\Models\EmployeeCategory;
use App\Models\Base;

class EmployeeCreateController extends Controller
{
    public function index(Request $request)
    {
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 従業員区分を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        return view('employee_mgt.create')->with([
            'bases' => $bases,
            'employee_categories' => $employee_categories,
        ]);
    }

    public function create(EmployeeCreateRequest $request)
    {
        // インスタンス化
        $EmployeeCreateService = new EmployeeCreateService;
        // レコードを変更
        $EmployeeCreateService->createEmployee($request);
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '従業員を追加しました。',
        ]);
    }
}
