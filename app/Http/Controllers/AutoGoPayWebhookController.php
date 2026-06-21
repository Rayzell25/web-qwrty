<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payment\AutoGoPayClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AutoGoPayWebhookController extends Controller
{
    public function handle(Request $request, AutoGoPayClient $client)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Signature');

        // Verifikasi signature WAJIB.
        if (! $client->verifySignature($payload, $signature)) {
            Log::warning('[AutoGoPay] Webhook signature tidak valid.');

            return response()->json(['success' => false, 'error' => 'invalid signature'], 401);
        }

        $data = json_decode($payload, true) ?: [];
        $tx = $data['transaction'] ?? null;

        if (! is_array($tx)) {
            return response()->json(['success' => true]);
        }

        $status = $tx['status'] ?? null;
        $amount = (int) ($tx['amount'] ?? 0);
        $txId = $tx['id'] ?? null;

        // Coba cocokkan ke transaksi: utamakan transaction_id, lalu nominal unik yang masih pending.
        $payment = Payment::query()
            ->when($txId, fn ($q) => $q->where('transaction_id', $txId))
            ->first();

        if (! $payment && $status === 'settlement' && $amount > 0) {
            $candidates = Payment::where('status', 'pending')->where('amount', $amount)->get();
            if ($candidates->count() === 1) {
                $payment = $candidates->first();
            } else {
                Log::info('[AutoGoPay] Webhook nominal ambigu / tidak cocok.', [
                    'amount' => $amount, 'matches' => $candidates->count(),
                ]);
            }
        }

        if ($payment && ! $payment->isFinal() && $status) {
            $payment->status = $status;
            $payment->issuer = $tx['issuer'] ?? $payment->issuer;
            if ($status === 'settlement' && ! $payment->paid_at) {
                $payment->paid_at = now();
            }
            $payment->save();
        }

        // Wajib balas 200 dalam 10 detik.
        return response()->json(['success' => true]);
    }
}
