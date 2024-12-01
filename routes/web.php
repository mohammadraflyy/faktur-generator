<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', [InvoiceController::class, 'showInvoiceForm'])->name('invoice.form');
Route::post('/generate-pdf', [InvoiceController::class, 'generateInvoicePdf'])->name('generate.invoice');
