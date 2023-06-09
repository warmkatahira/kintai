<?php

namespace App\Services\CustomerMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;


class CustomerCreateService
{
    public function createCustomer($request)
    {
        Customer::create([
            'base_id' => $request->base_id,
            'customer_name' => $request->customer_name,
            'customer_group_id' => $request->customer_group_id,
        ]);
        return;
    }
}