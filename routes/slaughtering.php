<?php

use App\Http\Controllers\SlaughteringController;
use App\Http\Controllers\PurchasePaymentsController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('slaughter', SlaughteringController::class);
    Route::get("slaughters/delete/{id}", [SlaughteringController::class, 'destroy'])->name('slaughters.delete')->middleware(confirmPassword::class);
   
});
