<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Generic HTTP provider that posts to a configurable WhatsApp gateway
 * endpoint. Designed to work with common self-hosted gateways that accept
 * a JSON body of { sender, number, message } and a Bearer/API token.
 *
 * If the gateway is not reachable or misconfigured it logs the failure and
 * returns false instead of throwing, keeping the OTP flow resilient.
 */
class HttpWhatsAppProvider implements WhatsAppProvider
{
    public function send(string $to, string $message): bool
    {
        $url = config('services.whatsapp.api_url');
        $token = config('services.whatsapp.api_token');
        $sender = config('services.whatsapp.sender');

        if (blank($url)) {
            Log::warning('[WhatsApp:http] WHATSAPP_API_URL is not configured. Falling back to log.', [
                'to' => $to,
                'message' => $message,
            ]);

            return false;
        }

        try {
            $request = Http::timeout(15)->acceptJson();

            if (filled($token)) {
                $request = $request->withToken($token);
            }

            $response = $request->post($url, [
                'sender' => $sender,
                'number' => $to,
                'target' => $to,
                'message' => $message,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('[WhatsApp:http] Gateway returned an error response.', [
                'status' => $response->status(),
                'body' => $response->body(),
                'to' => $to,
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('[WhatsApp:http] Failed to send message.', [
                'error' => $e->getMessage(),
                'to' => $to,
            ]);

            return false;
        }
    }
}
