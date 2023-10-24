<?php

namespace App\Http\Controllers\SystemMgt\TemporaryCompanyMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemporaryCompany;
use App\Services\TemporaryCompanyMgt\TemporaryCompanyUpdateService;
use App\Http\Requests\TemporaryCompanyRequest;

class TemporaryCompanyUpdateController extends Controller
{
    public function index(Request $request)
    {
        // 派遣会社情報を取得
        $temporary_company = TemporaryCompany::getSpecify($request->temporary_company_id)->first();
        return view('temporary_company_mgt.update')->with([
            'temporary_company' => $temporary_company,
        ]);
    }

    public function update(TemporaryCompanyRequest $request)
    {
        // インスタンス化
        $TemporaryCompanyUpdateService = new TemporaryCompanyUpdateService;
        // レコードを変更
        $TemporaryCompanyUpdateService->updateTemporaryCompany($request);
        return redirect()->route('temporary_company_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => '派遣会社を更新しました。',
        ]);
    }
}
