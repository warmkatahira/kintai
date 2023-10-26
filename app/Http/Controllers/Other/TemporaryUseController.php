<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TemporaryCompany;
use App\Models\Customer;
use App\Models\TemporaryUse;
use App\Models\Base;
use App\Services\Other\TemporaryUseService;
use Carbon\CarbonImmutable;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TemporaryUseController extends Controller
{
    public function index()
    {
        // インスタンス化
        $TemporaryUseService = new TemporaryUseService;
        // セッションを削除
        $TemporaryUseService->deleteSearchSession();
        // 初期条件をセット
        $TemporaryUseService->setDefaultCondition();
        // 派遣利用情報を取得
        $temporary_uses = $TemporaryUseService->getTemporaryUseSearch();
        // 合計情報を取得
        $temporary_use_total = $TemporaryUseService->getTemporaryUseTotal($temporary_uses['temporary_uses']);
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 派遣会社情報を取得
        $temporary_companies = TemporaryCompany::getAll()->get();
        return view('other.temporary_use.index')->with([
            'bases' => $bases,
            'temporary_uses' => $temporary_uses['temporary_uses'],
            'temporary_companies' => $temporary_companies,
            'temporary_use_total' => $temporary_use_total,
        ]);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $TemporaryUseService = new TemporaryUseService;
        // セッションを削除
        $TemporaryUseService->deleteSearchSession();
        // 検索条件をセット
        $TemporaryUseService->setSearchCondition($request);
        // 派遣利用情報を取得
        $temporary_uses = $TemporaryUseService->getTemporaryUseSearch();
        // 合計情報を取得
        $temporary_use_total = $TemporaryUseService->getTemporaryUseTotal($temporary_uses['temporary_uses']);
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 派遣会社情報を取得
        $temporary_companies = TemporaryCompany::getAll()->get();
        return view('other.temporary_use.index')->with([
            'bases' => $bases,
            'temporary_uses' => $temporary_uses['temporary_uses'],
            'temporary_companies' => $temporary_companies,
            'temporary_use_total' => $temporary_use_total,
        ]);
    }

    public function create_index()
    {
        // インスタンス化
        $TemporaryUseService = new TemporaryUseService;
        // 派遣会社を全て取得
        $temporary_companies = TemporaryCompany::getAll()->get();
        // 自拠点の荷主情報を取得
        $customers = Customer::getSpecifyBase(Auth::user()->base_id)->get();
        return view('other.temporary_use.create')->with([
            'temporary_companies' => $temporary_companies,
            'customers' => $customers,
        ]);
    }

    public function create(Request $request)
    {
        // インスタンス化
        $TemporaryUseService = new TemporaryUseService;
        // 派遣利用を登録
        $TemporaryUseService->createTemporaryUse($request);
        return redirect()->route('temporary_use.index')->with([
            'alert_type' => 'success',
            'alert_message' => '派遣利用入力が完了しました。',
        ]);
    }

    public function delete(Request $request)
    {
        // インスタンス化
        $TemporaryUseService = new TemporaryUseService;
        // 派遣利用を削除
        $TemporaryUseService->deleteTemporaryUse($request->temporary_use_id);
        return redirect()->route('temporary_use.index')->with([
            'alert_type' => 'success',
            'alert_message' => '派遣利用を削除しました。',
        ]);
    }

    public function download(Request $request)
    {
        // インスタンス化
        $TemporaryUseService = new TemporaryUseService;
        // 派遣利用情報を取得
        $temporary_uses = $TemporaryUseService->getTemporaryUseSearch();
        // ダウンロードする派遣利用データを取得
        $response = $TemporaryUseService->getDownloadTemporaryUse($temporary_uses['temporary_uses_download']);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=派遣利用データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
