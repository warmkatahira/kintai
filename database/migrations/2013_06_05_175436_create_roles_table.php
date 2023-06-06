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
            $table->boolean('is_kintai_list_func_available')->default(0);
            $table->boolean('is_employee_list_func_available')->default(0);
            $table->boolean('is_other_func_available')->default(0);
            $table->boolean('is_data_export_func_available')->default(0);
            $table->boolean('is_management_func_available')->default(0);
            $table->boolean('is_system_mgt_func_available')->default(0);
            $table->boolean('is_accounting_func_available')->default(0);
            $table->boolean('is_kintai_check_available')->default(0);
            $table->boolean('is_kintai_delete_available')->default(0);
            $table->boolean('is_kintai_modify_available')->default(0);
            $table->boolean('is_all_kintai_operation_available')->default(0);
            $table->boolean('is_employee_operation_available')->default(0);
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
