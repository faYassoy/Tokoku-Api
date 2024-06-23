<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditTransaction extends Model
{
    protected $table = 'credit_transactions';
    // protected $primaryKey = 'credit_transaction_id';
    protected $fillable = ['transaction_id', 'due_date', 'status', 'amount'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}