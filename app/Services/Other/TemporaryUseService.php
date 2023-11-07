<?php

namespace App\Services\Other;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\TemporaryUse;
use App\Models\TemporaryCompany;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'search_customer_name',
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
        session(['search_customer_name' => $request->search_customer_name]);
        return;
    }

    // 派遣利用情報を取得
    public function getTemporaryUseSearch()
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 利用日条件でデータを抽出
        $temporary_uses = TemporaryUse::whereDate('date', '>=', session('search_date_from'))
                            ->whereDate('date', '<=', session('search_date_to'))
                            ->join('customers', 'customers.customer_id', 'temporary_uses.customer_id')
                            ->select('temporary_uses.*', 'customers.customer_name');
        // 拠点条件がある場合
        if (session('search_base_id')  != null) {
            $temporary_uses->where('temporary_uses.base_id', session('search_base_id'));
        }
        // 派遣会社条件がある場合
        if (session('search_temporary_company_id')  != null) {
            $temporary_uses->where('temporary_company_id', session('search_temporary_company_id'));
        }
        // 荷主名条件がある場合
        if (session('search_customer_name') != null) {
            $temporary_uses->where('customer_name', 'LIKE', '%'.session('search_customer_name').'%');
        }
        // 並び替え
        $temporary_uses->orderBy('date', 'asc')->orderBy('base_id', 'asc')->orderBy('customer_id', 'asc')->orderBy('temporary_company_id', 'asc');
        // 取得
        return with([
            'temporary_uses' => $temporary_uses->paginate(50),
            'temporary_uses_download' => $temporary_uses,
        ]);
    }

    // 合計情報を取得
    public function getTemporaryUseTotal($temporary_uses)
    {
        // 合計人数
        $total_people = $temporary_uses->sum('people');
        // 合計稼働時間
        $total_working_time = $temporary_uses->sum('working_time');
        // 合計金額
        $total_amount = $temporary_uses->sum(function ($item) {
            if ($item->temporary_company->amount_calc_item === 'working_time') {
                return ($item->working_time / 60) * $item->hourly_rate;
            } elseif ($item->temporary_company->amount_calc_item === 'people') {
                return $item->people * $item->hourly_rate;
            }
        });
        return compact('total_people', 'total_working_time', 'total_amount');
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

    // ダウンロードする派遣利用データを取得
    public function getDownloadTemporaryUse($temporary_uses_download)
    {
        // チャンクサイズを指定
        $chunkSize = 2000;
        $response = new StreamedResponse(function () use ($chunkSize, $temporary_uses_download) {
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // ヘッダー行を書き込む
            $headerRow = [
                '利用日',
                '拠点',
                '派遣会社',
                '荷主名',
                '人数',
                '稼働時間',
                '時給単価',
                '金額',
                '入力者',
                '入力日',
                '入力時間',
            ];
            fputcsv($handle, $headerRow);
            // レコードをチャンクごとに書き込む
            $temporary_uses_download->chunk($chunkSize, function ($temporary_uses) use ($handle) {
                foreach ($temporary_uses as $temporary_use) {
                    // 合計金額を計算
                    if($temporary_use->temporary_company->amount_calc_item == 'working_time'){
                        $amount = ceil(($temporary_use->working_time / 60) * $temporary_use->hourly_rate);
                    }
                    if($temporary_use->temporary_company->amount_calc_item == 'people'){
                        $amount = ceil($temporary_use->people * $temporary_use->hourly_rate);
                    }
                    $row = [
                        CarbonImmutable::parse($temporary_use->date)->format('Y年m月d日'),
                        $temporary_use->base->base_name,
                        $temporary_use->temporary_company->temporary_company_name,
                        $temporary_use->customer->customer_name,
                        $temporary_use->people,
                        ($temporary_use->working_time / 60),
                        $temporary_use->hourly_rate,
                        $amount,
                        $temporary_use->user->last_name.' '.$temporary_use->user->first_name,
                        CarbonImmutable::parse($temporary_use->created_at)->format('Y年m月d日'),
                        CarbonImmutable::parse($temporary_use->created_at)->format('h時i分s秒'),
                    ];
                    fputcsv($handle, $row);
                }
            });
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}