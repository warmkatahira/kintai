<?php

namespace App\Services\CustomerGroupMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerGroup;


class CustomerGroupCreateService
{
    public function createCustomerGroup($request)
    {
        CustomerGroup::create([
            'base_id' => $request->base_id,
            'customer_group_name' => $request->customer_group_name,
            'customer_group_sort_order' => $request->customer_group_sort_order,
        ]);
        return;
    }
}