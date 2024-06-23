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
        {
            Schema::create('returns', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('transaction_id');
                $table->dateTime('return_date');
                $table->unsignedBigInteger('user_id');
                $table->string('reason')->nullable();
                $table->timestamps();
                $table->foreign('transaction_id')->references('id')->on('transactions');
                        $table->foreign('user_id')->references('id')->on('users');
                    });
                }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        {
            Schema::dropIfExists('returns');
        }
    }
};
