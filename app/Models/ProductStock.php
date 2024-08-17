<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $table = 'product_stock';
    protected $primaryKey = 'id';
    protected $fillable = ['stock_quantity', 'previous_stock_quantity', 'product_id'];
    public $selectable = [
        'product_stock.id', 
        'product_stock.stock_quantity', 
        'product_stock.previous_stock_quantity', 
        'product_stock.product_id',
        'product_stock.created_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}