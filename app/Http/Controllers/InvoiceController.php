<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceCheckRequest;
use App\Models\InvoiceRecord;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('invoice.index', [
            'invoice' => null,
            'searched' => false,
        ]);
    }

    public function check(InvoiceCheckRequest $request)
    {
        $number = trim($request->validated()['invoice_number']);

        $invoice = InvoiceRecord::where('invoice_number', $number)->first();

        if (! $invoice) {
            return view('invoice.index', [
                'invoice' => null,
                'searched' => true,
            ])->with('error', "Invoice dengan nomor \"{$number}\" tidak ditemukan.");
        }

        return view('invoice.index', [
            'invoice' => $invoice,
            'searched' => true,
        ]);
    }
}
