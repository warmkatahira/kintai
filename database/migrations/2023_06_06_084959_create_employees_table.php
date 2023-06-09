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
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('employee_id');
            $table->string('employee_no', 4)->unique();
            $table->string('base_id', 20);
            $table->string('employee_last_name', 10);
            $table->string('employee_first_name', 10);
            $table->unsignedInteger('employee_category_id');
            $table->unsignedInteger('hourly_wage')->default(0);
            $table->double('monthly_workable_time', 5, 2)->default(0);
            $table->double('over_time_start', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
