@extends('layouts.app')

@section('title', 'Top Up / Pembayaran QRIS')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1 text-center">Pembayaran QRIS</h1>
                    <p class="text-secondary text-center mb-4">Masukkan nominal, lalu scan QRIS untuk membayar.</p>

                    <form method="POST" action="{{ route('pay.create') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Nominal (Rp)</label>
                            <input type="number" min="1000" max="10000000" step="1000"
                                   class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                   id="amount" name="amount" value="{{ old('amount', 10000) }}" required autofocus>
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Min Rp 1.000 — Maks Rp 10.000.000</div>
                        </div>

                        <div class="d-grid gap-2 mb-2">
                            <button type="button" class="btn btn-outline-primary btn-sm preset" data-v="10000">Rp 10.000</button>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-qr-code"></i> Buat QRIS
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.preset').forEach(function (b) {
        b.addEventListener('click', function () {
            document.getElementById('amount').value = b.getAttribute('data-v');
        });
    });
</script>
@endpush
