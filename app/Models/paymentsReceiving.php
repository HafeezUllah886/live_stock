<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentsReceiving extends Model
{

    use HasFactory;

    protected $guarded = [];
    protected $table = 'payments_receiving';

    public function depositer()
    {
        return $this->belongsTo(accounts::class, 'depositer_id');
    }
    public function account()
    {
        return $this->belongsTo(accounts::class, 'account_id');
    }

  
}
