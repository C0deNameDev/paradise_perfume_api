<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('characteristic_perfume', function (Blueprint $table) {
            $table->unsignedBigInteger('perfume_id');
            $table->unsignedBigInteger('characteristic_id');            
            $table->foreign('perfume_id')->references('id')->on('perfumes')->onDelete('cascade');
            $table->foreign('characteristic_id')->references('id')->on('characteristics')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characteristic_perfume');
    }
};
