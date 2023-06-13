<?php

namespace App\Http\Middleware\FindBy;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Kintai;

class FindByKintai
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 勤怠IDを取得
        $kintai_id = $request->kintai_id;
        // 勤怠が見つからない場合は例外をスローする
        Kintai::getSpecify($kintai_id)->firstOrFail();
        return $next($request);
    }
}
