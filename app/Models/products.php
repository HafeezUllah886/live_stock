<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(units::class, 'unitID');
    }

    public function saleDetails()
    {
        return $this->hasMany(sale_details::class, 'productID');
    }

    public function category()
    {
        return $this->belongsTo(categories::class, 'catID');
    }

    public function brand()
    {
        return $this->belongsTo(brands::class, 'brandID');
    }

    public function units()
    {
        return $this->hasMany(product_units::class, 'productID');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');

    }
    public function scopeVendor($query, $id)
    {
        return $query->where('vendorID', $id);

    }

    public function dcs()
    {
        return $this->hasMany(product_dc::class, 'productID');
    }

    public function branch()
    {
        return $this->belongsTo(branches::class, 'branchID', 'id');
    }

    public function scopeCurrentBranch($query)
    {
        return $query->where('branchID', auth()->user()->branchID);
    }

    public function vendor()
    {
        return $this->belongsTo(accounts::class, 'vendorID');
    }

}
