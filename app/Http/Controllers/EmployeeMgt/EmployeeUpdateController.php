<?php

namespace App\Http\Controllers\EmployeeMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Services\EmployeeMgt\EmployeeUpdateService;
use App\Models\EmployeeCategory;
use App\Models\Employee;
use App\Models\Base;
use Illuminate\Support\Facades\Auth;

class EmployeeUpdateController extends Controller
{
    public function __construct()
    {
        // ミドルウェアを適用するメソッドを指定
        $this->middleware('FindByEmployee')->only(['index', 'update']);
    }

    public function index(Request $request)
    {
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 従業員区分を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 従業員の情報を取得
        $employee = Employee::getSpecify($request->employee_id)->first();
        return view('employee_mgt.update')->with([
            'bases' => $bases,
            'employee_categories' => $employee_categories,
            'employee' => $employee,
        ]);
    }

    public function update(EmployeeUpdateRequest $request)
    {
        // インスタンス化
        $EmployeeUpdateService = new EmployeeUpdateService;
        // レコードを変更
        $EmployeeUpdateService->updateEmployee($request);
        return redirect(session('back_url_2'))->with([
            'alert_type' => 'success',
            'alert_message' => '従業員情報を更新しました。',
        ]);
    }
}
