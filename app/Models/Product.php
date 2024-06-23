<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable = ['category_id', 'name', 'barcode', 'image', 'popularity_rating', 'sales_counter'];

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

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'product_id');
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class, 'product_id');
    }

    public function productStock()
    {
        return $this->hasOne(ProductStock::class, 'product_id');
    }
    public function toArray()
    {
        $toArray = parent::toArray();

        $toArray['image'] = $this->image ? asset('storage/' . $this->image) : null;

        return $toArray;
    }
}