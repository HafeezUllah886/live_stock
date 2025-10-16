<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\accounts;

class balanceSheetReport extends Controller
{
    public function index()
    {
        return view('reports.balanceSheet.index');
    }

    public function data($category)    
    {
        if($category == "All")
        {
            $ids = accounts::active()->pluck('id')->toArray();
        }
        else
        {
            $ids = accounts::active()->where('category', $category)->pluck('id')->toArray();
        }

        $accounts = accounts::whereIn('id', $ids)->get();

        foreach($accounts as $account)
        {
            $balance = getAccountBalance($account->id);
            $account->balance = $balance;
        }

        return view('reports.balanceSheet.details', compact('category', 'accounts'));
    }
}
