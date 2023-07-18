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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('quantity');
            $table->string('status');
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('bottle_id');
            $table->unsignedBigInteger('perfume_id');
            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('bottle_id')->references('id')->on('bottles');
            $table->foreign('perfume_id')->references('id')->on('perfumes');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
