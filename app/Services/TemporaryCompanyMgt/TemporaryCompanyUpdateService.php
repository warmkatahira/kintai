<?php

namespace App\Services\TemporaryCompanyMgt;

use App\Models\TemporaryCompany;

class TemporaryCompanyUpdateService
{
    public function updateTemporaryCompany($request)
    {
        TemporaryCompany::getSpecify($request->temporary_company_id)->update([
            'temporary_company_name' => $request->temporary_company_name,
            'hourly_rate' => $request->hourly_rate,
            'amount_calc_item' => $request->amount_calc_item,
        ]);
        return;
    }
}