<?php

namespace App\Http\Controllers\SystemMgt\UserMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserMgt\UserUpdateService;
use App\Models\User;
use App\Models\Base;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Enums\StatusEnum;
use App\Enums\NoIpCheckEnum;

class UserUpdateController extends Controller
{
    public function index(Request $request)
    {
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 権限情報を取得
        $roles = Role::getAll()->get();
        // ステータス情報を取得($xx->aaの形式でアクセスできるようにしている)
        $statuses = collect(StatusEnum::PULLDOWN_LIST)->map(function ($item) {
            return (object) $item;
        });
        // IPチェックなし情報を取得($xx->aaの形式でアクセスできるようにしている)
        $no_ip_checks = collect(NoIpCheckEnum::PULLDOWN_LIST)->map(function ($item) {
            return (object) $item;
        });
        // ユーザーを取得
        $user = User::getSpecify($request->id)->first();
        return view('user_mgt.update')->with([
            'bases' => $bases,
            'roles' => $roles,
            'statuses' => $statuses,
            'no_ip_checks' => $no_ip_checks,
            'user' => $user,
        ]);
    }

    public function update(UserUpdateRequest $request)
    {
        // インスタンス化
        $UserUpdateService = new UserUpdateService;
        // レコードを変更
        $UserUpdateService->updateUser($request);
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => 'ユーザー情報を更新しました。',
        ]);
    }

    public function no_ip_check_bulk_update(Request $request)
    {
        // バリデーション
        $request->validate([
            'bulk_base_id' => 'required|exists:bases,base_id',
            'no_ip_check' => 'required|in:'.NoIpCheckEnum::DEACTIVE.','.NoIpCheckEnum::ACTIVE,
        ], [], [
            'bulk_base_id' => '拠点',
            'no_ip_check' => 'IPチェックなし',
        ]);
        // インスタンス化
        $UserUpdateService = new UserUpdateService;
        // 拠点のユーザー（ステータスが有効のみ）のIPチェックなしを一括更新
        $count = $UserUpdateService->updateNoIpCheckByBase($request->bulk_base_id, $request->no_ip_check);
        // 拠点名を取得
        $base = Base::getSpecify($request->bulk_base_id)->first();
        return redirect(session('back_url_1', route('user_mgt.index')))->with([
            'alert_type' => 'success',
            'alert_message' => $base->base_name.'の有効ユーザー（'.$count.'件）のIPチェックなしを「'.NoIpCheckEnum::get_jp($request->no_ip_check).'」に更新しました。',
        ]);
    }
}
