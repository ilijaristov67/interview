<?php

use App\Http\Controllers\API\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/storeInvoice', [InvoiceController::class, 'store']);
Route::get('/getInvoices', [InvoiceController::class, 'index']);
Route::post('deleteInvoice/{id}', [InvoiceController::class, 'destroy']);
Route::post('/update/{id}', [InvoiceController::class, 'update']);
