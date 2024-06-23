<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    protected $fillable = ['user_id', 'customer_id', 'transaction_date', 'total_amount', 'payment_type', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    public function creditTransaction()
    {
        return $this->hasOne(CreditTransaction::class, 'transaction_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'related_transaction_id');
    }

    public function ProductReturn()
    {
        return $this->hasOne(ProductReturn::class, 'transaction_id');
    }
}