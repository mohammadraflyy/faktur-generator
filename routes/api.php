<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::options('/{any}', function (Request $request) {
    return response()->json(['status' => 'ok'], 200);
})->where('any', '.*');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/generate', [InvoiceController::class, 'generateInvoicePdf']);
