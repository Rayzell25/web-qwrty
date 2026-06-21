<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\Otp\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        protected OtpService $otpService,
    ) {
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'whatsapp' => $data['whatsapp'],
            'password' => $data['password'],
            'is_admin' => false,
            'is_active' => true,
        ]);

        // Generate and send the first OTP, then log the user in.
        $this->otpService->generateAndSend($user);

        Auth::login($user);

        return redirect()
            ->route('otp.verify')
            ->with('success', 'Pendaftaran berhasil. Kode OTP telah dikirim ke WhatsApp Anda.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau kata sandi salah.');
        }

        if (! $user->is_active) {
            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akun Anda sedang tidak aktif. Silakan hubungi administrator.');
        }

        $request->session()->regenerate();

        // If the user has not verified OTP yet, issue a new one.
        if (! $user->hasVerifiedOtp()) {
            $this->otpService->generateAndSend($user);

            return redirect()
                ->route('otp.verify')
                ->with('success', 'Kode OTP baru telah dikirim. Silakan verifikasi untuk melanjutkan.');
        }

        return redirect()
            ->intended(route('home'))
            ->with('success', 'Selamat datang kembali, '.$user->name.'!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Anda telah keluar.');
    }
}
