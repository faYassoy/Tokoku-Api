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
        Schema::create('return_details', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->integer('refund_amount');
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('returns');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_details');
    }
};