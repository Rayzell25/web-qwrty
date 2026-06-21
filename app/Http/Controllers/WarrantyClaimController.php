<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarrantyClaimRequest;
use App\Models\InvoiceRecord;
use App\Models\WarrantyClaim;
use Illuminate\Support\Facades\Auth;

class WarrantyClaimController extends Controller
{
    public function index()
    {
        return view('warranty.index');
    }

    public function store(WarrantyClaimRequest $request)
    {
        $data = $request->validated();

        // Store optional attachment on the public disk.
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')
                ->store('warranty-claims', 'public');
        } else {
            unset($data['attachment']);
        }

        // Link to a logged-in user when available.
        $data['user_id'] = Auth::id();

        // Try to link to an existing invoice record by number.
        $invoice = InvoiceRecord::where('invoice_number', $data['invoice_number'])->first();
        $data['invoice_record_id'] = $invoice?->id;

        $data['status'] = 'pending';

        WarrantyClaim::create($data);

        return redirect()
            ->route('warranty.index')
            ->with('success', 'Klaim garansi Anda berhasil dikirim. Tim kami akan meninjau dan menghubungi Anda.');
    }
}
