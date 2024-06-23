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
        Schema::create('product_stock_log', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('product_stock_id');
            $table->integer('old_quantity');
            $table->integer('new_quantity');
            $table->timestamps();

            $table->foreign('product_stock_id')->references('id')->on('product_stock')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_log');
    }
};
