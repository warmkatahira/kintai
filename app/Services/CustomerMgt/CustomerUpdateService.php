<?php

namespace App\Services\CustomerMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;


class CustomerUpdateService
{
    public function updateCustomer($request)
    {
        Customer::getSpecify($request->customer_id)->update([
            'base_id' => $request->base_id,
            'customer_name' => $request->customer_name,
            'customer_group_id' => $request->customer_group_id,
            'is_status' => $request->is_status,
            'customer_sort_order' => $request->customer_sort_order,
        ]);
        return;
    }
}