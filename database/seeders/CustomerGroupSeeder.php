<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerGroup;

class CustomerGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerGroup::create([
            'base_id' => '00_Honsha',
            'customer_group_name' => 'コンタクト',
            'customer_group_sort_order' => 1,
        ]);
        CustomerGroup::create([
            'base_id' => '00_Honsha',
            'customer_group_name' => '雑貨',
            'customer_group_sort_order' => 2,
        ]);
        CustomerGroup::create([
            'base_id' => '01_1st',
            'customer_group_name' => 'コンタクト',
            'customer_group_sort_order' => 1,
        ]);
    }
}
