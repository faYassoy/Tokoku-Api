<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'summary_transaction_id',
        'created_by',
        'total_payment',
        'change'
    ];

  
    /**
     * Relation to `transaction` model
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

 
    /**
     * Relation to `PaymentMethod` model
     */
   

    /**
     * Relation to `User` model as user creator
     */
    public function user_creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
