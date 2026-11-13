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
            $table->boolean('is_paid_leave_mgt_available')->default(0)->after('is_holiday_mgt_available');
        });
        // 休日管理が有効な権限は、有給管理も有効にする
        DB::table('roles')->where('is_holiday_mgt_available', 1)->update(['is_paid_leave_mgt_available' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('is_paid_leave_mgt_available');
        });
    }
};
