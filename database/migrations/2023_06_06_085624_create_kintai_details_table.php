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
        Schema::create('kintai_details', function (Blueprint $table) {
            $table->increments('kintai_detail_id');
            $table->unsignedInteger('kintai_id');
            $table->string('customer_id', 20);
            $table->unsignedInteger('customer_working_time');
            $table->boolean('is_supported');
            $table->timestamps();
            // 外部キー制約
            $table->foreign('kintai_id')->references('kintai_id')->on('kintais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kintai_details');
    }
};
