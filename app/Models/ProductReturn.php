<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    protected $table = 'returns';
    protected $primaryKey = 'return_id';
    protected $fillable = ['transaction_id', 'return_date', 'user_id', 'reason'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }
}