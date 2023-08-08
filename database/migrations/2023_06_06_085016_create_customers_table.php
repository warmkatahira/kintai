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
        Schema::create('customers', function (Blueprint $table) {
            $table->string('customer_id', 20)->primary();
            $table->string('customer_name', 20);
            $table->unsignedInteger('customer_group_id')->nullable();
            $table->string('base_id', 20);
            $table->boolean('is_available')->default(1);
            $table->unsignedInteger('customer_sort_order')->default(100);
            $table->timestamps();
            // 外部キー制約
            $table->foreign('base_id')->references('base_id')->on('bases');
            $table->foreign('customer_group_id')->references('customer_group_id')->on('customer_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
