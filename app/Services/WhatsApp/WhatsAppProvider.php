<?php

namespace App\Services\WhatsApp;

interface WhatsAppProvider
{
    /**
     * Send a WhatsApp text message to the given recipient.
     *
     * @param  string  $to       Recipient WhatsApp number (E.164 or local format).
     * @param  string  $message  The message body.
     * @return bool  Whether the message was accepted/dispatched.
     */
    public function send(string $to, string $message): bool;
}
