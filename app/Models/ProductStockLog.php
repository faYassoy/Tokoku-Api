<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockLog extends Model
{
    protected $table = 'product_stock_log';
    protected $primaryKey = 'product_stock_log_id';
    protected $fillable = ['product_stock_id', 'old_quantity', 'new_quantity'];

    public function productStock()
    {
        return $this->belongsTo(ProductStock::class, 'product_stock_id');
    }
}