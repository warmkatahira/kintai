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
            $table->boolean('is_employee_csv_mgt_available')->default(0)->after('is_payroll_data_download_available');
        });
        // 権限管理が有効な権限(システム管理者)のみ、従業員CSV管理を有効にする
        DB::table('roles')->where('is_role_mgt_available', 1)->update(['is_employee_csv_mgt_available' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('is_employee_csv_mgt_available');
        });
    }
};
