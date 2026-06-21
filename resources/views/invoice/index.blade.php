@extends('layouts.app')

@section('title', 'Cek Invoice — ' . setting('site_name', config('app.name', 'RPD')))

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4">Cek Invoice</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('invoice.check') }}">
                        @csrf
                        <label for="invoice_number" class="form-label">Masukkan Nomor Invoice</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('invoice_number') is-invalid @enderror"
                                   id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}"
                                   placeholder="Contoh: INV-2024-0001" required>
                            <button class="btn btn-primary" type="submit">Cek</button>
                            @error('invoice_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </form>
                </div>
            </div>

            @if (! empty($invoice))
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-check-circle"></i> Invoice Ditemukan
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr><th style="width: 200px;">Nomor Invoice</th><td>{{ $invoice->invoice_number }}</td></tr>
                                <tr><th>Nama Customer</th><td>{{ $invoice->customer_name }}</td></tr>
                                <tr><th>Produk</th><td>{{ $invoice->product_name }}</td></tr>
                                <tr><th>Tanggal Pembelian</th><td>{{ optional($invoice->purchase_date)->format('d M Y') ?? '-' }}</td></tr>
                                <tr><th>Status Garansi</th><td>
                                    <span class="badge bg-info text-dark">{{ $invoice->warranty_status ?? 'Tidak diketahui' }}</span>
                                </td></tr>
                                <tr><th>Status Invoice</th><td>
                                    <span class="badge bg-primary">{{ $invoice->invoice_status ?? 'Tidak diketahui' }}</span>
                                </td></tr>
                                @if ($invoice->notes)
                                    <tr><th>Catatan</th><td>{{ $invoice->notes }}</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif (! empty($searched))
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    {{ session('error', 'Invoice tidak ditemukan. Pastikan nomor invoice yang Anda masukkan benar.') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
