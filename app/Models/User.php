<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'users';
    // protected $primaryKey = 'user_id';
    protected $fillable = ['username', 'password', 'name', 'role'];
    protected $hidden = ['password'];
        
    


    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    public function productReturns()
    {
        return $this->hasMany(ProductReturn::class, 'user_id');
    }
}