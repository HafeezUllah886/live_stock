<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sale extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(accounts::class, 'customer_id');
    }

    public function details()
    {
        return $this->hasMany(sale_details::class, 'sale_id');
    }

  
}
