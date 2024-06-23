<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table = 'product_prices';
    protected $primaryKey = 'product_prices_id';
    protected $fillable = ['product_id', 'price_type', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}