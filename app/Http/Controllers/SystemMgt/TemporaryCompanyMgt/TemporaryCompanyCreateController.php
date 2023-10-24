<?php

namespace App\Http\Controllers\SystemMgt\TemporaryCompanyMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemporaryCompany;
use App\Services\TemporaryCompanyMgt\TemporaryCompanyCreateService;
use App\Http\Requests\TemporaryCompanyRequest;

class TemporaryCompanyCreateController extends Controller
{
    public function index(Request $request)
    {
        return view('temporary_company_mgt.create');
    }

    public function create(TemporaryCompanyRequest $request)
    {
        // インスタンス化
        $TemporaryCompanyCreateService = new TemporaryCompanyCreateService;
        // レコードを追加
        $TemporaryCompanyCreateService->createTemporaryCompany($request);
        return redirect()->route('temporary_company_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => '派遣会社を追加しました。',
        ]);
    }
}
