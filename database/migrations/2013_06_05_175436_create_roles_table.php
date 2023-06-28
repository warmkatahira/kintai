<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('role_id');
            $table->string('role_name', 10);
            $table->boolean('is_kintai_mgt_func_available')->default(0);
            $table->boolean('is_base_check_available')->default(0);
            $table->boolean('is_kintai_operation_available')->default(0);
            $table->boolean('is_employee_mgt_func_available')->default(0);
            $table->boolean('is_employee_operation_available')->default(0);
            $table->boolean('is_base_mgt_func_available')->default(0);
            $table->boolean('is_manual_punch_available')->default(0);
            $table->boolean('is_customer_mgt_func_available')->default(0);
            $table->boolean('is_kintai_close_available')->default(0);
            $table->boolean('is_download_func_available')->default(0);
            $table->boolean('is_kintai_report_download_available')->default(0);
            $table->boolean('is_data_download_available')->default(0);
            $table->boolean('is_other_func_available')->default(0);
            $table->boolean('is_accounting_mgt_func_available')->default(0);
            $table->boolean('is_system_mgt_func_available')->default(0);
            $table->boolean('is_user_mgt_available')->default(0);
            $table->boolean('is_role_mgt_available')->default(0);
            $table->boolean('is_holiday_mgt_available')->default(0);
            $table->boolean('is_access_mgt_available')->default(0);
            $table->boolean('is_base_mgt_available')->default(0);
            $table->boolean('is_lock_kintai_operation_available')->default(0);
            $table->boolean('is_all_kintai_operation_available')->default(0);
            $table->boolean('is_short_time_info_available')->default(0);
            $table->boolean('is_all_base_operation_available')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
