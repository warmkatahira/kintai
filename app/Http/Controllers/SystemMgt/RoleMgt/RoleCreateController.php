<?php

namespace App\Http\Controllers\SystemMgt\RoleMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RoleCreateRequest;
use App\Services\RoleMgt\RoleCreateService;
use App\Enums\RoleEnum;

class RoleCreateController extends Controller
{
    public function index(Request $request)
    {
        // ステータス情報を取得($xx->aaの形式でアクセスできるようにしている)
        $roles = collect(RoleEnum::PULLDOWN_LIST)->map(function ($item) {
            return (object) $item;
        });
        return view('role_mgt.create')->with([
            'roles' => $roles,
        ]);
    }

    public function create(RoleCreateRequest $request)
    {
        // インスタンス化
        $RoleCreateService = new RoleCreateService;
        // レコードを変更
        $RoleCreateService->createRole($request);
        return redirect()->route('role_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => '権限を追加しました。',
        ]);
    }
}
