@extends('layouts.app')

@section('title', 'Verifikasi OTP — ' . setting('site_name', config('app.name', 'RPD')))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-shield-lock display-4 text-primary"></i>
                    <h1 class="h4 mt-3 mb-2">Verifikasi OTP</h1>
                    <p class="text-muted">
                        Kami telah mengirim kode OTP 6 digit
                        @if (! empty($whatsapp))
                            ke WhatsApp <strong>{{ $whatsapp }}</strong>.
                        @else
                            ke kontak Anda.
                        @endif
                    </p>

                    <form method="POST" action="{{ route('otp.verify.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="text" inputmode="numeric" maxlength="6"
                                   class="form-control form-control-lg text-center @error('otp') is-invalid @enderror"
                                   id="otp" name="otp" value="{{ old('otp') }}"
                                   placeholder="● ● ● ● ● ●" required autofocus
                                   style="letter-spacing: .5rem; font-weight: 600;">
                            @error('otp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Verifikasi</button>
                    </form>

                    <form method="POST" action="{{ route('otp.resend') }}">
                        @csrf
                        <p class="mb-1 text-muted small">Tidak menerima kode?</p>
                        <button type="submit" class="btn btn-link p-0">Kirim Ulang OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
