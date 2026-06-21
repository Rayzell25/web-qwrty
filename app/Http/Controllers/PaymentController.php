<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payment\AutoGoPayClient;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function form()
    {
        return view('payment.form');
    }

    public function create(Request $request, AutoGoPayClient $client)
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:1000', 'max:10000000'],
        ], [], ['amount' => 'nominal']);

        if (! $client->isEnabled()) {
            return back()->with('error', 'Gateway pembayaran belum dikonfigurasi (AUTOGOPAY_API_KEY kosong).');
        }

        $res = $client->generateQris((int) $data['amount']);

        if (! ($res['success'] ?? false) || empty($res['data']['order_id'])) {
            return back()
                ->withInput()
                ->with('error', $res['message'] ?? 'Gagal membuat pembayaran QRIS.');
        }

        $d = $res['data'];

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'order_id' => $d['order_id'],
            'transaction_id' => $d['transaction_id'] ?? null,
            'amount' => (int) ($d['amount'] ?? $data['amount']),
            'status' => $d['transaction_status'] ?? 'pending',
            'payment_type' => 'qris',
            'reference' => 'topup',
            'qr_string' => $d['qr_string'] ?? null,
            'qr_url' => $d['qr_url'] ?? null,
            'checkout_url' => $d['checkout_url'] ?? null,
            'expired_at' => isset($d['expiry_time']) ? $this->parseTime($d['expiry_time']) : null,
        ]);

        return redirect()->route('pay.show', $payment->order_id);
    }

    public function show(string $order)
    {
        $payment = Payment::where('order_id', $order)->firstOrFail();

        return view('payment.show', compact('payment'));
    }

    public function status(string $order, AutoGoPayClient $client)
    {
        $payment = Payment::where('order_id', $order)->firstOrFail();

        // Sudah final, tidak perlu cek lagi.
        if ($payment->isFinal()) {
            return response()->json($this->statusPayload($payment));
        }

        $res = $client->status($order);
        $newStatus = $res['data']['transaction_status']
            ?? $res['data']['status']
            ?? $res['status']
            ?? $payment->status;

        if ($newStatus && $newStatus !== $payment->status) {
            $payment->status = $newStatus;
            if ($newStatus === 'settlement' && ! $payment->paid_at) {
                $payment->paid_at = now();
            }
            $payment->save();
        }

        return response()->json($this->statusPayload($payment));
    }

    /**
     * @return array<string, mixed>
     */
    protected function statusPayload(Payment $payment): array
    {
        return [
            'status' => $payment->status,
            'label' => Payment::STATUSES[$payment->status] ?? $payment->status,
            'paid' => $payment->isPaid(),
            'final' => $payment->isFinal(),
        ];
    }

    protected function parseTime(?string $value): ?Carbon
    {
        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
