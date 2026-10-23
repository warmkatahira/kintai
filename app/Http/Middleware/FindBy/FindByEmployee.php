<?php

namespace App\Http\Middleware\FindBy;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Employee;
use App\Services\KintaiMgt\KintaiDispScopeService;

class FindByEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 従業員IDを取得
        $employee_id = $request->employee_id;
        // 従業員が見つからない場合は例外をスローする
        Employee::getSpecify($employee_id)->firstOrFail();
        // 勤怠表示範囲外の従業員の場合は403
        $query = Employee::where('employees.employee_id', $employee_id);
        if(!(new KintaiDispScopeService)->applyScope($query)->exists()){
            abort(403);
        }
        return $next($request);
    }
}
