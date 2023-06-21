<?php

namespace App\Http\Middleware\Available;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class CustomerUpdateAvailable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 荷主を取得
        $customer = Customer::getSpecify($request->customer_id)->first();
        // 自拠点の荷主ではない場合
        if(Auth::user()->base_id != $customer->base_id){
            abort(403, 'Access Denied');
        }
        return $next($request);
    }
}
