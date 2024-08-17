<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

    // =========================>
    // ## Fillable
    // =========================>
    protected $fillable = [
        'product_id',
        'price_type',
        'price',
    ];

    // =========================>
    // ## Selectable
    // =========================>
    public $selectable = [
        'product_prices.id',
        'product_prices.product_id',
        'product_prices.price_type',
        'product_prices.price',
    ];

    // =========================>
    // ## Relations
    // =========================>
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}