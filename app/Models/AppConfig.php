<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    use HasFactory;

    // =========================>
    // ## Fillable
    // =========================>
    protected $fillable = [
        'code',
        'name',
        'description',
        'value'
    ];

    // =========================>
    // ## Searchable
    // =========================>
    public $searchable = [
        'app_configs.code',
        'app_configs.name',
    ];

    // =========================>
    // ## Selectable
    // =========================>
    public $selectable = [
        'app_configs.id',
        'app_configs.code',
        'app_configs.name',
        'app_configs.description',
        'app_configs.value',
    ];
}
