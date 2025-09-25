<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenses extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(accounts::class, 'account_id');
    }

    public function category()
    {
        return $this->belongsTo(expenseCategories::class, 'expense_category_id');
    }

    public function scopeCurrentBranch($query)
    {
        return $query->where('branch_id', auth()->user()->branch_id);
    }
}
