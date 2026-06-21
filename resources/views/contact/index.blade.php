@extends('layouts.app')

@section('title', 'Kontak — ' . setting('site_name', config('app.name', 'RPD')))

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4">Hubungi Kami</h1>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp" class="form-label">Nomor WhatsApp</label>
                            <input type="text" class="form-control @error('whatsapp') is-invalid @enderror"
                                   id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}">
                            @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                   id="subject" name="subject" value="{{ old('subject') }}">
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror"
                                      id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Informasi Kontak</h5>
                    @if ($address = setting('company_address'))
                        <p class="mb-2"><i class="bi bi-geo-alt text-primary"></i> {{ $address }}</p>
                    @endif
                    @if ($phone = setting('company_phone'))
                        <p class="mb-2"><i class="bi bi-telephone text-primary"></i> {{ $phone }}</p>
                    @endif
                    @if ($wa = setting('company_whatsapp'))
                        <p class="mb-2"><i class="bi bi-whatsapp text-success"></i>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $wa) }}" target="_blank" rel="noopener">{{ $wa }}</a>
                        </p>
                    @endif
                    @if ($email = setting('company_email'))
                        <p class="mb-2"><i class="bi bi-envelope text-primary"></i> {{ $email }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
