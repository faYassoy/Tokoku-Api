<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    protected $primaryKey = 'report_id';
    protected $fillable = ['report_type', 'start_date', 'end_date', 'generated_on', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}