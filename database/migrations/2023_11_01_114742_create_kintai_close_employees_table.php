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
        Schema::create('kintai_close_employees', function (Blueprint $table) {
            $table->increments('kintai_close_employee_id');
            $table->unsignedInteger('kintai_close_id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('employee_category_id');
            $table->boolean('is_available');
            $table->timestamps();
            // 外部キー制約
            $table->foreign('kintai_close_id')->references('kintai_close_id')->on('kintai_closes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kintai_close_employees');
    }
};
