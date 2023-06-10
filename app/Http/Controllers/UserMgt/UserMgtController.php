<?php

namespace App\Http\Controllers\UserMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserMgt\UserMgtService;
use App\Services\CommonService;
use App\Models\Base;
use App\Models\User;
use Carbon\CarbonImmutable;

class UserMgtController extends Controller
{
    public function index()
    {
        // インスタンス化
        $UserMgtService = new UserMgtService;
        // セッションを削除
        $UserMgtService->deleteSearchSession();
        // 初期条件をセット
        $UserMgtService->setDefaultCondition();
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 荷主を取得
        $users = $UserMgtService->getUserSearch();
        return view('user_mgt.index')->with([
            'bases' => $bases,
            'users' => $users,
        ]);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $UserMgtService = new UserMgtService;
        // セッションを削除
        $UserMgtService->deleteSearchSession();
        // 検索条件をセット
        $UserMgtService->setSearchCondition($request);
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 荷主を取得
        $users = $UserMgtService->getUserSearch();
        return view('user_mgt.index')->with([
            'bases' => $bases,
            'users' => $users,
        ]);
    }
}
