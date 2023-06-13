<?php

namespace App\Http\Middleware\FindBy;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Customer;

class FindByCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 荷主IDを取得
        $customer_id = $request->customer_id;
        // 荷主が見つからない場合は例外をスローする
        Customer::getSpecify($customer_id)->firstOrFail();
        return $next($request);
    }
}
