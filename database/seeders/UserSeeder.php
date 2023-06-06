<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'user_id' => 'katahira',
            'last_name' => '片平',
            'first_name' => '貴順',
            'email' => 't.katahira@warm.co.jp',
            'password' => bcrypt('katahira134'),
            'role_id' => 1,
            'base_id' => '00_Honsha',
            'status' => 1,
        ]);
    }
}
