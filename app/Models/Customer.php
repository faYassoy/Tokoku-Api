<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    // protected $primaryKey = 'customer_id';
    protected $fillable = ['name', 'contact_info', 'credit_balance'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id');
    }
}