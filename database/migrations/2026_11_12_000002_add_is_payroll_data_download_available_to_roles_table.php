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
            $table->boolean('is_payroll_data_download_available')->default(0)->after('is_paid_leave_mgt_available');
        });
        // 有給管理が有効な権限は、給与計算データダウンロードも有効にする
        DB::table('roles')->where('is_paid_leave_mgt_available', 1)->update(['is_payroll_data_download_available' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('is_payroll_data_download_available');
        });
    }
};
