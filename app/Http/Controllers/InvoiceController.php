<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function showInvoiceForm()
    {
        return view('invoice-form');
    }

    public function generateInvoicePdf(Request $request)
    {
        $request->validate([
            'invoice_items.receiver' => 'required|string',
            'invocie_items.note' => 'nullable|string',
            'invoice_items.items' => 'required|array|min:1',
            'invoice_items.items.*.product_name' => 'required|string',
            'invoice_items.items.*.qty' => 'required|numeric|min:1',
            'invoice_items.items.*.unit' => 'nullable|string',
            'invoice_items.items.*.price' => 'required|numeric|min:0',
        ]);

        $invoiceData = $request->input('invoice_items');
        $itemData = $invoiceData['items'];

        $lastInvoice = Invoice::latest()->first();
        $lastInvoiceNumber = $lastInvoice ? (int) substr($lastInvoice->invoice_number, 2) : 938;

        $newInvoiceNumber = $lastInvoiceNumber + 1;
        $invoiceNumber = str_pad($newInvoiceNumber, 4, '0', STR_PAD_LEFT);
        $invoicePrefix = 'WS';

        $fullInvoiceNumber = $invoicePrefix . $invoiceNumber;

        $invoice = Invoice::create([
            'invoice_number' => $fullInvoiceNumber,
            'receiver' => $invoiceData['receiver'],
            'note' => $invoiceData['note'] ?? '',
        ]);

        $totalAmount = 0;

        foreach ($itemData as $item) {
            $total = $item['qty'] * $item['price'];

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_name' => $item['product_name'],
                'qty' => $item['qty'],
                'unit' => $item['unit'],
                'price' => $item['price'],
                'total' => $total,
            ]);

            $totalAmount += $total;
        }

        $signaturePath = public_path('images/signature.png');
        $base64Signature = base64_encode(file_get_contents($signaturePath));

        $logoPath = public_path('images/logo.png');
        $base64Logo = base64_encode(file_get_contents($logoPath));

        $date = \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d');
        $dateName = \Carbon\Carbon::parse($invoice->created_at)->format('Ymd');

        $invoiceName = 'INV_' . $fullInvoiceNumber . '_' . $dateName . '.pdf';

        return Pdf::view('invoice-pdf', [
                    'invoiceData' => $itemData,
                    'base64Logo' => $base64Logo,
                    'base64Signature' => $base64Signature,
                    'invoiceNumber' => $fullInvoiceNumber,
                    'totalAmount' => $totalAmount,
                    'note' => $invoiceData['note'],
                    'receiver' => $invoiceData['receiver'],
                    'date' => $date
                ])
                ->format('a4')
                ->name($invoiceName);
    }
}
