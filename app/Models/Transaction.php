<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Constraint\Operator;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'operator_id', 'customer_id', 'transaction_date', 'total_amount', 'payment_type', 'status'
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id')->withDefault();
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
