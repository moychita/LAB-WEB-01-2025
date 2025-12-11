<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::resource('categories', CategoryController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('products', ProductController::class);

Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
Route::get('stocks/transfer', [StockController::class, 'create'])->name('stocks.transfer');
Route::post('stocks/transfer', [StockController::class, 'store'])->name('stocks.transfer.store');
