<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummaryTransactions extends Model
{
    use HasFactory;
    protected $fillable = [
        'open_at','open_by','total_sales', 'total_sale_product', 'total_payment', 'total_income', 'total_bp'
    ];

    public $selectable = [
        'open_at','open_by','id','total_sales', 'total_sale_product', 'total_payment', 'total_income', 'total_out_income'
    ];

    public function user_opened()
    {
        return $this->belongsTo(User::class, 'open_by', 'id');
    }
}
