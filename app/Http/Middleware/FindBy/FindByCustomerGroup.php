<?php

namespace App\Http\Middleware\FindBy;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CustomerGroup;

class FindByCustomerGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 荷主グループIDを取得
        $customer_group_id = $request->customer_group_id;
        // 荷主グループが見つからない場合は例外をスローする
        CustomerGroup::getSpecify($customer_group_id)->firstOrFail();
        return $next($request);
    }
}
