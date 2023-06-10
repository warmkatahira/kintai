<?php

namespace App\Http\Controllers\RoleMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RoleCreateRequest;
use App\Services\RoleMgt\RoleUpdateService;
use App\Models\Role;
use App\Enums\RoleEnum;

class RoleUpdateController extends Controller
{
    public function index(Request $request)
    {
        // 権限を取得
        $role = Role::getSpecify($request->role_id)->first();
        // ステータス情報を取得($xx->aaの形式でアクセスできるようにしている)
        $roles = collect(RoleEnum::PULLDOWN_LIST)->map(function ($item) {
            return (object) $item;
        });
        return view('role_mgt.update')->with([
            'role' => $role,
            'roles' => $roles,
        ]);
    }

    public function update(RoleCreateRequest $request)
    {
        // インスタンス化
        $RoleUpdateService = new RoleUpdateService;
        // レコードを変更
        $RoleUpdateService->updateRole($request);
        return redirect()->route('role_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => '権限情報を更新しました。',
        ]);
    }
}
