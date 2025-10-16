<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{

    use HasFactory;

    protected $guarded = [];

    public function receiver()
    {
        return $this->belongsTo(accounts::class, 'receiver_id');
    }

    public function account()
    {
        return $this->belongsTo(accounts::class, 'account_id');
    }

    
}
