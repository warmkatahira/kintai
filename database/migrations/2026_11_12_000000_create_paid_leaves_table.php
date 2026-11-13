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
        Schema::create('paid_leaves', function (Blueprint $table) {
            $table->increments('paid_leave_id');
            $table->string('employee_no', 4);
            $table->date('paid_leave_date');
            $table->decimal('paid_leave_days', 3, 1);
            $table->timestamps();
            // 同じ従業員・同じ日の有給は1件のみ
            $table->unique(['employee_no', 'paid_leave_date']);
            $table->index('paid_leave_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paid_leaves');
    }
};
