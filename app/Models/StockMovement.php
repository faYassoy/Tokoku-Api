<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'stock_movements';
    protected $primaryKey = 'movement_id';
    protected $fillable = ['product_id', 'type', 'quantity', 'movement_date', 'related_transaction_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'related_transaction_id');
    }
}