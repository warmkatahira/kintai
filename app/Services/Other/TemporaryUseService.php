<?php

namespace App\Services\Other;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\TemporaryUse;
use App\Models\TemporaryCompany;

class TemporaryUseService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_date_from',
            'search_date_to',
            'search_base_id',
            'search_temporary_company_id',
        ]);
        return;
    }

    public function setDefaultCondition()
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 初期条件をセット
        session(['search_date_from' => $nowDate->toDateString()]);
        session(['search_date_to' => $nowDate->toDateString()]);
        session(['search_base_id' => Auth::user()->base_id]);
        return;
    }

    public function setSearchCondition($request)
    {
        // 検索条件をセット
        session(['search_date_from' => $request->search_date_from]);
        session(['search_date_to' => $request->search_date_to]);
        session(['search_base_id' => $request->search_base_id]);
        session(['search_temporary_company_id' => $request->search_temporary_company_id]);
        return;
    }

    // 派遣利用情報を取得
    public function getTemporaryUseSearch()
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 利用日条件でデータを抽出
        $temporary_uses = TemporaryUse::whereDate('date', '>=', session('search_date_from'))
                        ->whereDate('date', '<=', session('search_date_to'));
        // 拠点条件がある場合
        if (session('search_base_id')  != null) {
            $temporary_uses->where('base_id', session('search_base_id'));
        }
        // 派遣会社条件がある場合
        if (session('search_temporary_company_id')  != null) {
            $temporary_uses->where('temporary_company_id', session('search_temporary_company_id'));
        }
        // 
        return $temporary_uses->orderBy('date', 'asc')->orderBy('base_id', 'asc')->orderBy('temporary_company_id', 'asc')->paginate(50);
    }

    // 派遣利用を登録
    public function createTemporaryUse($request)
    {
        // 派遣会社を取得
        $temporary_company = TemporaryCompany::getSpecify($request->temporary_company_id)->first();
        // 登録(working timeは分数で管理するのでかけている)
        TemporaryUse::create([
            'temporary_company_id' => $request->temporary_company_id,
            'base_id' => Auth::user()->base_id,
            'date' => $request->date,
            'customer_id' => $request->customer_id,
            'people' => $request->people_input,
            'working_time' => $request->working_time_input * 60,
            'hourly_rate' => $temporary_company->hourly_rate,
            'register_user' => Auth::user()->id,
        ]);
        return;
    }

    // 派遣利用を削除
    public function deleteTemporaryUse($temporary_use_id)
    {
        TemporaryUse::where('temporary_use_id', $temporary_use_id)->delete();
        return;
    }
}