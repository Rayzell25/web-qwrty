<?php

namespace App\Services\Otp;

use App\Models\User;
use App\Services\WhatsApp\WhatsAppProvider;
use Illuminate\Support\Carbon;

class OtpService
{
    public function __construct(
        protected WhatsAppProvider $whatsApp,
    ) {
    }

    /**
     * Generate a fresh OTP for the user, persist it, set expiry and send it.
     */
    public function generateAndSend(User $user): string
    {
        $code = $this->generateCode();

        $user->forceFill([
            'otp_code' => $code,
            'otp_expires_at' => Carbon::now()->addMinutes($this->ttlMinutes()),
        ])->save();

        $this->sendOtp($user, $code);

        return $code;
    }

    /**
     * Verify a submitted OTP code for the user.
     *
     * Returns one of: 'success', 'invalid', 'expired', 'already'.
     */
    public function verify(User $user, string $code): string
    {
        if ($user->hasVerifiedOtp()) {
            return 'already';
        }

        if (blank($user->otp_code) || blank($user->otp_expires_at)) {
            return 'invalid';
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return 'expired';
        }

        if (! hash_equals((string) $user->otp_code, trim($code))) {
            return 'invalid';
        }

        $user->forceFill([
            'otp_verified_at' => Carbon::now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        return 'success';
    }

    /**
     * Determine if the user can request a new OTP (basic throttle helper).
     */
    public function send(User $user): bool
    {
        $code = $this->generateAndSend($user);

        return filled($code);
    }

    protected function sendOtp(User $user, string $code): void
    {
        $appName = config('app.name', 'RPD');
        $ttl = $this->ttlMinutes();
        $message = "Kode OTP {$appName} Anda adalah: {$code}. Berlaku selama {$ttl} menit. Jangan bagikan kode ini kepada siapa pun.";

        $recipient = $user->whatsapp ?: $user->email;

        if (config('services.whatsapp.enabled')) {
            $this->whatsApp->send($recipient, $message);
        } else {
            // OTP delivery disabled: log the code so the flow remains testable.
            \Illuminate\Support\Facades\Log::info('[OTP] Delivery disabled, code generated.', [
                'user_id' => $user->id,
                'to' => $recipient,
                'code' => $code,
            ]);
        }
    }

    protected function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    protected function ttlMinutes(): int
    {
        return (int) config('services.whatsapp.otp_ttl', 5);
    }
}
