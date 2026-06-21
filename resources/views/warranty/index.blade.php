@extends('layouts.app')

@section('title', 'Klaim Garansi — ' . setting('site_name', config('app.name', 'RPD')))

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4">Klaim Garansi</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted">Lengkapi formulir di bawah ini untuk mengajukan klaim garansi produk Anda.</p>

                    <form method="POST" action="{{ route('warranty.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                       id="full_name" name="full_name"
                                       value="{{ old('full_name', auth()->user()->name ?? '') }}" required>
                                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email"
                                       value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="whatsapp" class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('whatsapp') is-invalid @enderror"
                                       id="whatsapp" name="whatsapp"
                                       value="{{ old('whatsapp', auth()->user()->whatsapp ?? '') }}" required>
                                @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="invoice_number" class="form-label">Nomor Invoice <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('invoice_number') is-invalid @enderror"
                                       id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" required>
                                @error('invoice_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="product_name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                       id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                                @error('product_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="complaint" class="form-label">Keluhan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('complaint') is-invalid @enderror"
                                          id="complaint" name="complaint" rows="4" required>{{ old('complaint') }}</textarea>
                                @error('complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="attachment" class="form-label">Lampiran (jpg, png, pdf — maks 2MB)</label>
                                <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                       id="attachment" name="attachment" accept=".jpg,.jpeg,.png,.pdf">
                                @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Kirim Klaim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
