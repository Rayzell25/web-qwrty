<?php

namespace App\Services\Payment;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Client untuk gateway pembayaran AutoGoPay (QRIS dinamis).
 * Dokumentasi: https://v1-gateway.autogopay.site
 *
 * API key TIDAK pernah hardcode di sini — selalu dari config/.env.
 */
class AutoGoPayClient
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.autogopay.base_url'), '/');
        $this->apiKey = (string) config('services.autogopay.api_key');
    }

    public function isEnabled(): bool
    {
        return filled($this->apiKey) && filled($this->baseUrl);
    }

    protected function http(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->apiKey)
            ->acceptJson()
            ->timeout(20);
    }

    /**
     * Buat QRIS dinamis untuk sejumlah nominal (IDR).
     *
     * @return array<string, mixed>
     */
    public function generateQris(int $amount): array
    {
        try {
            $res = $this->http()->post('/qris/generate', ['amount' => $amount]);

            return $res->json() ?? ['success' => false, 'message' => 'Respon kosong dari gateway.'];
        } catch (\Throwable $e) {
            Log::error('[AutoGoPay] generateQris error', ['error' => $e->getMessage()]);

            return ['success' => false, 'message' => 'Tidak dapat menghubungi gateway pembayaran.'];
        }
    }

    /**
     * Cek status sebuah transaksi QRIS berdasarkan order_id.
     *
     * @return array<string, mixed>
     */
    public function status(string $orderId): array
    {
        try {
            $res = $this->http()->post('/qris/status', ['order_id' => $orderId]);

            return $res->json() ?? ['success' => false];
        } catch (\Throwable $e) {
            Log::error('[AutoGoPay] status error', ['error' => $e->getMessage()]);

            return ['success' => false];
        }
    }

    /**
     * Batalkan transaksi QRIS yang masih pending.
     *
     * @return array<string, mixed>
     */
    public function cancel(string $orderId): array
    {
        try {
            $res = $this->http()->post('/qris/cancel', ['order_id' => $orderId]);

            return $res->json() ?? ['success' => false];
        } catch (\Throwable $e) {
            Log::error('[AutoGoPay] cancel error', ['error' => $e->getMessage()]);

            return ['success' => false];
        }
    }

    /**
     * Verifikasi signature webhook (HMAC-SHA256 dari raw payload + API key).
     */
    public function verifySignature(string $payload, ?string $signature): bool
    {
        if (blank($signature) || blank($this->apiKey)) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $this->apiKey);

        return hash_equals($expected, $signature);
    }
}
