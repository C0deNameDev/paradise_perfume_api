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
            // $table->integer('quantity');
            // $table->unsignedBigInteger('bottle_id');
            // $table->foreign('bottle_id')->references('id')->on('bottles');
            $table->id();
            $table->timestamps();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('perfume_id');
            $table->unsignedBigInteger('card_id');
            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('perfume_id')->references('id')->on('perfumes');
            $table->foreign('client_id')->references('id')->on('clients');
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
