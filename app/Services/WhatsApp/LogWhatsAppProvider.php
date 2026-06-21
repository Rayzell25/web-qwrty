<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Log;

/**
 * Fallback provider used when no real WhatsApp gateway is configured.
 * It simply logs the outgoing message so the OTP flow stays runnable
 * locally without external dependencies.
 */
class LogWhatsAppProvider implements WhatsAppProvider
{
    public function send(string $to, string $message): bool
    {
        Log::info('[WhatsApp:log] Simulated message', [
            'to' => $to,
            'message' => $message,
        ]);

        return true;
    }
}
