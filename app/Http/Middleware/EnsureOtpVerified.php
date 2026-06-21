<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpVerified
{
    /**
     * Routes that must remain reachable even when OTP is not yet verified,
     * to avoid redirect loops.
     *
     * @var array<int, string>
     */
    protected array $allowed = [
        'otp.verify',
        'otp.verify.submit',
        'otp.resend',
        'logout',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && ! $user->hasVerifiedOtp()) {
            // Allow the OTP-related routes through to break any loop.
            if ($request->routeIs($this->allowed)) {
                return $next($request);
            }

            return redirect()
                ->route('otp.verify')
                ->with('warning', 'Silakan verifikasi kode OTP Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
