@extends('layouts.app')

@section('title', 'Pembayaran — Rp ' . number_format($payment->amount, 0, ',', '.'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            {{-- Card utama --}}
            <div class="pay-card card border-0 shadow-lg">

                {{-- Header --}}
                <div class="pay-header text-center px-4 pt-4 pb-3">
                    <div class="pay-brand mb-2">
                        @if($logo = setting_asset('logo'))
                            <img src="{{ $logo }}" alt="{{ setting('site_name', config('app.name')) }}" height="36" class="pay-logo">
                        @else
                            <span class="pay-brand-text">{{ setting('site_name', config('app.name', 'RPD')) }}</span>
                        @endif
                    </div>
                    <div class="pay-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                    <div class="pay-orderid">{{ $payment->order_id }}</div>
                </div>

                <hr class="mx-4 my-0">

                {{-- Body --}}
                <div class="card-body px-4 py-4 text-center">

                    {{-- PENDING --}}
                    <div id="state-pending" @class(['d-none' => $payment->isFinal()])>
                        @if ($payment->qr_url)
                            <div class="qr-wrap mx-auto mb-3">
                                <img src="{{ $payment->qr_url }}" alt="QRIS" class="qr-img">
                            </div>
                        @endif

                        <div class="pay-instructions mb-3">
                            <p class="mb-1 fw-semibold" style="color: var(--rz-ink);">Scan QRIS di atas dengan</p>
                            <div class="pay-apps d-flex justify-content-center flex-wrap gap-2">
                                @foreach(['GoPay','OVO','Dana','ShopeePay','LinkAja'] as $app)
                                    <span class="pay-app-pill">{{ $app }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="pay-timer-box mb-3">
                            <i class="bi bi-clock me-1"></i>
                            Berlaku selama <span id="countdown" class="fw-bold">--:--</span>
                        </div>

                        <div class="pay-status-badge pending">
                            <span class="spinner-grow spinner-grow-sm me-2"></span>
                            Menunggu Pembayaran
                        </div>
                    </div>

                    {{-- SUCCESS --}}
                    <div id="state-success" @class(['d-none' => $payment->status !== 'settlement'])>
                        <div class="state-icon success mb-3">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <h2 class="h4 fw-bold mb-1" style="color: #16a34a;">Pembayaran Berhasil!</h2>
                        <p class="text-secondary mb-4">Terima kasih, transaksi kamu berhasil diverifikasi.</p>
                        @if($payment->issuer)
                            <div class="meta-row mb-4">
                                <span class="meta-label">Metode</span>
                                <span class="meta-val">{{ $payment->issuer }}</span>
                            </div>
                        @endif
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg w-100">Kembali ke Beranda</a>
                    </div>

                    {{-- EXPIRED / CANCEL --}}
                    <div id="state-failed" @class(['d-none' => ! in_array($payment->status, ['expire', 'cancel'])])>
                        <div class="state-icon failed mb-3">
                            <i class="bi bi-x-lg"></i>
                        </div>
                        <h2 class="h4 fw-bold mb-1 text-danger">
                            {{ $payment->status === 'expire' ? 'QR Kedaluwarsa' : 'Pembayaran Dibatalkan' }}
                        </h2>
                        <p class="text-secondary mb-4">
                            {{ $payment->status === 'expire' ? 'Waktu pembayaran habis.' : 'Transaksi telah dibatalkan.' }}
                        </p>
                        <a href="{{ route('pay.form') }}" class="btn btn-primary btn-lg w-100">Buat Pembayaran Baru</a>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="pay-footer text-center px-4 pb-4">
                    <i class="bi bi-shield-lock me-1"></i>
                    Transaksi aman & terenkripsi
                </div>

            </div>
            {{-- /card --}}

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.pay-card { border-radius: 24px; overflow: hidden; }

/* Header */
.pay-header { background: linear-gradient(135deg, var(--rz-primary), var(--rz-primary-2)); color: #fff; }
.pay-brand-text { font-weight: 800; font-size: 1.1rem; letter-spacing: -.02em; }
.pay-logo { object-fit: contain; filter: brightness(0) invert(1); }
.pay-amount { font-size: 2.1rem; font-weight: 800; letter-spacing: -.03em; margin: .3rem 0 .15rem; }
.pay-orderid { font-size: .82rem; opacity: .75; letter-spacing: .02em; }

/* QR */
.qr-wrap {
    width: 220px; height: 220px; border-radius: 20px;
    background: #fff; padding: 10px;
    box-shadow: 0 8px 30px -10px rgba(0,0,0,.18);
    display: grid; place-items: center;
}
.qr-img { width: 100%; height: 100%; object-fit: contain; }

/* App pills */
.pay-app-pill {
    background: rgba(var(--rz-primary-rgb), .08); color: var(--rz-primary);
    border-radius: 999px; padding: .28rem .7rem; font-size: .8rem; font-weight: 700;
}

/* Timer */
.pay-timer-box {
    display: inline-flex; align-items: center; gap: .35rem;
    background: #fffbeb; color: #b45309;
    border: 1px solid #fde68a; border-radius: 999px;
    padding: .4rem 1rem; font-size: .9rem; font-weight: 600;
}

/* Status badge */
.pay-status-badge {
    display: inline-flex; align-items: center;
    border-radius: 999px; padding: .45rem 1.1rem; font-weight: 700; font-size: .9rem;
}
.pay-status-badge.pending {
    background: #eff6ff; color: #2563eb;
    border: 1px solid #bfdbfe;
}

/* State icons */
.state-icon {
    width: 80px; height: 80px; border-radius: 50%; margin: 0 auto;
    display: grid; place-items: center; font-size: 2rem;
}
.state-icon.success { background: #dcfce7; color: #16a34a; }
.state-icon.failed  { background: #fee2e2; color: #dc2626; }

/* Meta row */
.meta-row { display: flex; justify-content: space-between; align-items: center;
    background: var(--rz-bg); border-radius: 12px; padding: .7rem 1rem; }
.meta-label { color: var(--rz-ink-soft); font-size: .9rem; }
.meta-val { font-weight: 700; color: var(--rz-ink); }

/* Footer */
.pay-footer { color: var(--rz-ink-soft); font-size: .82rem; }

/* Dark mode tweaks */
[data-bs-theme="dark"] .qr-wrap { background: #fff; }
[data-bs-theme="dark"] .pay-timer-box { background: #2d1f00; border-color: #92400e; color: #fbbf24; }
[data-bs-theme="dark"] .pay-status-badge.pending { background: #1e2a45; border-color: #3b5998; color: #93c5fd; }
[data-bs-theme="dark"] .meta-row { background: var(--rz-surface); }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var statusUrl = "{{ route('pay.status', $payment->order_id) }}";
    var isFinal   = {{ $payment->isFinal() ? 'true' : 'false' }};
    var expiry    = "{{ optional($payment->expired_at)->toIso8601String() }}";

    var pending  = document.getElementById('state-pending');
    var success  = document.getElementById('state-success');
    var failed   = document.getElementById('state-failed');
    var countEl  = document.getElementById('countdown');

    function render(status) {
        pending.classList.add('d-none');
        success.classList.add('d-none');
        failed.classList.add('d-none');
        if (status === 'settlement') { success.classList.remove('d-none'); }
        else if (status === 'expire' || status === 'cancel') { failed.classList.remove('d-none'); }
        else { pending.classList.remove('d-none'); }
    }

    if (expiry && countEl) {
        var exp = new Date(expiry).getTime();
        function tick() {
            var diff = Math.max(0, Math.floor((exp - Date.now()) / 1000));
            countEl.textContent = String(Math.floor(diff / 60)).padStart(2,'0') + ':' + String(diff % 60).padStart(2,'0');
        }
        tick(); setInterval(tick, 1000);
    }

    if (!isFinal) {
        var poll = setInterval(function () {
            fetch(statusUrl, { headers: { Accept: 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (d) { render(d.status); if (d.final) clearInterval(poll); })
                .catch(function () {});
        }, 3000);
    }
})();
</script>
@endpush
