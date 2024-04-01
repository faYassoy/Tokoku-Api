<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'name', 'barcode', 'price', 'stock_quantity', 'image'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id');
    }
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'product_id');
    }

    public function getCurrentStockAttribute()
    {
        $stockIn = $this->stockMovements()->where('type', 'stock-in')->sum('quantity');
        $stockOut = $this->stockMovements()->where('type', 'stock-out')->sum('quantity');
        return $stockIn - $stockOut;
    }
}
