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
        Schema::create('temporary_uses', function (Blueprint $table) {
            $table->increments('temporary_use_id');
            $table->unsignedInteger('temporary_company_id');
            $table->string('base_id', 20);
            $table->date('date');
            $table->string('customer_id', 20);
            $table->unsignedInteger('customer_working_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_uses');
    }
};
