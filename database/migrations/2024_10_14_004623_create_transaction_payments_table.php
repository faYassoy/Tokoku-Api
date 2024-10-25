<?php

use App\Models\SummaryTransactions;
use App\Models\Transaction;
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
        {
            Schema::create('transaction_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Transaction::class)->nullable()->onDelete('cascade')->index();
                $table->foreignIdFor(SummaryTransactions::class)->onDelete('set null')->index();
                $table->foreignIdFor(User::class, 'created_by')->onDelete('set null');
                $table->string('note')->nullable();
                $table->float('total_payment', 20, 2)->default(0);
                $table->float('change', 20, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_payments');
    }
};
