<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $table = 'product_stock';
    protected $primaryKey = 'product_stock_id';
    protected $fillable = ['stock_quantity', 'previous_stock_quantity', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}