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
            $table->unsignedTinyInteger('kintai_disp_scope')->default(1)->after('is_special_woriking_time_disp_available');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_no', 4)->nullable()->after('base_id');
        });
        // 既存の権限に勤怠表示範囲を設定（1:自分のみ、2:自拠点、3:全拠点）
        DB::table('roles')->whereIn('role_name', ['社員', '田村', '打刻'])->update(['kintai_disp_scope' => 1]);
        DB::table('roles')->whereIn('role_name', ['所長', '事務'])->update(['kintai_disp_scope' => 2]);
        DB::table('roles')->whereIn('role_name', ['ALL', '経理', '本社'])->update(['kintai_disp_scope' => 3]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('kintai_disp_scope');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('employee_no');
        });
    }
};
