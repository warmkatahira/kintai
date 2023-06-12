<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IpLimit;

class IpLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IpLimit::create([
            'ip' => '60.41.205.126',
            'note' => '本社',
            'is_available' => 1,
        ]);
    }
}
