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
        Schema::table('employees', function (Blueprint $table) {
            // 交通費の支給形態(0:なし 1:1ヶ月固定 2:出勤日数×単価 3:コメント記載日数×単価)
            $table->unsignedTinyInteger('commuting_allowance_type')->default(0)->after('over_time_start');
            // 交通費の金額(1ヶ月固定の場合は月額、それ以外は1日あたりの単価)
            $table->unsignedInteger('commuting_allowance_amount')->default(0)->after('commuting_allowance_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['commuting_allowance_type', 'commuting_allowance_amount']);
        });
    }
};
