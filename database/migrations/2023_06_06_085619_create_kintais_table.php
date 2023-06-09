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
        Schema::create('kintais', function (Blueprint $table) {
            $table->increments('kintai_id');
            $table->unsignedInteger('employee_id');
            $table->date('work_day');
            $table->time('begin_time');
            $table->time('finish_time')->nullable();
            $table->time('begin_time_adj')->nullable();
            $table->time('finish_time_adj')->nullable();
            $table->time('out_time')->nullable();
            $table->time('return_time')->nullable();
            $table->time('out_time_adj')->nullable();
            $table->time('return_time_adj')->nullable();
            $table->boolean('out_enabled')->default(0);
            $table->unsignedInteger('rest_time')->nullable();
            $table->unsignedInteger('no_rest_time')->nullable();
            $table->unsignedInteger('add_rest_time')->nullable();
            $table->string('comment', 20)->nullable();
            $table->unsignedInteger('out_return_time')->default(0);
            $table->unsignedInteger('working_time')->nullable();
            $table->unsignedInteger('over_time')->nullable();
            $table->boolean('is_early_worked');
            $table->boolean('is_modified')->default(0);
            $table->boolean('is_manual_punched')->default(0);
            $table->timestamp('base_checked_at')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kintais');
    }
};
