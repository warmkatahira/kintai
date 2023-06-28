<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Employee;

class LCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'customer_id' => '230628-001',
            'customer_name' => 'ウエニ貿易',
            'base_id' => '08_LC',
            'customer_sort_order' => 1,
        ]);
        Customer::create([
            'customer_id' => '230628-002',
            'customer_name' => 'フロムアイズ・アイネクスト',
            'base_id' => '08_LC',
            'customer_sort_order' => 2,
        ]);
        Customer::create([
            'customer_id' => '230628-003',
            'customer_name' => 'その他',
            'base_id' => '08_LC',
            'customer_sort_order' => 3,
        ]);

        Employee::create([
            'employee_no' => '0152',
            'base_id' => '08_LC',
            'employee_last_name' => '對馬',
            'employee_first_name' => '加寿彦',
            'employee_category_id' => 1,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '0185',
            'base_id' => '08_LC',
            'employee_last_name' => '荻野',
            'employee_first_name' => '博之',
            'employee_category_id' => 1,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '0199',
            'base_id' => '08_LC',
            'employee_last_name' => '岩下',
            'employee_first_name' => '龍平',
            'employee_category_id' => 1,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '0202',
            'base_id' => '08_LC',
            'employee_last_name' => '大林',
            'employee_first_name' => '晴美',
            'employee_category_id' => 1,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1196',
            'base_id' => '08_LC',
            'employee_last_name' => '佐藤',
            'employee_first_name' => '優子',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1442',
            'base_id' => '08_LC',
            'employee_last_name' => '井上',
            'employee_first_name' => '琴映',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1447',
            'base_id' => '08_LC',
            'employee_last_name' => '伊藤',
            'employee_first_name' => '彰',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1494',
            'base_id' => '08_LC',
            'employee_last_name' => '蓮見',
            'employee_first_name' => '美香',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1539',
            'base_id' => '08_LC',
            'employee_last_name' => '井澤',
            'employee_first_name' => '志保',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1541',
            'base_id' => '08_LC',
            'employee_last_name' => '入江',
            'employee_first_name' => '和美',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1564',
            'base_id' => '08_LC',
            'employee_last_name' => '稲越',
            'employee_first_name' => '美保',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1596',
            'base_id' => '08_LC',
            'employee_last_name' => '大山',
            'employee_first_name' => '久美子',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1623',
            'base_id' => '08_LC',
            'employee_last_name' => '大野',
            'employee_first_name' => '光枝',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1633',
            'base_id' => '08_LC',
            'employee_last_name' => '志村',
            'employee_first_name' => '由美',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1634',
            'base_id' => '08_LC',
            'employee_last_name' => '土屋',
            'employee_first_name' => '孝子',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1740',
            'base_id' => '08_LC',
            'employee_last_name' => '蓮見',
            'employee_first_name' => '健一',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1781',
            'base_id' => '08_LC',
            'employee_last_name' => '岩崎',
            'employee_first_name' => '典子',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1782',
            'base_id' => '08_LC',
            'employee_last_name' => '髙橋',
            'employee_first_name' => '麻衣',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1805',
            'base_id' => '08_LC',
            'employee_last_name' => '辻川',
            'employee_first_name' => '佳菜子',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1806',
            'base_id' => '08_LC',
            'employee_last_name' => '五十嵐',
            'employee_first_name' => '未弥子',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1807',
            'base_id' => '08_LC',
            'employee_last_name' => '吉田',
            'employee_first_name' => '愛菜',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1816',
            'base_id' => '08_LC',
            'employee_last_name' => '進藤',
            'employee_first_name' => '明恵',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1822',
            'base_id' => '08_LC',
            'employee_last_name' => '稲葉',
            'employee_first_name' => '厚子',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
    }
}
