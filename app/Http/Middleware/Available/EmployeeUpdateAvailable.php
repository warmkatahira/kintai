<?php

namespace App\Http\Middleware\Available;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class EmployeeUpdateAvailable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 従業員を取得
        $employee = Employee::getSpecify($request->employee_id)->first();
        // 自拠点の従業員ではない場合
        if(Auth::user()->base_id != $employee->base_id){
            abort(403, 'Access Denied');
        }
        return $next($request);
    }
}
