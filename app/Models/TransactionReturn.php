<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionReturn extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'return_amount',
        'note',
    ];
    public $selectable = [
        'transaction_returns.id', 'transaction_returns.transaction_id', 'transaction_returns.product_id', 'transaction_returns.quantity', 'transaction_returns.return_amount', 'transaction_returns.note',
        'transaction_returns.created_at', 'transaction_returns.updated_at'
    ];
    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
