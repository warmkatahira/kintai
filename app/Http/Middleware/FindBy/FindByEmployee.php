<?php

namespace App\Http\Middleware\FindBy;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Employee;

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
        return $next($request);
    }
}
