<?php

namespace App\Http\Middleware\Available;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Kintai;

class CommentOperationAllAvailable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 勤怠を取得
        $kintai = Kintai::getSpecify($request->kintai_id)->first();
        // コメント操作が無効である
        if(Auth::user()->role->is_comment_operation_available == 0){
            abort(403, 'Access Denied');
        }
        // 自拠点でなく、全勤怠操作が無効である
        if(Auth::user()->base_id != $kintai->employee->base_id && Auth::user()->role->is_all_kintai_operation_available == 0){
            abort(403, 'Access Denied');
        }
        // ロックがかかっている、ロック後の勤怠操作が無効である
        if(!is_null($kintai->locked_at) && Auth::user()->role->is_lock_kintai_operation_available == 0){
            abort(403, 'Access Denied');
        }
        return $next($request);
    }
}
