<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TemporaryCompany;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Services\Punch\PunchFinishInputService;

class TemporaryUseController extends Controller
{
    public function index()
    {
        // インスタンス化
        $PunchFinishInputService = new PunchFinishInputService;
        // 派遣会社を全て取得
        $temporary_companies = TemporaryCompany::getAll()->get();
        // 自拠点の荷主情報を取得
        $customers = Customer::getSpecifyBase(Auth::user()->base_id)->get();
        $customer_groups = CustomerGroup::getSpecifyBase(Auth::user()->base_id)->has('customers')->get();
        // 荷主から応援タブの情報を取得
        $support_bases = $PunchFinishInputService->getSupportedBases();
        return view('other.temporary_use.index')->with([
            'temporary_companies' => $temporary_companies,
            'customers' => $customers,
            'customer_groups' => $customer_groups,
            'support_bases' => $support_bases,
        ]);
    }
}
