<?php

use App\Http\Controllers\balanceSheetReport;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/reports/balanceSheet', [balanceSheetReport::class, 'index'])->name('reportBalanceSheet');
    Route::get('/reports/balanceSheet/{category}', [balanceSheetReport::class, 'data'])->name('reportBalanceSheetData');

   
   
});
