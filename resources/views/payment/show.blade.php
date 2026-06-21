@extends('layouts.app')

@section('title', 'Pembayaran #' . $payment->order_id)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4 text-center">

                    <h1 class="h4 mb-1">Scan untuk Membayar</h1>
                    <p class="text-secondary mb-3">Order: <code>{{ $payment->order_id }}</code></p>

                    <div class="display-6 fw-bold mb-3">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>

                    {{-- Area status --}}
                    <div id="statusBox" class="mb-3">
                        <div id="state-pending" @class(['d-none' => $payment->isFinal()])>
                            @if ($payment->qr_url)
                                <img src="{{ $payment->qr_url }}" alt="QRIS" class="img-fluid rounded border" style="max-width: 280px;">
                            @endif
                            <div class="alert alert-warning mt-3 mb-0">
                                <span class="spinner-border spinner-border-sm"></span>
                                Menunggu pembayaran...
                                <span id="countdown" class="fw-bold"></span>
                            </div>
                            @if ($payment->checkout_url)
                                <a href="{{ $payment->checkout_url }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm mt-3">
                                    Buka Halaman Pembayaran <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            @endif
                        </div>

                        <div id="state-success" @class(['d-none' => $payment->status !== 'settlement'])>
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3.5rem;"></i>
                            <h2 class="h4 mt-2 text-success">Pembayaran Berhasil</h2>
                            <p class="text-secondary">Terima kasih, pembayaran Anda sudah kami terima.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
                        </div>

                        <div id="state-failed" @class(['d-none' => ! in_array($payment->status, ['expire', 'cancel'])])>
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 3.5rem;"></i>
                            <h2 class="h4 mt-2 text-danger">Pembayaran {{ $payment->status === 'expire' ? 'Kedaluwarsa' : 'Dibatalkan' }}</h2>
                            <a href="{{ route('pay.form') }}" class="btn btn-outline-primary">Coba Lagi</a>
                        </div>
                    </div>

                    <p class="text-secondary small mb-0">Status diperbarui otomatis. Jangan tutup halaman ini saat membayar.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var statusUrl = "{{ route('pay.status', $payment->order_id) }}";
    var isFinal = {{ $payment->isFinal() ? 'true' : 'false' }};
    var expiry = "{{ optional($payment->expired_at)->toIso8601String() }}";

    var pending = document.getElementById('state-pending');
    var success = document.getElementById('state-success');
    var failed  = document.getElementById('state-failed');
    var countdownEl = document.getElementById('countdown');

    function render(status) {
        pending.classList.add('d-none');
        success.classList.add('d-none');
        failed.classList.add('d-none');
        if (status === 'settlement') { success.classList.remove('d-none'); }
        else if (status === 'expire' || status === 'cancel') { failed.classList.remove('d-none'); }
        else { pending.classList.remove('d-none'); }
    }

    // Countdown
    if (expiry) {
        var exp = new Date(expiry).getTime();
        setInterval(function () {
            var diff = Math.max(0, Math.floor((exp - Date.now()) / 1000));
            var m = String(Math.floor(diff / 60)).padStart(2, '0');
            var s = String(diff % 60).padStart(2, '0');
            if (countdownEl) countdownEl.textContent = '(' + m + ':' + s + ')';
        }, 1000);
    }

    // Polling status tiap 3 detik
    if (!isFinal) {
        var poll = setInterval(function () {
            fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    render(d.status);
                    if (d.final) { clearInterval(poll); }
                })
                .catch(function () {});
        }, 3000);
    }
})();
</script>
@endpush
