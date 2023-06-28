<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'customer_id' => '230606-001',
            'customer_name' => '荷主A',
            'customer_group_id' => 1,
            'base_id' => '00_Honsha',
        ]);
        Customer::create([
            'customer_id' => '230606-002',
            'customer_name' => '荷主B',
            'customer_group_id' => 1,
            'base_id' => '00_Honsha',
        ]);
        Customer::create([
            'customer_id' => '230607-001',
            'customer_name' => '荷主C',
            'customer_group_id' => 2,
            'base_id' => '00_Honsha',
        ]);
        Customer::create([
            'customer_id' => '230606-003',
            'customer_name' => '大洋製薬',
            'customer_group_id' => 4,
            'base_id' => '01_1st',
        ]);
        Customer::create([
            'customer_id' => '230606-004',
            'customer_name' => 'intervia',
            'customer_group_id' => 3,
            'base_id' => '01_1st',
        ]);
    }
}
