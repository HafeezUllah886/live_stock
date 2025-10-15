<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slaughtering extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(accounts::class, 'customer_id');
    }

    public function factory()
    {
        return $this->belongsTo(accounts::class, 'factory_id');
    }

    public function butcher()
    {
        return $this->belongsTo(accounts::class, 'butcher_id');
    }

    public function ober_customer()
    {
        return $this->belongsTo(accounts::class, 'ober_customer_id');
    }
}
