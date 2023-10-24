<?php

namespace App\Http\Controllers\SystemMgt\TemporaryCompanyMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemporaryCompany;

class TemporaryCompanyMgtController extends Controller
{
    public function index()
    {
        // 派遣会社情報を取得
        $temporary_companies = TemporaryCompany::getAll()->get();
        return view('temporary_company_mgt.index')->with([
            'temporary_companies' => $temporary_companies,
        ]);
    }
}
