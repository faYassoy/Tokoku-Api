<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    // protected $primaryKey = 'customer_id';
    protected $fillable = ['name', 'contact_info', 'credit_balance'];
    protected $selectable = ['name', 'contact_info', 'credit_balance'];
    public $searchable = ['customers.name', 'customers.contact_info', 'customers.credit_balance'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }
}