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
        Schema::create('bottle_order', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('bottle_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->string('status')->default('pending');
            $table->foreign('bottle_id')->references('id')->on('bottles')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->primary(['bottle_id', 'order_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bottles_orders');
    }
};
