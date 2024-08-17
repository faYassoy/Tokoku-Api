<?php

use App\Models\User;
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
        Schema::create('summary_transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('open_at');
            $table->timestamp('close_at')->nullable();
            $table->integer('total_sales')->default(0);
            $table->integer('total_sale_product')->default(0);
            $table->float('total_payment', 20, 2)->default(0);
            $table->float('total_income', 20, 2)->default(0);
            $table->float('total_out_income', 20, 2)->default(0);
            $table->float('total_bp', 20, 2)->default(0);
            $table->boolean('is_active')->default(1)->index();
            $table->timestamps();

            $table->foreignIdFor(User::class, 'open_by')->onDelete('cascade')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary_transactions');
    }
};
