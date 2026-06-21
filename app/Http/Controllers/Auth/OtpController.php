<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\Otp\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function __construct(
        protected OtpService $otpService,
    ) {
    }

    public function showVerifyForm(Request $request)
    {
        $user = $request->user();

        // Already verified users do not need the OTP page.
        if ($user->hasVerifiedOtp()) {
            return redirect()
                ->route('home')
                ->with('info', 'Akun Anda sudah terverifikasi.');
        }

        return view('auth.otp-verify', [
            'whatsapp' => $user->whatsapp,
        ]);
    }

    public function verify(VerifyOtpRequest $request)
    {
        $user = $request->user();

        $result = $this->otpService->verify($user, $request->validated()['otp']);

        return match ($result) {
            'success' => redirect()
                ->route('home')
                ->with('success', 'Verifikasi berhasil. Akun Anda kini aktif sepenuhnya.'),
            'already' => redirect()
                ->route('home')
                ->with('info', 'Akun Anda sudah terverifikasi.'),
            'expired' => back()
                ->with('error', 'Kode OTP telah kedaluwarsa. Silakan minta kode baru.'),
            default => back()
                ->with('error', 'Kode OTP yang Anda masukkan salah.'),
        };
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedOtp()) {
            return redirect()
                ->route('home')
                ->with('info', 'Akun Anda sudah terverifikasi.');
        }

        $this->otpService->generateAndSend($user);

        return back()->with('success', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
    }
}
