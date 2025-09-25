<?php

use App\Http\Controllers\WarehousesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductUnitsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('warehouses', WarehousesController::class);

    Route::resource('products', ProductsController::class);
        
    Route::resource('product_units', ProductUnitsController::class);

});

