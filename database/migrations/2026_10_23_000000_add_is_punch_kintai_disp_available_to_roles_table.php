<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('is_punch_kintai_disp_available')->default(1)->after('kintai_disp_scope');
        });
        // 他の従業員の勤怠が見えてしまうため、社員・田村は打刻画面の今日の勤怠・今月の勤怠を無効にする
        DB::table('roles')->whereIn('role_name', ['社員', '田村'])->update(['is_punch_kintai_disp_available' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('is_punch_kintai_disp_available');
        });
    }
};
