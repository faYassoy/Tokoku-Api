<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
    protected $table = 'return_details';
    protected $primaryKey = 'return_detail_id';
    protected $fillable = ['return_id', 'product_id', 'quantity', 'refund_amount'];

    public function productReturn()
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}