@extends('layouts.app')

@section('title', 'Pembayaran QRIS')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
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
                    <div class="pay-amount" style="font-size: 1.3rem;">Pembayaran QRIS</div>
                    <div class="pay-orderid">Masukkan nominal & bayar via QRIS</div>
                </div>

                <hr class="mx-4 my-0">

                <div class="card-body px-4 py-4">
                    <form method="POST" action="{{ route('pay.create') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label fw-semibold">Nominal (Rp)</label>
                            <input type="number" min="1000" max="10000000" step="1000"
                                   class="form-control form-control-lg text-center fw-bold @error('amount') is-invalid @enderror"
                                   id="amount" name="amount" value="{{ old('amount') }}"
                                   placeholder="Masukkan nominal..." required autofocus>
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Preset tombol --}}
                        <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
                            @foreach([10000, 25000, 50000, 100000] as $v)
                                <button type="button" class="btn btn-outline-primary btn-sm fw-bold preset"
                                        data-v="{{ $v }}">
                                    Rp {{ number_format($v, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                            <i class="bi bi-qr-code me-1"></i> Buat QRIS
                        </button>
                    </form>
                </div>

                <div class="pay-footer text-center px-4 pb-4">
                    <i class="bi bi-shield-lock me-1"></i>
                    Transaksi aman & terenkripsi
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.pay-card { border-radius: 24px; overflow: hidden; }
.pay-header { background: linear-gradient(135deg, var(--rz-primary), var(--rz-primary-2)); color: #fff; }
.pay-brand-text { font-weight: 800; font-size: 1.1rem; letter-spacing: -.02em; }
.pay-logo { object-fit: contain; filter: brightness(0) invert(1); }
.pay-amount { font-size: 2.1rem; font-weight: 800; letter-spacing: -.03em; margin: .3rem 0 .15rem; }
.pay-orderid { font-size: .82rem; opacity: .75; }
.pay-footer { color: var(--rz-ink-soft); font-size: .82rem; }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.preset').forEach(function (b) {
        b.addEventListener('click', function () {
            var input = document.getElementById('amount');
            input.value = b.getAttribute('data-v');
            input.focus();
        });
    });
</script>
@endpush
