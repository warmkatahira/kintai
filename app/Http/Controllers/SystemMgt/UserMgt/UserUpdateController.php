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
}
