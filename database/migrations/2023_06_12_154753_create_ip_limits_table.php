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
        Schema::create('ip_limits', function (Blueprint $table) {
            $table->increments('ip_limit_id');
            $table->string('ip', 15);
            $table->string('note', 20);
            $table->boolean('is_available')->default(0);
            $table->string('base_id', 20);
            $table->timestamps();
            // 外部キー制約
            $table->foreign('base_id')->references('base_id')->on('bases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_limits');
    }
};
