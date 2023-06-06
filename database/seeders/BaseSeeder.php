<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Base;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Base::create([
            'base_id' => '00_Honsha',
            'base_name' => '本社',
            'is_add_rest_available' => 0,
        ]);
        Base::create([
            'base_id' => '01_1st',
            'base_name' => '第1営業所',
            'is_add_rest_available' => 0,
        ]);
    }
}
