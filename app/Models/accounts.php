<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class accounts extends Model
{
    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(branches::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeBusiness($query)
    {
        return $query->where('category', 'Business');
    }

    public function scopeCustomer($query)
    {
        return $query->where('category', 'Customer');
    }

    public function scopeVendor($query)
    {
        return $query->where('category', 'Vendor');
    }

    public function scopeTransporter($query)
    {
        return $query->where('category', 'Transporter');
    }

    public function scopeFactory($query)
    {
        return $query->where('category', 'Factory');
    }
    public function scopeButcher($query)
    {
        return $query->where('category', 'Butcher');
    }

    public function scopeNotBusiness($query)
    {
        return $query->whereNot('category', 'Business');
    }

    public function scopeCustomerAndFactory($query)
    {
        return $query->where('category', 'Customer')->orWhere('category', 'Factory');
    }

   
}
