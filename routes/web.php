<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;


Route::get('/', [OrderController::class, 'showForm'])->name('order.form');
Route::post('/order/submit', [OrderController::class, 'submitOrder'])->name('order.submit');


// Admin routes (protected by auth)
Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/delete/{id}', [AdminController::class, 'destroy'])->name('admin.delete');
});
Auth::routes();
