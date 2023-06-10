<?php

namespace App\Services\UserMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserMgtService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_base_id',
            'search_user_name'
        ]);
        return;
    }

    public function setDefaultCondition()
    {
        // 初期条件をセット
        session(['search_base_id' => Auth::user()->base_id]);
        return;
    }

    public function setSearchCondition($request)
    {
        // セッションに検索条件をセット
        session(['search_base_id' => $request->search_base_id]);
        session(['search_user_name' => $request->search_user_name]);
        return;
    }

    public function getUserSearch()
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // ユーザーのクエリをセット
        $users = User::query();
        // 拠点条件がある場合
        if (session('search_base_id') != 0) {
            $users->where('base_id', session('search_base_id'));
        }
        // 荷主名条件がある場合
        if (!empty(session('search_user_name'))) {
            $users->where('last_name', 'LIKE', '%'.session('search_user_name').'%')
                    ->orWhere('first_name', 'LIKE', '%'.session('search_user_name').'%');
        }
        // 拠点IDと荷主IDで並び替え
        $users = $users->orderBy('base_id', 'asc')->orderBy('id', 'asc')->paginate(50);
        return $users;
    }
}